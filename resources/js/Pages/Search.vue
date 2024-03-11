<script setup>
import {onMounted, onUnmounted, ref, watch} from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import GithubUserCard from "@/Components/GithubUserCard.vue";
import TagInput from "@/Components/TagInput.vue";
import {router} from "@inertiajs/vue3";
import LoadingUserCard from "@/Components/LoadingUserCard.vue";
import SearchFixedBottomNavigationBar from "@/Components/SearchFixedBottomNavigationBar.vue";
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import { ChevronUpIcon, ChevronDownIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    results: Object,
    filters: Object,
    pageInfo: Object
});

const location = ref(props.filters.location ?? "");
const languages = ref(props.filters.languages ?? []);
const followers = ref(props.filters.followers ?? []);
const repositories = ref(props.filters.repositories ?? []);
const users = ref(props.results.users);
const pageInfo = ref(props.pageInfo);
const isFetchingMoreUsers = ref(false);
const isFetchingUsersByFilter = ref(false);
const selectedUsers = ref(new Set());

const setFilters = () => {
    if (isFetchingUsersByFilter.value) {
        return;
    }

    isFetchingUsersByFilter.value = true;

    router.visit(route('search'),  {
        data: {
            location: location.value,
            languages: languages.value.join(","),
            followers: followers.value,
            repositories: repositories.value
        }
    }, {
        onFinish: visit => {
            isFetchingUsersByFilter.value = false;
        }
    })
}

const fetchMoreUsers = async () => {
    if (isFetchingUsersByFilter.value) {
        return;
    }

    if (!pageInfo.value?.hasNextPage) return;

    isFetchingMoreUsers.value = true;

    try {
        const response = await axios.post(route('get-more-users'), {
            after: pageInfo.value.endCursor,
            location: props.filters.location,
            languages: (props.filters.languages ?? []).join(',') ?? null,
            followers: props.filters.followers ?? '>0',
            repositories: props.filters.repositories ?? '>0'
        })

        users.value = [...users.value, ...response.data.results.users];
        pageInfo.value = response.data.pageInfo;
    } catch (e) {
        console.log(e);
    } finally {
        isFetchingMoreUsers.value = false;
    }
};

const updateSelectedUsers = async (user, newIsSelectedStatus, languages) => {
    // Construct the payload with correct column names
    const payload = {
        login: user.login,
        name: user.name,
        email: user.email,
        bio_html: user.bio_html,
        location: user.location,
        languages: languages.languages,
        primary_language: languages.primary_language,
        pinned_languages: user.pinned_languages,
        pinned_primary_language: user.pinned_primary_language,
        top_repositories_languages: user.top_repositories_languages,
        top_repositories_primary_language: user.top_repositories_primary_language,
        followers_count: user.followers_count,
        repositories_count: user.repositories_count,
        avatar_url: user.avatar_url,
        website_url: user.website_url,
        pinned_items: user.pinned_items.map(item => ({
            type: item.__typename,
            name: item.name,
            description: item.description,
            stargazersTotalCount: item.stargazers.totalCount
        })),
        pinned_items_count: user.pinned_items_count,
        pinned_items_stars_count: user.pinned_items_stars_count,
        contributions_last_year: { ...user.contributions_last_year },
        repositories_contributed_count: user.repositories_contributed_count,
        // notes: user.notes,
        is_selected: newIsSelectedStatus
    };

    console.log(payload);

    try {
        await axios.post('/heroes/selection', payload);

        if (newIsSelectedStatus) {
            selectedUsers.value.add(user.login);
        } else {
            selectedUsers.value.delete(user.login);
        }

        // Must assign a new Set to trigger reactivity
        selectedUsers.value = new Set(selectedUsers.value);
    } catch (error) {
        console.error('Failed to update hero selection', error);
    }
};

const checkScroll = () => {
    if (isFetchingMoreUsers.value) return;

    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
        fetchMoreUsers();
    }
};

watch(() => props.results.users, (newUsers) => {
    users.value = newUsers;
});

onMounted(() => {
    window.addEventListener('scroll', checkScroll);

    console.log(props);
});

onUnmounted(() => {
    window.removeEventListener('scroll', checkScroll);
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Open Source Hero Search
            </h2>
        </template>

        <div class="pt-12 pb-28 px-2 sm:px-0">
            <!-- Top status message -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pb-4 hidden sm:block">
                <div class="sm:flex gap-8">
                    <!-- Filters Header -->
                    <div class="sm:w-64" />

                    <!-- Results Header -->
                    <div class="flex-1">
                        <p>{{ props.results.userCount }} Heroes Found.</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="sm:flex gap-8">
                    <!-- Filters -->
                    <div class="flex flex-col gap-4 sm:w-64">
                        <div>
                            <label for="location-input" class="block text-sm font-medium leading-6 text-gray-900">Location</label>
                            <div class="mt-2">
                                <input type="text" name="location-input" id="location-input" v-model="location"  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 sm:text-sm sm:leading-6" placeholder="Example: Brazil" />
                            </div>
                        </div>

                        <div>
                            <label for="languages-input" class="block text-sm font-medium leading-6 text-gray-900 mb-2">Languages</label>
                            <TagInput v-model="languages" placeholder="Example: php" />
                        </div>

                        <Disclosure as="div" v-slot="{ open }">
                            <div>
                                <DisclosureButton class="flex w-full items-start justify-between text-sm text-left font-medium leading-6 text-gray-900">
                                    <span>Advanced Search</span>
                                    <span class="ml-6 flex h-7 items-center">
                                      <ChevronDownIcon v-if="!open" class="h-4 w-4" aria-hidden="true" />
                                      <ChevronUpIcon v-else class="h-4 w-4" aria-hidden="true" />
                                    </span>
                                </DisclosureButton>
                            </div>
                            <DisclosurePanel as="div" class="mt-2 p-4 border border-gray-400 rounded-md flex flex-col gap-4">
                                <div class="inline-flex items-center gap-4">
                                    <label for="location-input" class="block text-sm font-medium leading-6 text-gray-900">Followers</label>
                                    <input type="text" name="location-input" id="location-input" v-model="followers"  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 sm:text-sm sm:leading-6" placeholder=">50 (default)" />
                                </div>
                                <div class="inline-flex items-center gap-4">
                                    <label for="location-input" class="block text-sm font-medium leading-6 text-gray-900">Repositories</label>
                                    <input type="text" name="location-input" id="location-input" v-model="repositories"  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 sm:text-sm sm:leading-6" placeholder=">10 (default)" />
                                </div>
                            </DisclosurePanel>
                        </Disclosure>

                        <button
                            class="flex items-center justify-center px-4 py-2 border border-blue-700 text-blue-700 hover:bg-blue-700 hover:text-white font-semibold rounded-md shadow-sm text-sm mt-2 transition ease-in-out duration-150"
                            :class="[ isFetchingUsersByFilter ? 'opacity-85 bg-blue-700 text-white cursor-not-allowed' : 'cursor-pointer']"
                            @click="setFilters"
                            :disabled="isFetchingUsersByFilter"
                        >
                            <svg v-if="isFetchingUsersByFilter" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ isFetchingUsersByFilter ? 'Searching...' : 'Search' }}
                        </button>
                    </div>

                    <!-- Results -->
                    <div class="flex flex-col flex-1 gap-4 my-8 sm:my-0">
                        <p class="sm:hidden">{{ props.results.userCount }} Heroes Found.</p>

                        <GithubUserCard
                            v-show="!isFetchingUsersByFilter"
                            v-for="user in users"
                            :key="user.login"
                            :user="user"
                            :selected="selectedUsers.has(user.login)"
                            @update:selected="updateSelectedUsers(user, !selectedUsers.has(user.login), $event)"
                        />

                        <LoadingUserCard
                            v-if="isFetchingMoreUsers || isFetchingUsersByFilter"
                            :message="isFetchingMoreUsers ? 'Loading more users...' : 'Loading users...'"
                        />
                    </div>
                </div>

            </div>
        </div>

        <SearchFixedBottomNavigationBar :selected-users="selectedUsers"/>
    </AppLayout>
</template>
