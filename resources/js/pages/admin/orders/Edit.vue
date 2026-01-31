<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

type Order = {
    id: number;
    code: string;
    status: 'pending' | 'paid' | 'cancelled' | 'refunded' | string;
    note?: string | null;
    currency: string;
    total_net: string | number;
    vat_rate: string | number;
    total_gross: string | number;
    user?: { email: string; first_name?: string | null; last_name?: string | null; name?: string | null } | null;
    location?: { name: string } | null;
};

const { order, statuses } = defineProps<{
    order: Order;
    statuses: string[];
}>();

const form = useForm({
    status: order.status ?? 'pending',
    note: order.note ?? '',
    currency: order.currency ?? 'EUR',
    total_net: String(order.total_net ?? '0'),
    vat_rate: String(order.vat_rate ?? '25'),
    total_gross: String(order.total_gross ?? '0'),
});

const processing = computed(() => form.processing);
const showHref = computed(() => `/admin/orders/${order.id}`);
const backHref = '/admin/orders';

const userLabel = computed(() => {
    const u = order.user;
    if (!u) return '-';
    const full = [u.first_name, u.last_name].filter(Boolean).join(' ').trim();
    return full || u.name || u.email;
});

const submit = () => {
    form.put(`/admin/orders/${order.id}`, { preserveScroll: true });
};

/** UX: auto-gross */
const grossAuto = ref(true);

const toNum = (v: unknown) => {
    const n = Number(String(v ?? '').replace(',', '.'));
    return Number.isFinite(n) ? n : 0;
};

const calcGross = () => {
    const net = toNum(form.total_net);
    const vat = toNum(form.vat_rate);
    const gross = net * (1 + vat / 100);
    // 2 decimals
    return (Math.round(gross * 100) / 100).toFixed(2);
};

watch(
    () => [form.total_net, form.vat_rate],
    () => {
        if (grossAuto) form.total_gross = calcGross();
    },
    { immediate: true }
);

/** UX: unsaved indicator */
const initial = JSON.stringify(form.data());
const hasChanges = computed(() => JSON.stringify(form.data()) !== initial);
</script>

<template>
    <AdminLayout :title="`Uredi narudžbu #${order.id}`">
        <div class="max-w-3xl space-y-6">
            <!-- Sticky actions -->
            <div class="sticky top-3 z-20">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-foreground truncate">
                            {{ order.code }}
                        </div>
                        <div class="text-xs text-muted-foreground">
                            {{ userLabel }} · {{ order.location?.name ?? '-' }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span v-if="hasChanges" class="text-xs text-amber-700 dark:text-amber-300">
                            Nespremjene promjene
                        </span>

                        <button
                            type="button"
                            @click="submit"
                            class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground
                                   hover:opacity-90 disabled:opacity-60"
                            :disabled="processing"
                        >
                            {{ processing ? 'Spremanje…' : 'Spremi' }}
                        </button>

                        <a :href="showHref" class="text-sm text-muted-foreground hover:text-foreground">
                            Pregled
                        </a>

                        <a :href="backHref" class="text-sm text-muted-foreground hover:text-foreground">
                            Natrag
                        </a>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="submit"
                class="rounded border border-border bg-card p-5 card-elev space-y-6"
            >
                <!-- Status -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Status</label>
                        <select
                            v-model="form.status"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option v-for="s in statuses" :key="s" :value="s">
                                {{ s }}
                            </option>
                        </select>
                        <p v-if="form.errors.status" class="text-xs text-rose-600">
                            {{ form.errors.status }}
                        </p>
                        <p class="text-xs text-muted-foreground">
                            Promjena statusa ne mijenja iznose, samo evidenciju narudžbe.
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Valuta</label>
                        <input
                            v-model="form.currency"
                            maxlength="3"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm uppercase
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                            placeholder="EUR"
                        />
                        <p v-if="form.errors.currency" class="text-xs text-rose-600">
                            {{ form.errors.currency }}
                        </p>
                        <p class="text-xs text-muted-foreground">
                            ISO kod (npr. EUR, HRK).
                        </p>
                    </div>
                </div>

                <!-- Totals -->
                <div class="rounded border border-border bg-card p-4">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <div>
                            <div class="text-sm font-semibold text-foreground">Iznosi</div>
                            <div class="text-xs text-muted-foreground">Net, PDV i brutto.</div>
                        </div>

                        <label class="inline-flex items-center gap-2 text-xs text-muted-foreground">
                            <input
                                type="checkbox"
                                v-model="grossAuto"
                                class="h-4 w-4 rounded border border-input bg-background"
                            />
                            Auto izračun brutto
                        </label>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Total net</label>
                            <input
                                v-model="form.total_net"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.total_net" class="text-xs text-rose-600">
                                {{ form.errors.total_net }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">PDV %</label>
                            <input
                                v-model="form.vat_rate"
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.vat_rate" class="text-xs text-rose-600">
                                {{ form.errors.vat_rate }}
                            </p>
                            <p class="text-xs text-muted-foreground">npr. 25</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Total gross</label>
                            <input
                                v-model="form.total_gross"
                                :readonly="grossAuto"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40
                                       disabled:opacity-60"
                            />
                            <p v-if="form.errors.total_gross" class="text-xs text-rose-600">
                                {{ form.errors.total_gross }}
                            </p>
                            <p v-if="grossAuto" class="text-xs text-muted-foreground">
                                Brutto se računa: net × (1 + PDV/100)
                            </p>
                            <p v-else class="text-xs text-muted-foreground">
                                Ručno uređivanje uključeno.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Note -->
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-foreground">Napomena</label>
                    <textarea
                        v-model="form.note"
                        rows="4"
                        class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-ring/40"
                        placeholder="Interna napomena..."
                    />
                    <p v-if="form.errors.note" class="text-xs text-rose-600">
                        {{ form.errors.note }}
                    </p>
                </div>

                <!-- Bottom actions (for long pages) -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground
                               hover:opacity-90 disabled:opacity-60"
                        :disabled="processing"
                    >
                        {{ processing ? 'Spremanje…' : 'Spremi promjene' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
