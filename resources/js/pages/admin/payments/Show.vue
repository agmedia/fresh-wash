<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

type Payment = {
    id: number;
    provider: string;
    status: string;
    amount: string | number;
    currency: string;
    provider_ref?: string | null;
    paid_at?: string | null;
    payload?: any;
    order?: { id: number; code: string; user?: { email: string } | null } | null;
};

const props = withDefaults(defineProps<{ payment?: Payment | null }>(), {
    payment: null,
});

const payment = computed<Payment | null>(() => props.payment ?? null);

const title = computed(() =>
    payment.value ? `Plaćanje #${payment.value.id}` : 'Plaćanje'
);

const listHref = '/admin/payments';
const editHref = computed(() =>
    payment.value ? `/admin/payments/${payment.value.id}/edit` : listHref
);

const orderHref = computed(() =>
    payment.value?.order ? `/admin/orders/${payment.value.order.id}` : null
);

const payloadPretty = computed(() => {
    const val = payment.value?.payload;
    if (val == null) return '-';
    if (typeof val === 'string') return val;
    try {
        return JSON.stringify(val, null, 2);
    } catch {
        return String(val);
    }
});

const statusBadgeClass = computed(() => {
    const s = (payment.value?.status ?? '').toLowerCase();
    if (['succeeded', 'paid', 'success'].includes(s)) {
        return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300';
    }
    if (['pending', 'processing'].includes(s)) {
        return 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/40 dark:text-amber-300';
    }
    if (['failed', 'error'].includes(s)) {
        return 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-200';
    }
    if (['refunded'].includes(s)) {
        return 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300';
    }
    return 'border-border bg-muted text-muted-foreground';
});

const failureReason = computed(() => {
    const pay = payment.value?.payload;
    if (!pay || typeof pay !== 'object') return null;

    const candidates = [
        pay?.error?.message,
        pay?.error_message,
        pay?.message,
        pay?.failure_message,
        pay?.failure_reason,
        pay?.decline_code,
        pay?.code,
        pay?.status_reason,
        pay?.outcome?.seller_message,
        pay?.outcome?.network_status,
    ].filter(Boolean);

    if (!candidates.length) return null;

    return Array.from(new Set(candidates.map((x: any) => String(x)))).join(' · ');
});
</script>

<template>
    <AdminLayout :title="title.value">
        <div class="max-w-4xl space-y-6">
            <!-- Fallback -->
            <div
                v-if="!payment"
                class="rounded border border-border bg-card p-5 card-elev"
            >
                <div class="text-sm font-medium text-foreground">
                    Plaćanje nije učitano
                </div>
                <div class="mt-1 text-sm text-muted-foreground">
                    (payment prop nedostaje ili je null)
                </div>

                <div class="mt-4">
                    <a
                        :href="listHref"
                        class="text-sm text-muted-foreground hover:text-foreground"
                    >
                        Natrag
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div v-else class="space-y-6">
                <!-- Sticky header -->
                <div class="sticky top-3 z-20">
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-foreground truncate">
                                Payment #{{ payment.id }}
                            </div>

                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                <span
                                    class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                    :class="statusBadgeClass"
                                >
                                    {{ payment.status }}
                                </span>

                                <span class="text-xs text-muted-foreground">
                                    {{ payment.provider }}
                                </span>

                                <span v-if="payment.order?.code" class="text-xs text-muted-foreground">
                                    · Order {{ payment.order.code }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <Link
                                :href="editHref"
                                class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground
                                       hover:opacity-90"
                            >
                                Uredi
                            </Link>

                            <a
                                :href="listHref"
                                class="text-sm text-muted-foreground hover:text-foreground"
                            >
                                Natrag
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main info -->
                <div class="rounded border border-border bg-card p-5 card-elev space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <!-- Context -->
                        <div class="rounded border border-border bg-card p-4">
                            <div class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                                Kontekst
                            </div>

                            <div class="mt-3 grid gap-2 text-sm">
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="text-xs font-medium text-muted-foreground">Order</div>
                                    <div class="text-foreground">
                                        <Link
                                            v-if="payment.order && orderHref"
                                            :href="orderHref"
                                            class="text-primary hover:underline"
                                        >
                                            {{ payment.order.code }}
                                        </Link>
                                        <span v-else>-</span>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="text-xs font-medium text-muted-foreground">Email</div>
                                    <div class="text-foreground">
                                        {{ payment.order?.user?.email ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment -->
                        <div class="rounded border border-border bg-card p-4">
                            <div class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                                Plaćanje
                            </div>

                            <div class="mt-3 grid gap-2 text-sm">
                                <div>
                                    <div class="text-xs font-medium text-muted-foreground">Iznos</div>
                                    <div class="mt-1 text-foreground font-medium tabular-nums">
                                        {{ payment.amount }} {{ payment.currency }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs font-medium text-muted-foreground">Paid at</div>
                                    <div class="mt-1 text-foreground">
                                        {{ payment.paid_at ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs font-medium text-muted-foreground">Provider ref</div>
                                    <div class="mt-1 text-foreground break-all">
                                        {{ payment.provider_ref ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Failure reason -->
                    <div
                        v-if="failureReason"
                        class="rounded border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-200"
                    >
                        <div class="text-xs font-semibold uppercase tracking-wide">
                            Razlog neuspjeha
                        </div>
                        <div class="mt-1">
                            {{ failureReason }}
                        </div>
                    </div>

                    <div
                        v-else-if="String(payment.status).toLowerCase() === 'failed'"
                        class="text-sm text-muted-foreground"
                    >
                        Status je <span class="font-medium">failed</span>, ali payload nema detaljan razlog.
                    </div>
                </div>

                <!-- Payload -->
                <div class="rounded border border-border bg-card p-5 card-elev">
                    <div class="mb-2 text-sm font-medium text-foreground">Payload</div>
                    <pre class="overflow-auto rounded border border-border bg-muted p-3 text-xs">{{ payloadPretty }}</pre>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
