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
            return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300';
        case 'pending':
            return 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/40 dark:text-amber-300';
        case 'cancelled':
            return 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-300';
        case 'refunded':
            return 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300';
        default:
            return 'border-border bg-muted text-muted-foreground';
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
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <!-- Ukupno -->
            <div class="rounded border border-border bg-card p-3 text-sm text-foreground">
                <div class="text-muted-foreground">Ukupno</div>
                <div class="text-lg font-semibold tabular-nums">{{ totalCount }}</div>
            </div>

            <!-- Search -->
            <form @submit.prevent="submitSearch" class="flex items-center gap-2">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Pretraži (code, status, user, lokacija)"
                    class="w-80 rounded border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                />
                <button
                    class="rounded border border-border bg-card px-3 py-2 text-sm text-foreground hover:bg-muted"
                >
                    Traži
                </button>
            </form>
        </div>

        <!-- Table wrapper -->
        <div class="mt-4 overflow-auto rounded border border-border bg-card">
            <table class="min-w-full text-sm text-foreground">
                <thead class="bg-muted/60">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Code
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Korisnik
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Lokacija
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Status
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Ukupno
                    </th>
                    <th class="px-3 py-2"></th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="o in orders.data"
                    :key="o.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2">
                        <div class="font-medium tabular-nums">{{ o.code }}</div>
                        <div class="text-xs text-muted-foreground">#{{ o.id }}</div>
                    </td>

                    <td class="px-3 py-2">
                        <div class="font-medium leading-tight">
                            {{ userName(o) }}
                        </div>
                        <div class="max-w-[240px] truncate text-xs text-muted-foreground">
                            {{ o.user?.email ?? '-' }}
                        </div>
                    </td>

                    <td class="px-3 py-2">
                        {{ o.location?.name ?? '-' }}
                    </td>

                    <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                :class="statusClass(o.status)"
                            >
                                {{ statusLabel(o.status) }}
                            </span>
                    </td>

                    <td class="px-3 py-2 text-right font-medium tabular-nums">
                        {{ o.total_gross }} {{ o.currency }}
                    </td>

                    <td class="px-3 py-2 text-right">
                        <div class="inline-flex items-center gap-3 text-sm">
                            <Link
                                :href="`/admin/orders/${o.id}`"
                                class="text-primary hover:underline"
                            >
                                Pregled
                            </Link>
                            <span class="text-muted-foreground">·</span>
                            <Link
                                :href="`/admin/orders/${o.id}/edit`"
                                class="text-primary hover:underline"
                            >
                                Uredi
                            </Link>
                        </div>
                    </td>
                </tr>

                <tr v-if="!orders.data.length">
                    <td colspan="6" class="px-3 py-10 text-center text-muted-foreground">
                        Nema narudžbi.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
