<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

type Payment = {
    id: number;
    provider: string;
    status: string;
    amount: string | number;
    currency: string;
    provider_ref?: string | null;
    paid_at?: string | null;
    payload?: any;
    order?: {
        id: number;
        code: string;
        user?: { email: string } | null;
    } | null;
};

const { payment, statuses, providers } = defineProps<{
    payment: Payment;
    statuses: string[];
    providers: string[];
}>();

const payloadJson = ref(payment.payload ? JSON.stringify(payment.payload, null, 2) : '');

const form = useForm({
    provider: payment.provider ?? 'offline',
    status: payment.status ?? 'pending',
    amount: String(payment.amount ?? '0'),
    currency: payment.currency ?? 'EUR',
    provider_ref: payment.provider_ref ?? '',
    paid_at: payment.paid_at ?? '',
    payload: payment.payload ?? null,
});

const processing = computed(() => form.processing);

const showHref = computed(() => `/admin/payments/${payment.id}`);
const backHref = '/admin/payments';

const submit = () => {
    form
        .transform((data) => {
            let payload: any = null;

            if (payloadJson.value?.trim()) {
                try {
                    payload = JSON.parse(payloadJson.value);
                } catch {
                    payload = null;
                }
            }

            return { ...data, payload };
        })
        .put(`/admin/payments/${payment.id}`, { preserveScroll: true });
};
</script>

<template>
    <AdminLayout :title="`Uredi plaćanje #${payment.id}`">
        <div class="max-w-3xl space-y-6">
            <!-- Sticky actions -->
            <div class="sticky top-3 z-20">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-foreground truncate">
                            Payment #{{ payment.id }}
                        </div>
                        <div class="text-xs text-muted-foreground">
                            {{ payment.order?.code ?? '-' }} · {{ payment.order?.user?.email ?? '-' }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
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
                <!-- Context -->
                <div class="rounded border border-border bg-card p-4 text-sm">
                    <div class="grid gap-1">
                        <div>
                            <span class="text-xs font-medium text-muted-foreground">Order</span>
                            <span class="ml-2 text-foreground">{{ payment.order?.code ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-muted-foreground">Email</span>
                            <span class="ml-2 text-foreground">{{ payment.order?.user?.email ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Provider + Status -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Provider</label>
                        <select
                            v-model="form.provider"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option v-for="p in providers" :key="p" :value="p">
                                {{ p }}
                            </option>
                        </select>
                        <p v-if="form.errors.provider" class="text-xs text-rose-600">
                            {{ form.errors.provider }}
                        </p>
                    </div>

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
                    </div>
                </div>

                <!-- Amount -->
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Iznos</label>
                        <input
                            v-model="form.amount"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.amount" class="text-xs text-rose-600">
                            {{ form.errors.amount }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Valuta</label>
                        <input
                            v-model="form.currency"
                            maxlength="3"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm uppercase
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.currency" class="text-xs text-rose-600">
                            {{ form.errors.currency }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Paid at</label>
                        <input
                            v-model="form.paid_at"
                            type="datetime-local"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.paid_at" class="text-xs text-rose-600">
                            {{ form.errors.paid_at }}
                        </p>
                    </div>
                </div>

                <!-- Provider ref -->
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-foreground">Provider ref</label>
                    <input
                        v-model="form.provider_ref"
                        class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-ring/40"
                    />
                    <p v-if="form.errors.provider_ref" class="text-xs text-rose-600">
                        {{ form.errors.provider_ref }}
                    </p>
                </div>

                <!-- Payload -->
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-foreground">Payload (JSON)</label>
                    <textarea
                        v-model="payloadJson"
                        rows="8"
                        class="w-full rounded border border-input bg-background px-3 py-2 font-mono text-xs
                               focus:outline-none focus:ring-2 focus:ring-ring/40"
                    />
                    <div class="text-xs text-muted-foreground">
                        Ako JSON nije validan, payload će se poslati kao null.
                    </div>
                    <p v-if="form.errors.payload" class="text-xs text-rose-600">
                        {{ form.errors.payload }}
                    </p>
                </div>

                <!-- Bottom actions -->
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
