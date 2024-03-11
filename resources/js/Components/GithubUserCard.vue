<script setup>
    import {computed} from "vue";

    const props = defineProps({
        user: Object,
        selected: {
            type: Boolean,
            default: true
        }
    });

    const emit = defineEmits(['update:selected']);

    const languages = computed(() => (props.user?.pinned_languages?.length ?? []) > 0
        ? (props.user?.pinned_languages ?? [])
        : (props.user?.top_repositories_languages ?? [])
    )
    const primaryLanguage = computed(() => props.user.pinned_primary_language ?? props.user.top_repositories_primary_language)
</script>

<template>
    <div
        class="flex p-4 w-full leading-5 break-words rounded-md border border-solid text-neutral-800 justify-between"
        :class="props.selected ? 'border-blue-700 bg-white' : 'border-gray-300'"
    >
        <!-- Avatar -->
        <div class="flex-shrink-0 flex items-center">
            <img
                :alt="`${props.user.name} avatar photo`"
                :src="props.user.avatar_url"
                class="inline-block overflow-hidden mt-1 mr-4 w-16 h-16 leading-none align-middle border-none rounded-full"
            />
        </div>
        <!-- User info -->
        <div class="flex flex-col flex-1 border-gray-300 line-clamp-4">
            <!-- User name -->
            <h3 class="flex flex-col my-0 text-base font-medium leading-6 decoration-0">
                <span class="flex items-center truncate">
                    <a
                        :href="props.user.github_url"
                        class="text-blue-600 whitespace-nowrap bg-transparent cursor-pointer decoration-0"
                    >
                        <span>{{ props.user.name }}</span>
                    </a>
                    <a
                        :href="props.user.github_url"
                        class="text-blue-600 whitespace-nowrap bg-transparent cursor-pointer decoration-0"
                    >
                        <span class="ml-1 font-light text-gray-500">
                            {{ props.user.login }}
                        </span>
                    </a>
                </span>
            </h3>
            <!-- Biography -->
            <div class="mt-1 text-sm">
                <span v-html="props.user.bio_html" />
            </div>
            <!-- Metadata -->
            <ul class="flex flex-wrap items-center pl-0 mt-1 mb-0">
                <li class="flex items-center text-xs leading-4 text-left text-gray-500 list-none">
                    {{ props.user.location }}
                </li>
                <span aria-hidden="true" class="mx-2 text-gray-500">·</span>
                <li class="flex items-center text-xs leading-4 text-left text-gray-500 list-none">
                    <svg
                        aria-hidden="true"
                        focusable="false"
                        role="img"
                        class="inline-block overflow-visible mr-1 align-text-bottom select-none"
                        viewBox="0 0 16 16"
                        width="16"
                        height="16"
                        fill="currentColor"
                        style="display: inline-block; user-select: none; vertical-align: text-bottom; overflow: visible; list-style: outside none none;"
                    >
                        <path
                            d="M2 2.5A2.5 2.5 0 0 1 4.5 0h8.75a.75.75 0 0 1 .75.75v12.5a.75.75 0 0 1-.75.75h-2.5a.75.75 0 0 1 0-1.5h1.75v-2h-8a1 1 0 0 0-.714 1.7.75.75 0 1 1-1.072 1.05A2.495 2.495 0 0 1 2 11.5Zm10.5-1h-8a1 1 0 0 0-1 1v6.708A2.486 2.486 0 0 1 4.5 9h8ZM5 12.25a.25.25 0 0 1 .25-.25h3.5a.25.25 0 0 1 .25.25v3.25a.25.25 0 0 1-.4.2l-1.45-1.087a.249.249 0 0 0-.3 0L5.4 15.7a.25.25 0 0 1-.4-.2Z"
                            class=""
                            style="list-style: outside none none;"
                        ></path>
                    </svg>
                    <span :aria-label="`${props.user.repositories_count} repositories`">
                        {{ props.user.repositories_count }}
                    </span>
                </li>
                <span aria-hidden="true" class="mx-2 text-gray-500">·</span>
                <li class="flex items-center text-xs leading-4 text-left text-gray-500 list-none">
                    <svg
                        aria-hidden="true"
                        focusable="false"
                        role="img"
                        class="inline-block overflow-visible mr-1 align-text-bottom select-none"
                        viewBox="0 0 16 16"
                        width="16"
                        height="16"
                        fill="currentColor"
                        style="display: inline-block; user-select: none; vertical-align: text-bottom; overflow: visible; list-style: outside none none;"
                    >
                        <path
                            d="M2 5.5a3.5 3.5 0 1 1 5.898 2.549 5.508 5.508 0 0 1 3.034 4.084.75.75 0 1 1-1.482.235 4 4 0 0 0-7.9 0 .75.75 0 0 1-1.482-.236A5.507 5.507 0 0 1 3.102 8.05 3.493 3.493 0 0 1 2 5.5ZM11 4a3.001 3.001 0 0 1 2.22 5.018 5.01 5.01 0 0 1 2.56 3.012.749.749 0 0 1-.885.954.752.752 0 0 1-.549-.514 3.507 3.507 0 0 0-2.522-2.372.75.75 0 0 1-.574-.73v-.352a.75.75 0 0 1 .416-.672A1.5 1.5 0 0 0 11 5.5.75.75 0 0 1 11 4Zm-5.5-.5a2 2 0 1 0-.001 3.999A2 2 0 0 0 5.5 3.5Z"
                            class=""
                            style="list-style: outside none none;"
                        ></path>
                    </svg>
                    <span :aria-label="`${props.user.followers_count} followers`">
                        {{ props.user.followers_count }}
                    </span>
                </li>
            </ul>
            <!-- Languages -->
            <ul class="flex flex-wrap items-center pl-0 mt-4 mb-0 gap-2 font-semibold">
                <li
                    v-for="language in languages"
                    :key="language.name"
                    class="flex items-center text-xs leading-4 text-left text-gray-900 list-none px-2 py-1 rounded-full border"
                    :style="{ borderColor: language.color, backgroundColor: language.color + '50' }"
                >
                    {{ language.name }} <span v-if="language.name === primaryLanguage?.name" class="ml-1">⭐</span>
                </li>
            </ul>
        </div>
        <!-- Select checkbox -->
        <div
            class="flex flex-grow-0 flex-shrink-0 text-neutral-800"
            @click="emit('update:selected', { languages, primaryLanguage })"
        >
            <button type="button">
                <!-- Check Circle -->
                <svg v-if="props.selected" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-8 h-8 stroke-blue-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                <!-- Circle Only -->
                <svg v-if="!props.selected" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" class="w-8 h-8 stroke-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        </div>
    </div>
</template>
