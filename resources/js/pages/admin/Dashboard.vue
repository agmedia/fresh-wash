<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

type Stats = {
    users?: number;
    locations?: number;
    lockers?: number;
    devices?: number;
    reservations?: number;
    orders?: number;
    payments?: number;
    revenue_today_gross?: number;
    revenue_today_net?: number;
    pending_orders?: number;
    pending_payments?: number;
    revenue_last_7_days?: Array<{ date: string; label: string; gross: number; net: number }>;
};

const page = usePage();
const stats = computed<Stats>(() => (page.props as any).stats ?? {});

const money = (value: unknown) => {
    const n = Number(value ?? 0);
    return new Intl.NumberFormat('hr-HR', { style: 'currency', currency: 'EUR' }).format(isFinite(n) ? n : 0);
};

const number = (value: unknown) => {
    const n = Number(value ?? 0);
    return new Intl.NumberFormat('hr-HR').format(isFinite(n) ? n : 0);
};

type StatCard = {
    key: string;
    title: string;
    value: () => string;
    sublabel?: string;
    subvalue?: () => string;
};

const statCards = computed<StatCard[]>(() => [
    {
        key: 'revenue_today',
        title: 'Prihod danas (bruto)',
        value: () => money(stats.value.revenue_today_gross),
        sublabel: 'NET',
        subvalue: () => money(stats.value.revenue_today_net),
    },
    {
        key: 'orders',
        title: 'Narudžbe',
        value: () => number(stats.value.orders),
        sublabel: 'Pending',
        subvalue: () => number(stats.value.pending_orders),
    },
    {
        key: 'payments',
        title: 'Plaćanja',
        value: () => number(stats.value.payments),
        sublabel: 'Pending',
        subvalue: () => number(stats.value.pending_payments),
    },
    {
        key: 'reservations',
        title: 'Rezervacije',
        value: () => number(stats.value.reservations),
        sublabel: 'Ukupno',
        subvalue: () => number(stats.value.reservations),
    },
    {
        key: 'users',
        title: 'Korisnici',
        value: () => number(stats.value.users),
        sublabel: 'Ukupno',
        subvalue: () => number(stats.value.users),
    },
    {
        key: 'locations',
        title: 'Lokacije',
        value: () => number(stats.value.locations),
        sublabel: 'Aktivne',
        subvalue: () => number(stats.value.locations),
    },
    {
        key: 'lockers',
        title: 'Ormarići / Lockers',
        value: () => number(stats.value.lockers),
        sublabel: 'Ukupno',
        subvalue: () => number(stats.value.lockers),
    },
    {
        key: 'devices',
        title: 'Uređaji',
        value: () => number(stats.value.devices),
        sublabel: 'Ukupno',
        subvalue: () => number(stats.value.devices),
    },
]);

type QuickLink = { href: string; label: string };

const quickLinks: QuickLink[] = [
    { href: '/admin/reservations', label: 'Rezervacije' },
    { href: '/admin/orders', label: 'Narudžbe' },
    { href: '/admin/payments', label: 'Plaćanja' },
    { href: '/admin/locations', label: 'Lokacije' },
    { href: '/admin/lockers', label: 'Uređaji / Lockers' },
    { href: '/admin/devices', label: 'Uređaji' },
    { href: '/admin/services', label: 'Usluge' },
    { href: '/admin/tariffs', label: 'Tarife' },
    { href: '/admin/pages', label: 'Stranice' },
    { href: '/admin/settings/roles', label: 'Uloge & prava' },
];
</script>

<template>
    <AdminLayout title="Admin dashboard">
        <div class="space-y-6">
            <!-- Stat cards -->
            <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-4">
                <div
                    v-for="c in statCards"
                    :key="c.key"
                    class="rounded border border-border bg-card p-4 card-elev"
                >
                    <div class="text-xs text-muted-foreground">{{ c.title }}</div>

                    <div class="mt-1 text-2xl font-semibold tabular-nums text-foreground">
                        {{ c.value() }}
                    </div>

                    <div v-if="c.sublabel && c.subvalue" class="mt-2 text-xs text-muted-foreground">
                        {{ c.sublabel }}:
                        <span class="tabular-nums text-foreground/80">{{ c.subvalue() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick links -->
            <div class="rounded border border-border bg-card p-4 card-elev">
                <div class="text-sm font-semibold text-foreground">Brzi linkovi</div>

                <div class="mt-3 flex flex-wrap gap-2">
                    <Link
                        v-for="l in quickLinks"
                        :key="l.href"
                        :href="l.href"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        {{ l.label }}
                    </Link>
                </div>

                <div class="mt-3 text-xs text-muted-foreground">
                    * Ako ne vidiš brojke, provjeri da controller za dashboard šalje
                    <code class="rounded bg-muted px-1 py-0.5">stats</code>
                    u Inertia props.
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
