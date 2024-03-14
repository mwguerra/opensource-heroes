<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ArrowUpIcon } from "@heroicons/vue/24/solid";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />

    <div class="relative flex flex-col min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div v-if="canLogin" class="p-6 text-end flex justify-end">
            <Link v-if="$page.props.auth.user" :href="route('heroes.index')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</Link>

            <template v-else>
                <nav class="flex justify-between px-4 py-3 space-x-4">
                    <Link :href="route('login')" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</Link>

                    <div class="relative">
                        <Link v-if="canRegister" :href="route('register')" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-200 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</Link>
                        <div class="absolute right-0 top-full flex flex-col items-end p-2 gap-1">
                            <ArrowUpIcon class="h-5 w-5 fill-blue-700 dark:fill-blue-400" aria-hidden="true" />
                            <span class="w-64 text-indigo-700 dark:text-blue-400 font-semibold leading-5">Click "Register" to create your account and try the app.</span>
                        </div>
                    </div>

                </nav>

            </template>
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8 flex-1 flex flex-col gap-2 justify-center items-center">
            <h1 class="text-2xl dark:text-gray-200">Open Source Heroes</h1>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl text-center">
                Open Source Heroes is an application that allows you to search for open source developers, also known as Open Source Heroes.
            </p>
        </div>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
