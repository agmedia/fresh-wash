<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    categories: any;
    filters: { search?: string | null };
}>();

const search = ref(props.filters?.search ?? '');

const submitSearch = () => {
    router.get('/admin/page-categories', { search: search.value }, { preserveState: true, replace: true });
};
</script>

<template>
    <AdminLayout title="Kategorije stranica">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <input
                    v-model="search"
                    @keyup.enter="submitSearch"
                    class="w-72 rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    placeholder="Pretraži..."
                />
                <button
                    type="button"
                    class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-gray-700"
                    @click="submitSearch"
                >
                    Traži
                </button>
            </div>

            <Link
                href="/admin/page-categories/create"
                class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-500"
            >
                + Nova kategorija
            </Link>
        </div>

        <div class="mt-4 overflow-hidden rounded border border-gray-800">
            <table class="min-w-full bg-gray-900 text-sm text-gray-200">
                <thead class="bg-gray-800 text-gray-300">
                    <tr>
                        <th class="px-3 py-2 text-left">Naziv</th>
                        <th class="px-3 py-2 text-left">Slug</th>
                        <th class="px-3 py-2 text-left">Aktivna</th>
                        <th class="px-3 py-2 text-right">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="c in props.categories.data" :key="c.id" class="border-t border-gray-800">
                        <td class="px-3 py-2 font-medium">{{ c.name }}</td>
                        <td class="px-3 py-2">{{ c.slug }}</td>
                        <td class="px-3 py-2">
                            <span
                                class="rounded px-2 py-0.5 text-xs"
                                :class="c.is_active ? 'bg-green-900 text-green-200' : 'bg-gray-800 text-gray-300'"
                            >
                                {{ c.is_active ? 'da' : 'ne' }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-right">
                            <Link :href="`/admin/page-categories/${c.id}/edit`" class="text-blue-400 hover:underline">Uredi</Link>
                        </td>
                    </tr>

                    <tr v-if="!props.categories.data.length">
                        <td colspan="4" class="px-3 py-6 text-center text-gray-400">Nema zapisa.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
