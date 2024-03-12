<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class GithubSearchService
{
    protected $graphqlEndpoint = 'https://api.github.com/graphql';

    public function search(array $filters): array
    {
        $cacheKey = 'github_search_' . md5(serialize($filters));

        //////////////////////
        // Cache::forget($cacheKey); Just for testing
        //////////////////////

        $cachedResponse = Cache::get($cacheKey);
        if ($cachedResponse) {
            return $cachedResponse;
        }

        $searchQuery = $this->buildSearchQuery($filters);

        $query = File::get(base_path('graphql/queries/searchQuery.graphql'));

        $variables = [
            'query' => $searchQuery,
            'first' => 10,
        ];

        if (!empty($filters['after'])) {
            $variables['after'] = $filters['after'];
        }

        $response = Http::withOptions(['verify' => false])->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . config('services.github.token'),
        ])->post($this->graphqlEndpoint, [
            'query' => $query,
            'variables' => $variables,
        ]);

        if (!$response->successful()) {
            return ['error' => 'Unable to fetch data'];
        }

        $data = $response->json();

        Cache::put($cacheKey, $data, 3600);

        return $data;
    }

    public function searchAndTransform(array $filters): array
    {
        $rawResponse = $this->search($filters);

        if (isset($rawResponse['error'])) {
            return $rawResponse;
        }

        return $this->transformResponse($rawResponse);
    }

    public function transformResponse($response)
    {
        $userCount = $response['data']['search']['userCount'];
        $pageInfo = $response['data']['search']['pageInfo'];

        $githubUsers = collect($response['data']['search']['nodes'])
            ->map(function ($githubUser) {
                $pinnedItemsNodes = collect($githubUser['pinnedItems']['nodes'] ?? []);
                $topRepositoriesNodes = collect($githubUser['topRepositories']['nodes'] ?? []);

                // $pinnedLanguages: All languages from the pinned_items (unique by name)
                // $primaryLanguageFromPinned: The primary language is determined by which one appears most frequently as the primary language, and in the case of a tie, the one with the most stars is chosen as the tiebreaker
                $pinnedLanguages = $this->extractLanguages($pinnedItemsNodes);
                $primaryLanguageFromPinned = $this->determinePrimaryLanguage($pinnedItemsNodes);

                $topRepoLanguages = $this->extractLanguages($topRepositoriesNodes);
                $primaryLanguageFromTopRepos = $this->determinePrimaryLanguage($topRepositoriesNodes);

                return [
                    // Developer information
                    'avatar_url' => $githubUser['avatarUrl'],
                    'name' => $githubUser['name'],
                    'login' => $githubUser['login'],
                    'email' => $githubUser['email'],
                    'location' => $githubUser['location'],
                    'github_url' => 'https://github.com/' . $githubUser['login'],
                    'bio_html' => $githubUser['bioHTML'],
                    'twitter_username' => $githubUser['twitterUsername'],
                    'is_hireable' => $githubUser['isHireable'],
                    'website_url' => $githubUser['websiteUrl'],
                    'is_github_star' => $githubUser['isGitHubStar'],

                    // Languages
                    'pinned_languages' => $pinnedLanguages,
                    'pinned_primary_language' => $primaryLanguageFromPinned,

                    'top_repositories_languages' => $topRepoLanguages,
                    'top_repositories_primary_language' => $primaryLanguageFromTopRepos,

                    // Github statistics
                    'followers_count' => $githubUser['followers']['totalCount'],
                    'repositories_count' => $githubUser['repositories']['totalCount'],
                    'github_updated_at' => $githubUser['updatedAt'],

                    // Main repositories maintained
                    'pinned_items' => $githubUser['pinnedItems']['nodes'],
                    'pinned_items_count' => $githubUser['pinnedItems']['totalCount'],
                    'pinned_items_stars_count' => collect($githubUser['pinnedItems']['nodes'])
                        ->sum(fn($repo) => $repo['stargazers']['totalCount']),

                    // Contributions
                    'contributions_last_year' => $githubUser['contributionsCollection'],
                    'repositories_contributed_count' => $githubUser['repositoriesContributedTo']['totalCount'],
                ];
            })
            ->all();

        return [
            'userCount' => $userCount,
            'users' => $githubUsers,
            'pageInfo' => $pageInfo,
        ];
    }

    protected function buildSearchQuery(array $filters): string
    {
        $queryParts = [
            'type:User', // Search for GitHub users, not organizations.
        ];

        if (isset($filters['followers'])) {
            $queryParts[] = 'followers:' . $filters['followers'];
        }
        if (isset($filters['location'])) {
            $queryParts[] = 'location:"' . $filters['location'] . '"';
        }
        if (isset($filters['repositories'])) {
            $queryParts[] = 'repos:' . $filters['repositories'];
        }
        if (isset($filters['languages']) && is_array($filters['languages'])) {
            foreach ($filters['languages'] as $language) {
                $queryParts[] = 'language:' . $language;
            }
        }
        return implode(' ', $queryParts);
    }

    protected function extractLanguages($items)
    {
        return collect($items)
            ->flatMap(fn($item) => collect($item['languages']['nodes'] ?? [])
                ->map(fn($language) => [
                    'name' => $language['name'] ?? 'Unknown',
                    'color' => $language['color'] ?? 'N/A',
                ])
            )
            ->unique('name')
            ->values()
            ->all();
    }

    protected function determinePrimaryLanguage($items)
    {
        return collect($items)
            ->map(fn($item) => [
                'name' => $item['primaryLanguage']['name'] ?? 'Unknown',
                'color' => $item['primaryLanguage']['color'] ?? 'N/A',
                'stars' => $item['stargazers']['totalCount'] ?? 0,
            ])
            ->filter(fn($language) => $language['name'] !== 'Unknown')
            ->groupBy('name')
            ->map(fn($group, $name) => [
                'name' => $name,
                'color' => $group->first()['color'],
                'total_stars' => $group->sum('stars'),
                'count' => $group->count(),
            ])
            ->sortByDesc(fn($language) => [$language['count'], $language['total_stars']])
            ->first();
    }

}
