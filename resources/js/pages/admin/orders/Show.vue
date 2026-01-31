<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

type Order = {
    id: number;
    code: string;
    status: string;
    currency: string;
    total_net: string | number;
    vat_rate: string | number;
    total_gross: string | number;
    note?: string | null;
    user?: { email: string; first_name?: string | null; last_name?: string | null; name?: string | null } | null;
    location?: { name: string } | null;
    payments?: Array<{
        id: number;
        provider: string;
        status: string;
        amount: string | number;
        currency: string;
        paid_at?: string | null;
    }>;
};

const { order } = defineProps<{ order: Order }>();

const editHref = computed(() => `/admin/orders/${order.id}/edit`);
const listHref = '/admin/orders';

const userLabel = computed(() => {
    const u = order.user;
    if (!u) return '-';
    const full = [u.first_name, u.last_name].filter(Boolean).join(' ').trim();
    return full || u.name || u.email;
});
</script>

<template>
    <AdminLayout :title="`Narudžba #${order.id}`">
        <div class="max-w-4xl space-y-6">
            <!-- Sticky header -->
            <div class="sticky top-3 z-20">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-foreground truncate">{{ order.code }}</div>
                        <div class="text-xs text-muted-foreground">
                            {{ userLabel }} · {{ order.location?.name ?? '-' }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <Link
                            :href="editHref"
                            class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:opacity-90"
                        >
                            Uredi
                        </Link>
                        <a :href="listHref" class="text-sm text-muted-foreground hover:text-foreground">
                            Natrag
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main -->
            <div class="rounded border border-border bg-card p-5 card-elev space-y-5">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded border border-border bg-card p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Kontekst</div>
                        <div class="mt-3 grid gap-2 text-sm">
                            <div><span class="text-xs font-medium text-muted-foreground">Status</span> <span class="ml-2">{{ order.status }}</span></div>
                            <div><span class="text-xs font-medium text-muted-foreground">Email</span> <span class="ml-2">{{ order.user?.email ?? '-' }}</span></div>
                            <div><span class="text-xs font-medium text-muted-foreground">Lokacija</span> <span class="ml-2">{{ order.location?.name ?? '-' }}</span></div>
                        </div>
                    </div>

                    <div class="rounded border border-border bg-card p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Iznosi</div>
                        <div class="mt-3 grid gap-2 text-sm">
                            <div class="font-medium tabular-nums">Net: {{ order.total_net }} {{ order.currency }}</div>
                            <div class="tabular-nums">PDV: {{ order.vat_rate }}%</div>
                            <div class="font-medium tabular-nums">Brutto: {{ order.total_gross }} {{ order.currency }}</div>
                        </div>
                    </div>
                </div>

                <div v-if="order.note" class="rounded border border-border bg-card p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Napomena</div>
                    <div class="mt-2 text-sm text-foreground whitespace-pre-wrap">{{ order.note }}</div>
                </div>

                <div v-if="order.payments?.length" class="rounded border border-border bg-card p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Plaćanja</div>
                    <div class="mt-3 space-y-2">
                        <div
                            v-for="pay in order.payments"
                            :key="pay.id"
                            class="flex flex-wrap items-center justify-between gap-2 rounded border border-border bg-background px-3 py-2 text-sm"
                        >
                            <div class="min-w-0">
                                <Link :href="`/admin/payments/${pay.id}`" class="font-medium text-primary hover:underline">
                                    Payment #{{ pay.id }}
                                </Link>
                                <div class="text-xs text-muted-foreground">
                                    {{ pay.provider }} · {{ pay.status }} · {{ pay.paid_at ?? '-' }}
                                </div>
                            </div>
                            <div class="font-medium tabular-nums">
                                {{ pay.amount }} {{ pay.currency }}
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="text-sm text-muted-foreground">
                    Nema evidentiranih plaćanja.
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
