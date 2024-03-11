<script setup>
import { ArrowUpIcon, EyeIcon } from '@heroicons/vue/20/solid'
import {computed} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    selectedUsers: {
        type: Set,
        required: true
    }
})

const selectedUsersCount = computed(() => props.selectedUsers.size)

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const goToAnalysisPage = () => {
    router.visit(route('heroes.index'))
}
</script>

<template>
    <div class="fixed bottom-0 left-0 w-full bg-white/60 backdrop-blur-md z-50 shadow-2xl shadow-black py-6 px-6">
        <div class="mx-auto max-w-7xl flex justify-end gap-8">
            <button
                type="button"
                class="inline-flex items-center gap-x-2 rounded-full px-3.5 py-2.5 text-sm font-semibold shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 "
                :class="[
                    selectedUsersCount > 0
                        ? 'bg-indigo-600 hover:bg-indigo-500 focus-visible:outline-indigo-600 text-white'
                        : 'bg-gray-300 text-gray-500'
                ]"
                @click="goToAnalysisPage"
            >
                <EyeIcon class="-ml-0.5 h-5 w-5" aria-hidden="true" />
                <span v-if="selectedUsersCount > 0">Analyze {{ selectedUsersCount }} Developers</span>
                <span v-else>Select Developers<span class="hidden sm:inline"> for Comparison</span></span>
            </button>

            <button
                type="button"
                class="inline-flex items-center gap-x-2 rounded-full bg-gray-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600"
                @click="scrollToTop"
            >
                <ArrowUpIcon class="-ml-0.5 h-5 w-5" aria-hidden="true" />
                <span class="hidden sm:inline">Back to top</span>
            </button>
        </div>
    </div>
</template>
