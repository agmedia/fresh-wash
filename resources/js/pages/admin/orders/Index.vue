<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

type Order = {
    id: number;
    code: string;
    status: 'pending' | 'paid' | 'cancelled' | 'refunded';
    currency: string;
    total_gross: string | number;
    user?: {
        email: string;
        first_name?: string | null;
        last_name?: string | null;
        name?: string | null;
    } | null;
    location?: { name: string } | null;
};

type Paginator<T> = { data: T[]; meta?: any; links?: any[] };

const props = defineProps<{
    orders: Paginator<Order>;
    filters: { search?: string | null };
}>();

const search = ref(props.filters?.search ?? '');

const submitSearch = () => {
    router.get(
        '/admin/orders',
        { search: search.value },
        { preserveState: true, replace: true },
    );
};

const statusLabel = (s: Order['status']) => {
    if (s === 'paid') return 'Plaćeno';
    if (s === 'pending') return 'Na čekanju';
    if (s === 'cancelled') return 'Otkazano';
    if (s === 'refunded') return 'Refundirano';
    return s;
};

const statusClass = (s: Order['status']) => {
    switch (s) {
        case 'paid':
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        case 'pending':
            return 'bg-amber-50 text-amber-800 border-amber-200';
        case 'cancelled':
            return 'bg-rose-50 text-rose-700 border-rose-200';
        case 'refunded':
            return 'bg-slate-50 text-slate-700 border-slate-200';
        default:
            return 'bg-slate-50 text-slate-700 border-slate-200';
    }
};

const userName = (o: Order) => {
    const u = o.user;
    if (!u) return '-';
    const full = [u.first_name, u.last_name].filter(Boolean).join(' ').trim();
    return full || u.name || u.email;
};

const totalCount = computed(
    () => props.orders?.meta?.total ?? props.orders?.data?.length ?? 0,
);
</script>

<template>
    <AdminLayout title="Narudžbe">
        <div
            class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between"
        >
            <div class="rounded border border-gray-800 bg-gray-900 p-3 text-sm text-gray-200">
                <div class="text-gray-400">Ukupno</div>
                <div class="text-lg font-semibold">{{ totalCount }}</div>
            </div>

            <form
                @submit.prevent="submitSearch"
                class="flex items-center gap-2"
            >
                <input
                    v-model="search"
                    type="text"
                    placeholder="Pretraži (code, status, user, lokacija)"
                    class="w-80 rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600/40"
                />
                <button
                    class="rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200 hover:bg-gray-800"
                >
                    Traži
                </button>
            </form>
        </div>

        <div class="mt-4 overflow-auto rounded border border-gray-800 bg-gray-900">
            <table class="min-w-full text-sm text-gray-200">
                <thead class="bg-gray-800 text-gray-200">
                    <tr>
                        <th class="px-3 py-2 text-left">Code</th>
                        <th class="px-3 py-2 text-left">Korisnik</th>
                        <th class="px-3 py-2 text-left">Lokacija</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-right">Ukupno</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="o in orders.data"
                        :key="o.id"
                        class="border-t border-gray-800 hover:bg-gray-800/60"
                    >
                        <td class="px-3 py-2">
                            <div class="font-medium">{{ o.code }}</div>
                            <div class="text-xs text-gray-400">#{{ o.id }}</div>
                        </td>
                        <td class="px-3 py-2">
                            <div class="font-medium">{{ userName(o) }}</div>
                            <div class="text-xs text-gray-400">
                                {{ o.user?.email ?? '-' }}
                            </div>
                        </td>
                        <td class="px-3 py-2">
                            {{ o.location?.name ?? '-' }}
                        </td>
                        <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-1 text-xs"
                                :class="statusClass(o.status)"
                            >
                                {{ statusLabel(o.status) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-right font-medium">
                            {{ o.total_gross }} {{ o.currency }}
                        </td>
                        <td class="px-3 py-2 text-right">
                            <div class="flex justify-end gap-3">
                                <Link
                                    :href="`/admin/orders/${o.id}`"
                                    class="text-blue-400 hover:underline"
                                    >Pregled</Link
                                >
                                <Link
                                    :href="`/admin/orders/${o.id}/edit`"
                                    class="text-blue-400 hover:underline"
                                    >Uredi</Link
                                >
                            </div>
                        </td>
                    </tr>

                    <tr v-if="!orders.data.length">
                        <td
                            colspan="6"
                            class="px-3 py-10 text-center text-gray-400"
                        >
                            Nema narudžbi.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
