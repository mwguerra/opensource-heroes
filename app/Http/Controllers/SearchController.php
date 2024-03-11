<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Services\GithubSearchService;

class SearchController extends Controller
{
    public function index(Request $request, GithubSearchService $githubSearchService)
    {
        $filters = $this->processFilters($request);

        $data = $githubSearchService->searchAndTransform($filters);

        $retries = 2;
        while (isset($data['error']) && $retries > 0) {
            $retries--;
            $data = $githubSearchService->searchAndTransform($filters);
        }

        return Inertia::render('Search', [
            'results' => $data,
            'filters' => $filters,
            'pageInfo' => $data['pageInfo'] ?? null,
        ]);
    }

    public function getMoreUsers(Request $request, GithubSearchService $githubSearchService)
    {
        $filters = $this->processFilters($request);

        $data = $githubSearchService->searchAndTransform($filters);

        return response()->json([
            'results' => $data,
            'filters' => $filters,
            'pageInfo' => $data['pageInfo'] ?? null,
        ]);
    }

    protected function processFilters(Request $request): array
    {
        $filters = [];

        // User search parameters
        if (!is_null($request->query('location'))) {
            $filters['location'] = Str::of($request->location)->trim()->lower()->toString();
        }

        if (!is_null($request->query('languages'))) {
            $filters['languages'] = collect(explode(',', $request->query('languages')))
                ->map(fn($item) => Str::of($item)->trim()->lower()->toString())
                ->unique()
                ->toArray();
        }

        $filters['followers'] = $request->query('followers', '>50');
        $filters['repositories'] = $request->query('repositories', '>10');

        // Pagination
        $filters['after'] = $request->input('after', null);

        return $filters;
    }
}
