<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HeroController extends Controller
{
    public function index(Request $request)
    {
        $heroes = Hero::with('languages', 'pinnedItems', 'pinnedItemsMeta', 'contributionsLastYear')
            ->latest()
            ->paginate(10);

        return Inertia::render('Dashboard', [
            'heroes' => $heroes
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'is_selected' => 'required|boolean',
            'login' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'bio_html' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'languages' => 'nullable|array',
            'primary_language' => 'nullable|array',
            'followers_count' => 'nullable|integer|min:0',
            'repositories_count' => 'nullable|integer|min:0',
            'avatar_url' => 'nullable|url|max:255',
            'website_url' => 'nullable|max:255',
            'notes' => 'nullable|string',
            'pinned_items' => 'nullable|array',
            'contributions_last_year' => 'nullable|array',
            'pinned_items_count' => 'nullable|integer|min:0',
            'pinned_items_stars_count' => 'nullable|integer|min:0',
        ]);

        // Begin transaction to ensure data integrity
        DB::beginTransaction();

        try {
            if ($validated['is_selected']) {
                $hero = Hero::updateOrCreate(['login' => $validated['login']], [
                    'name' => $validated['name'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'bio_html' => $validated['bio_html'] ?? null,
                    'location' => $validated['location'] ?? null,
                    'followers_count' => $validated['followers_count'] ?? null,
                    'repositories_count' => $validated['repositories_count'] ?? null,
                    'avatar_url' => $validated['avatar_url'] ?? null,
                    'website_url' => $validated['website_url'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);

                // Update or create ContributionsLastYear
                $hero->contributionsLastYear()->updateOrCreate([], [
                    'total_commit_contributions' => $validated['contributions_last_year']['totalCommitContributions'],
                    'total_issue_contributions' => $validated['contributions_last_year']['totalIssueContributions'],
                    'total_pull_request_contributions' => $validated['contributions_last_year']['totalPullRequestContributions'],
                    'total_pull_request_review_contributions' => $validated['contributions_last_year']['totalPullRequestReviewContributions'],
                    'total_repository_contributions' => $validated['contributions_last_year']['totalRepositoryContributions'],
                ]);

                // Update languages for the hero and set the primary language
                if (isset($validated['languages'])) {
                    // Sync all languages first without considering the 'is_primary' flag
                    $languageIds = collect($validated['languages'])->map(function ($language) {
                        $lang = Language::firstOrCreate([
                            'name' => $language['name'],
                        ], [
                            'color' => $language['color'],
                        ]);

                        return $lang->id;
                    })->all();

                    // Use sync method without 'is_primary' flag
                    $hero->languages()->sync($languageIds);

                    // Now set the 'is_primary' flag for the primary language
                    if (isset($validated['primary_language'])) {
                        $primaryLanguage = Language::where('name', $validated['primary_language']['name'])->first();

                        if ($primaryLanguage) {
                            DB::table('hero_language')
                                ->where('hero_id', $hero->id)
                                ->where('language_id', $primaryLanguage->id)
                                ->update(['is_primary' => true]);
                        }
                    }
                } else {
                    $hero->languages()->detach();
                }

                // Update PinnedItemsMeta
                $hero->pinnedItemsMeta()->updateOrCreate([], [
                    'count' => $validated['pinned_items_count'] ?? null,
                    'star_count' => $validated['pinned_items_stars_count'] ?? null,
                ]);

                // Handle PinnedItems, assuming we replace all existing ones
                $hero->pinnedItems()->delete();
                if (!empty($validated['pinned_items'])) {
                    foreach ($validated['pinned_items'] as $item) {
                        $hero->pinnedItems()->create($item);
                    }
                }
            } else {
                $hero = Hero::where('login', $validated['login'])->firstOrFail();
                $hero->delete();
            }

            DB::commit();
            return response()->json(['message' => 'Hero and related data updated successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred while updating the hero and related data.', 'exception' => $e->getMessage()], 500);
        }
    }

    public function destroy(Hero $hero, Request $request) {
        $hero->delete();

        return response()->json(['message' => 'Hero deleted successfully.']);
    }
}
