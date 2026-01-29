<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    pages: any;
    filters: { search?: string | null; type?: string | null };
}>();

const search = ref(props.filters?.search ?? '');
const type = ref(props.filters?.type ?? '');

const apply = () => {
    router.get(
        '/admin/pages',
        { search: search.value || null, type: type.value || null },
        { preserveState: true, replace: true },
    );
};
</script>

<template>
    <AdminLayout title="Stranice / Blog">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <input
                    v-model="search"
                    @keyup.enter="apply"
                    class="w-72 rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    placeholder="Search (title/slug)..."
                />

                <select
                    v-model="type"
                    @change="apply"
                    class="rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                >
                    <option value="">Sve</option>
                    <option value="page">Stranice</option>
                    <option value="post">Blog</option>
                </select>

                <button
                    type="button"
                    class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-gray-700"
                    @click="apply"
                >
                    Apply
                </button>
            </div>

            <Link href="/admin/pages/create" class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-500">
                + Nova
            </Link>
        </div>

        <div class="mt-4 overflow-hidden rounded border border-gray-800">
            <table class="min-w-full bg-gray-900 text-sm text-gray-200">
                <thead class="bg-gray-800 text-gray-300">
                    <tr>
                        <th class="px-3 py-2 text-left">Naslov</th>
                        <th class="px-3 py-2 text-left">Type</th>
                        <th class="px-3 py-2 text-left">Kategorija</th>
                        <th class="px-3 py-2 text-left">Aktivna</th>
                        <th class="px-3 py-2 text-left">Published</th>
                        <th class="px-3 py-2 text-right">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="p in props.pages.data" :key="p.id" class="border-t border-gray-800">
                        <td class="px-3 py-2">
                            <div class="font-medium">{{ p.title }}</div>
                            <div class="text-xs text-gray-400">{{ p.slug }}</div>
                        </td>
                        <td class="px-3 py-2">{{ p.type }}</td>
                        <td class="px-3 py-2">{{ p.category?.name ?? '—' }}</td>
                        <td class="px-3 py-2">
                            <span
                                class="rounded px-2 py-0.5 text-xs"
                                :class="p.is_active ? 'bg-green-900 text-green-200' : 'bg-gray-800 text-gray-300'"
                            >
                                {{ p.is_active ? 'da' : 'ne' }}
                            </span>
                        </td>
                        <td class="px-3 py-2">{{ p.published_at ?? '—' }}</td>
                        <td class="px-3 py-2 text-right">
                            <div class="inline-flex gap-2">
                                <Link :href="`/admin/pages/${p.id}`" class="text-blue-400 hover:underline">Show</Link>
                                <Link :href="`/admin/pages/${p.id}/edit`" class="text-blue-400 hover:underline">Edit</Link>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="!props.pages.data.length">
                        <td colspan="6" class="px-3 py-6 text-center text-gray-400">Nema zapisa.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
