<script setup>
import { ArrowLongLeftIcon, ArrowLongRightIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    pageInfo: {
        type: Object,
        required: true
    },
})
const emit = defineEmits(['nextPageRequest', 'previousPageRequest'])

const nextPage = () => {
    emit('nextPageRequest')
}

const previousPage = () => {
    emit('previousPageRequest')
}
</script>

<template>
    <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
        <div class="-mt-px flex w-0 flex-1" :class="{'hidden': !props.pageInfo.hasPreviousPage}">
            <a href="#" class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700" @click.prevent="previousPage">
                <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
                Previous
            </a>
        </div>

        <div class="-mt-px flex w-0 flex-1 justify-end" :class="{'hidden': !props.pageInfo.hasNextPage}">
            <a href="#" class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700" @click.prevent="nextPage">
                Next
                <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
            </a>
        </div>
    </nav>
</template>
