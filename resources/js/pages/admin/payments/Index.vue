<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

type Payment = {
    id: number;
    provider: string;
    status: 'pending' | 'succeeded' | 'failed' | 'refunded' | string;
    amount: string | number;
    currency: string;
    provider_ref?: string | null;
    paid_at?: string | null;
    order?: { id: number; code: string; user?: { email: string } | null } | null;
};

type Paginator<T> = { data: T[]; meta?: any; links?: any[] };

const props = defineProps<{
    payments: Paginator<Payment>;
    filters: { search?: string | null };
}>();

const search = ref(props.filters?.search ?? '');

const submitSearch = () => {
    router.get('/admin/payments', { search: search.value }, { preserveState: true, replace: true });
};

const statusLabel = (s: string) => {
    if (s === 'succeeded') return 'Uspješno';
    if (s === 'pending') return 'Na čekanju';
    if (s === 'failed') return 'Neuspješno';
    if (s === 'refunded') return 'Refundirano';
    return s;
};

const statusClass = (s: string) => {
    if (s === 'succeeded') return 'bg-emerald-950/40 text-emerald-300 border-emerald-900/60';
    if (s === 'pending') return 'bg-amber-950/40 text-amber-300 border-amber-900/60';
    if (s === 'failed') return 'bg-rose-950/40 text-rose-300 border-rose-900/60';
    if (s === 'refunded') return 'bg-slate-900 text-slate-300 border-slate-800';
    return 'bg-slate-900 text-slate-300 border-slate-800';
};

const totalCount = computed(() => props.payments?.meta?.total ?? props.payments?.data?.length ?? 0);
</script>

<template>
    <AdminLayout title="Plaćanja">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="rounded border border-gray-800 bg-gray-900 p-3 text-sm text-gray-200">
                <div class="text-gray-400">Ukupno</div>
                <div class="text-lg font-semibold">{{ totalCount }}</div>
            </div>

            <form @submit.prevent="submitSearch" class="flex items-center gap-2">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Pretraži (order, email, provider, ref)"
                    class="w-80 rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600/40"
                />
                <button class="rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200 hover:bg-gray-800">
                    Traži
                </button>
            </form>
        </div>

        <div class="mt-4 overflow-auto rounded border border-gray-800 bg-gray-900">
            <table class="min-w-full text-sm text-gray-200">
                <thead class="bg-gray-800 text-gray-200">
                    <tr>
                        <th class="px-3 py-2 text-left">ID</th>
                        <th class="px-3 py-2 text-left">Order</th>
                        <th class="px-3 py-2 text-left">Provider</th>
                        <th class="px-3 py-2 text-left">Status</th>
                        <th class="px-3 py-2 text-right">Iznos</th>
                        <th class="px-3 py-2 text-left">Paid at</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="p in payments.data" :key="p.id" class="border-t border-gray-800 hover:bg-gray-800/60">
                        <td class="px-3 py-2 font-medium">#{{ p.id }}</td>

                        <td class="px-3 py-2">
                            <div class="font-medium">
                                <Link
                                    v-if="p.order"
                                    :href="`/admin/orders/${p.order.id}`"
                                    class="text-blue-400 hover:underline"
                                >
                                    {{ p.order.code }}
                                </Link>
                                <span v-else>-</span>
                            </div>
                            <div class="text-xs text-gray-400">{{ p.order?.user?.email ?? '-' }}</div>
                        </td>

                        <td class="px-3 py-2">
                            <div class="font-medium">{{ p.provider }}</div>
                            <div class="text-xs text-gray-400">{{ p.provider_ref ?? '-' }}</div>
                        </td>

                        <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-1 text-xs"
                                :class="statusClass(p.status)"
                            >
                                {{ statusLabel(p.status) }}
                            </span>
                        </td>

                        <td class="px-3 py-2 text-right font-medium">{{ p.amount }} {{ p.currency }}</td>
                        <td class="px-3 py-2">{{ p.paid_at ?? '-' }}</td>

                        <td class="px-3 py-2 text-right">
                            <div class="flex justify-end gap-3">
                                <Link :href="`/admin/payments/${p.id}`" class="text-blue-400 hover:underline">Pregled</Link>
                                <Link :href="`/admin/payments/${p.id}/edit`" class="text-blue-400 hover:underline">Uredi</Link>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="!payments.data.length">
                        <td colspan="7" class="px-3 py-10 text-center text-gray-400">
                            Nema plaćanja.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>