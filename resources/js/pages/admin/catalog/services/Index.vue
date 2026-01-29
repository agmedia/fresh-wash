<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

type Service = {
    id: number;
    code: string;
    name: string;
    description?: string | null;
    sort_order: number;
    is_active: boolean;
};

type Paginator<T> = {
    data: T[];
    links?: any[];
    meta?: any;
};

const props = defineProps<{
    services: Paginator<Service>;
    filters: { search?: string | null };
}>();

const search = ref(props.filters?.search ?? '');

const submitSearch = () => {
    router.get('/admin/catalog/services', { search: search.value }, { preserveState: true, replace: true });
};
</script>

<template>
    <AdminLayout title="Usluge">
        <div class="flex items-center justify-between gap-3">
            <form @submit.prevent="submitSearch" class="flex items-center gap-2">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Pretraži (code ili name)"
                    class="w-72 rounded border px-3 py-2 text-sm"
                />
                <button class="rounded border px-3 py-2 text-sm">Traži</button>
            </form>

            <Link href="/admin/catalog/services/create" class="rounded bg-blue-600 px-4 py-2 text-sm text-white">
                Nova usluga
            </Link>
        </div>

        <div class="mt-4 overflow-auto rounded border">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">Code</th>
                    <th class="px-3 py-2 text-left">Naziv</th>
                    <th class="px-3 py-2 text-left">Sort</th>
                    <th class="px-3 py-2 text-left">Aktivno</th>
                    <th class="px-3 py-2"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="s in services.data" :key="s.id" class="border-t">
                    <td class="px-3 py-2">{{ s.id }}</td>
                    <td class="px-3 py-2 font-mono">{{ s.code }}</td>
                    <td class="px-3 py-2">{{ s.name }}</td>
                    <td class="px-3 py-2">{{ s.sort_order }}</td>
                    <td class="px-3 py-2">{{ s.is_active ? 'da' : 'ne' }}</td>
                    <td class="px-3 py-2 text-right">
                        <div class="flex justify-end gap-3">
                            <Link :href="`/admin/catalog/services/${s.id}`" class="text-blue-600">Pregled</Link>
                            <Link :href="`/admin/catalog/services/${s.id}/edit`" class="text-blue-600">Uredi</Link>
                        </div>
                    </td>
                </tr>

                <tr v-if="!services.data.length">
                    <td class="px-3 py-6 text-center text-gray-500" colspan="6">Nema podataka.</td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>