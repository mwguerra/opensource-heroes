<script setup>
const props = defineProps({
    message: {
        type: String,
        default: 'Loading...'
    }
})
</script>

<template>
    <div class="relative">
        <div class="bg-transparent dark:bg-zinc-600 p-4 ring-1 ring-zinc-900/5 rounded-md w-full border-gray-200 border-solid border">
            <div class="flex items-center space-x-4 animate-pulse">
                <div class="rounded-full bg-zinc-200 dark:bg-zinc-700 w-16 h-16 aspect-square flex-shrink-0"></div>
                <div class="flex-1 space-y-6 py-1">
                    <div class="h-2 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                    <div class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="h-2 bg-zinc-200 dark:bg-zinc-700 rounded col-span-2"></div>
                            <div class="h-2 bg-zinc-200 dark:bg-zinc-700 rounded col-span-1"></div>
                        </div>
                        <div class="h-2 bg-zinc-200 dark:bg-zinc-700 rounded"></div>
                    </div>
                </div>
                <div class="flex flex-shrink-0 flex-grow-0 justify-end text-neutral-800">
                    <!-- Circle Only -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-6 h-6 stroke-zinc-200 dark:stroke-zinc-700 fill-zinc-200 dark:fill-zinc-700">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="absolute flex items-center justify-center w-full h-full top-0 left-0 font-semibold">
            <p class="text-xl">{{ props.message }}</p>
        </div>
    </div>
</template>
