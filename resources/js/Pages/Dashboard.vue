<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    links: Object,
    totalClicks: Number,
});

const longUrl = ref('');

const createShortLink = () => {
    if (!longUrl.value) return;
    
    router.post('/dashboard/links', { 
        long_url: longUrl.value 
    });
    
    longUrl.value = '';
};

const copyToken = (token) => {
    const fullUrl = `${window.location.origin}/r/${token}`;
    navigator.clipboard.writeText(fullUrl);
    alert(`Скопировано: ${fullUrl}`);
};

const getToken = (token) => {
    return `${window.location.origin}/r/${token}`;
};

const truncate = (str, maxLength) => {
    if (!str) return 'Нет ссылки';
    return str.length > maxLength ? str.substring(0, maxLength) + '…' : str;
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Мои короткие ссылки
                </h2>
                <div class="flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm text-gray-500">Всего кликов:</span>
                    <span class="font-bold text-indigo-600 text-lg">
                        {{ totalClicks ?? 0 }}
                    </span>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
                <form @submit.prevent="createShortLink" class="flex flex-col sm:flex-row gap-2">
                    <input 
                        v-model="longUrl" 
                        type="url" 
                        required 
                        placeholder="https://example.com/very-long-link" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                    />
                    <button 
                        type="submit" 
                        :disabled="!longUrl"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                    >
                        Сократить ссылку
                    </button>
                </form>
            </div>

            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="links && links.data && links.data.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Целевая ссылка
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Короткая ссылка
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Кликов
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Действия
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="link in links.data" :key="link.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                            <a :href="link.long_url" target="_blank" class="hover:underline">
                                                {{ truncate(link.long_url, 40) }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                            <a :href="getToken(link.token)" target="_blank" class="hover:underline">
                                                {{ getToken(link.token) }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                            {{ link.clicks_count || 0 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="copyToken(link.token)" type="button"
                                                class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                                Копировать
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-4 text-sm text-gray-500">
                                Показываем {{ links.from }}–{{ links.to }} из {{ links.total }} записей.
                            </div>
                        </div>

                        <div v-else class="text-center py-12">
                            <p class="text-gray-500">У вас пока нет созданных коротких ссылок.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
