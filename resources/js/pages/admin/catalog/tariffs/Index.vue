<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

type Tariff = {
    id: number;
    price_net: string | number;
    vat_rate: string | number;
    price_gross: string | number;
    currency: string;
    is_active: boolean;
    service?: { id: number; name: string; code: string };
    location?: { id: number; name: string };
};

type Paginator<T> = { data: T[]; links?: any[]; meta?: any };

const props = defineProps<{
    tariffs: Paginator<Tariff>;
    filters: { search?: string | null };
}>();

const search = ref(props.filters?.search ?? '');

const submitSearch = () => {
    router.get(
        '/admin/catalog/tariffs',
        { search: search.value },
        { preserveState: true, replace: true },
    );
};
</script>

<template>
    <AdminLayout title="Tarife">
        <div class="flex items-center justify-between gap-3">
            <form
                @submit.prevent="submitSearch"
                class="flex items-center gap-2"
            >
                <input
                    v-model="search"
                    type="text"
                    placeholder="Pretraži (lokacija/usluga)"
                    class="w-72 rounded border px-3 py-2 text-sm"
                />
                <button class="rounded border px-3 py-2 text-sm">Traži</button>
            </form>

            <Link
                href="/admin/catalog/tariffs/create"
                class="rounded bg-blue-600 px-4 py-2 text-sm text-white"
            >
                Nova tarifa
            </Link>
        </div>

        <div class="mt-4 overflow-auto rounded border">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left">ID</th>
                        <th class="px-3 py-2 text-left">Lokacija</th>
                        <th class="px-3 py-2 text-left">Usluga</th>
                        <th class="px-3 py-2 text-left">Net</th>
                        <th class="px-3 py-2 text-left">PDV</th>
                        <th class="px-3 py-2 text-left">Bruto</th>
                        <th class="px-3 py-2 text-left">Aktivno</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="t in tariffs.data" :key="t.id" class="border-t">
                        <td class="px-3 py-2">{{ t.id }}</td>
                        <td class="px-3 py-2">{{ t.location?.name ?? '-' }}</td>
                        <td class="px-3 py-2">{{ t.service?.name ?? '-' }}</td>
                        <td class="px-3 py-2">
                            {{ t.price_net }} {{ t.currency }}
                        </td>
                        <td class="px-3 py-2">{{ t.vat_rate }}%</td>
                        <td class="px-3 py-2">
                            {{ t.price_gross }} {{ t.currency }}
                        </td>
                        <td class="px-3 py-2">
                            {{ t.is_active ? 'da' : 'ne' }}
                        </td>
                        <td class="px-3 py-2 text-right">
                            <div class="flex justify-end gap-3">
                                <Link
                                    :href="`/admin/catalog/tariffs/${t.id}`"
                                    class="text-blue-600"
                                    >Pregled</Link
                                >
                                <Link
                                    :href="`/admin/catalog/tariffs/${t.id}/edit`"
                                    class="text-blue-600"
                                    >Uredi</Link
                                >
                            </div>
                        </td>
                    </tr>

                    <tr v-if="!tariffs.data.length">
                        <td
                            class="px-3 py-6 text-center text-gray-500"
                            colspan="8"
                        >
                            Nema podataka.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
