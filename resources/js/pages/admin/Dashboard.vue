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

// const series = computed(() => stats.value.revenue_last_7_days ?? []);
// const maxGross = computed(() => {
//     const vals = series.value.map((d) => Number(d.gross ?? 0));
//     return Math.max(1, ...vals);
// });
// const barHeight = (gross: number) => {
//     const g = Number(gross ?? 0);
//     return Math.round((g / maxGross.value) * 100);
// };

const money = (value: unknown) => {
    const n = Number(value ?? 0);
    return new Intl.NumberFormat('hr-HR', { style: 'currency', currency: 'EUR' }).format(isFinite(n) ? n : 0);
};

const number = (value: unknown) => {
    const n = Number(value ?? 0);
    return new Intl.NumberFormat('hr-HR').format(isFinite(n) ? n : 0);
};
</script>

<template>
    <AdminLayout title="Admin dashboard">
        <div class="space-y-6">
            <!-- Stat cards -->
            <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-4">
                <div class="rounded border border-border bg-card p-4">
                    <div class="text-xs text-muted-foreground">Prihod danas (bruto)</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-foreground">
                        {{ money(stats.revenue_today_gross) }}
                    </div>
                    <div class="mt-2 text-xs text-muted-foreground">
                        NET: <span class="tabular-nums text-foreground/80">{{ money(stats.revenue_today_net) }}</span>
                    </div>
                </div>

                <div class="rounded border border-border bg-card p-4">
                    <div class="text-xs text-muted-foreground">Narudžbe</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-foreground">
                        {{ number(stats.orders) }}
                    </div>
                    <div class="mt-2 text-xs text-muted-foreground">
                        Pending: <span class="tabular-nums text-foreground/80">{{ number(stats.pending_orders) }}</span>
                    </div>
                </div>

                <div class="rounded border border-border bg-card p-4">
                    <div class="text-xs text-muted-foreground">Plaćanja</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-foreground">
                        {{ number(stats.payments) }}
                    </div>
                    <div class="mt-2 text-xs text-muted-foreground">
                        Pending: <span class="tabular-nums text-foreground/80">{{ number(stats.pending_payments) }}</span>
                    </div>
                </div>

                <div class="rounded border border-border bg-card p-4">
                    <div class="text-xs text-muted-foreground">Rezervacije</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-foreground">
                        {{ number(stats.reservations) }}
                    </div>
                    <div class="mt-2 text-xs text-muted-foreground">Ukupno</div>
                </div>

                <div class="rounded border border-border bg-card p-4">
                    <div class="text-xs text-muted-foreground">Korisnici</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-foreground">
                        {{ number(stats.users) }}
                    </div>
                    <div class="mt-2 text-xs text-muted-foreground">Ukupno</div>
                </div>

                <div class="rounded border border-border bg-card p-4">
                    <div class="text-xs text-muted-foreground">Lokacije</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-foreground">
                        {{ number(stats.locations) }}
                    </div>
                    <div class="mt-2 text-xs text-muted-foreground">Aktivne</div>
                </div>

                <div class="rounded border border-border bg-card p-4">
                    <div class="text-xs text-muted-foreground">Ormarići / VešMašine</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-foreground">
                        {{ number(stats.lockers) }}
                    </div>
                    <div class="mt-2 text-xs text-muted-foreground">Ukupno</div>
                </div>

                <div class="rounded border border-border bg-card p-4">
                    <div class="text-xs text-muted-foreground">Uređaji</div>
                    <div class="mt-1 text-2xl font-semibold tabular-nums text-foreground">
                        {{ number(stats.devices) }}
                    </div>
                    <div class="mt-2 text-xs text-muted-foreground">Ukupno</div>
                </div>
            </div>

            <!-- Quick links -->
            <div class="rounded border border-border bg-card p-4">
                <div class="text-sm font-semibold text-foreground">Brzi linkovi</div>

                <div class="mt-3 flex flex-wrap gap-2">
                    <Link
                        href="/admin/reservations"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        Rezervacije
                    </Link>
                    <Link
                        href="/admin/orders"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        Narudžbe
                    </Link>
                    <Link
                        href="/admin/payments"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        Plaćanja
                    </Link>
                    <Link
                        href="/admin/locations"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        Lokacije
                    </Link>
                    <Link
                        href="/admin/lockers"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        Ormarići
                    </Link>
                    <Link
                        href="/admin/devices"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        Uređaji
                    </Link>
                    <Link
                        href="/admin/services"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        Usluge
                    </Link>
                    <Link
                        href="/admin/tariffs"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        Tarife
                    </Link>
                    <Link
                        href="/admin/pages"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        Stranice
                    </Link>
                    <Link
                        href="/admin/settings/roles"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    >
                        Uloge & prava
                    </Link>
                </div>

                <div class="mt-3 text-xs text-muted-foreground">
                    * Ako ne vidiš brojke, provjeri da controller za dashboard šalje <code class="rounded bg-muted px-1 py-0.5">stats</code> u Inertia props.
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

