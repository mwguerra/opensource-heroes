<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {router} from "@inertiajs/vue3";
import DatabaseUserCard from "@/Components/DatabaseUserCard.vue";

const props = defineProps({
    heroes: {
        type: Object,
        required: true
    }
})

const goToHeroSearch = () => {
    router.visit(route('search'));
}

const deleteHero = async (hero) => {
    if (confirm('Are you sure you want to delete this hero?')) {
        await axios.delete(route('heroes.destroy', {hero: hero.id}));
        await router.reload({
            preserveScroll: true,
            preserveState: true
        });
    }
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Developer Analysis Dashboard
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Here you have all developers you already selected for screening.</p>
                </div>
                <button
                    type="button"
                    class="ml-3 inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    @click="goToHeroSearch"
                >Search More Developers</button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <h2 class="text-xl font-extrabold tracking-tight text-gray-900">Selected until now.</h2>
                <div class="flex flex-col flex-1 gap-4 mt-6 mb-8">
                    <DatabaseUserCard
                        v-for="hero in heroes.data"
                        :key="hero.id"
                        :user="hero"
                        @delete-user="deleteHero(hero)"
                    />
                </div>

            </div>
        </div>
    </AppLayout>
</template>
