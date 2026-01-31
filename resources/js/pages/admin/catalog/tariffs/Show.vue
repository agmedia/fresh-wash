<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { computed } from 'vue';

type Tariff = {
    id: number;
    price_net: string | number;
    vat_rate: string | number;
    price_gross: string | number;
    currency: string;
    is_active: boolean;
    valid_from?: string | null;
    valid_to?: string | null;
    service?: { id: number; name: string; code: string } | null;
    location?: { id: number; name: string } | null;
};

const { tariff } = defineProps<{ tariff: Tariff }>();

const editHref = computed(() => `/admin/catalog/tariffs/${tariff.id}/edit`);
const backHref = '/admin/catalog/tariffs';

const activeBadge = computed(() =>
    tariff.is_active
        ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300'
        : 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300',
);
</script>

<template>
    <AdminLayout :title="`Tarifa #${tariff.id}`">
        <div class="max-w-3xl space-y-6">
            <!-- Sticky header -->
            <div class="sticky top-3 z-20">
                <div
                    class="flex flex-wrap items-start justify-between gap-3 rounded border border-border bg-card p-3 card-elev"
                >
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-foreground truncate">
                            {{ tariff.service?.name ?? 'Tarifa' }}
                        </div>
                        <div class="text-xs text-muted-foreground">
                            {{ tariff.location?.name ?? '-' }}
                            <span v-if="tariff.service?.code"> · {{ tariff.service.code }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span
                            class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                            :class="activeBadge"
                        >
                            {{ tariff.is_active ? 'Aktivna' : 'Neaktivna' }}
                        </span>

                        <a
                            :href="editHref"
                            class="rounded border border-border bg-card px-3 py-2 text-sm hover:bg-muted"
                        >
                            Uredi
                        </a>

                        <a
                            :href="backHref"
                            class="text-sm text-muted-foreground hover:text-foreground"
                        >
                            Natrag
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cards -->
            <div class="rounded border border-border bg-card p-5 card-elev">
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded border border-border bg-card p-4">
                        <div class="text-xs font-medium text-muted-foreground">Net</div>
                        <div class="mt-1 text-foreground font-medium tabular-nums">
                            {{ tariff.price_net }} {{ tariff.currency }}
                        </div>
                    </div>

                    <div class="rounded border border-border bg-card p-4">
                        <div class="text-xs font-medium text-muted-foreground">PDV</div>
                        <div class="mt-1 text-foreground font-medium tabular-nums">
                            {{ tariff.vat_rate }}%
                        </div>
                    </div>

                    <div class="rounded border border-border bg-card p-4">
                        <div class="text-xs font-medium text-muted-foreground">Bruto</div>
                        <div class="mt-1 text-foreground font-medium tabular-nums">
                            {{ tariff.price_gross }} {{ tariff.currency }}
                        </div>
                    </div>
                </div>

                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div class="rounded border border-border bg-card p-4">
                        <div class="text-xs font-medium text-muted-foreground">Vrijedi od</div>
                        <div class="mt-1 text-foreground">
                            {{ tariff.valid_from ?? '-' }}
                        </div>
                    </div>

                    <div class="rounded border border-border bg-card p-4">
                        <div class="text-xs font-medium text-muted-foreground">Vrijedi do</div>
                        <div class="mt-1 text-foreground">
                            {{ tariff.valid_to ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
