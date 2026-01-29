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

const props = defineProps<{
    payment: Payment;
    statuses: string[];
    providers: string[];
}>();

const payloadJson = ref(
    props.payment.payload ? JSON.stringify(props.payment.payload, null, 2) : '',
);

const form = useForm({
    provider: props.payment.provider ?? 'offline',
    status: props.payment.status ?? 'pending',
    amount: String(props.payment.amount ?? '0'),
    currency: props.payment.currency ?? 'EUR',
    provider_ref: props.payment.provider_ref ?? '',
    paid_at: props.payment.paid_at ?? '',
    payload: props.payment.payload ?? null,
});

const processing = computed(() => form.processing);

const submit = () => {
    form.transform((data) => {
        let payload: any = null;
        if (payloadJson.value?.trim()) {
            try {
                payload = JSON.parse(payloadJson.value);
            } catch (e) {
                // ako je neispravan JSON, pošalji null i prikaži grešku lokalno
                payload = null;
            }
        }
        return { ...data, payload };
    }).put(`/admin/payments/${props.payment.id}`);
};
</script>

<template>
    <AdminLayout :title="`Uredi plaćanje #${payment.id}`">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="space-y-1 rounded border bg-white p-4 text-sm">
                <div>
                    <strong>Order:</strong> {{ payment.order?.code ?? '-' }}
                </div>
                <div>
                    <strong>Email:</strong>
                    {{ payment.order?.user?.email ?? '-' }}
                </div>
            </div>

            <div class="space-y-4 rounded border bg-white p-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium"
                            >Provider</label
                        >
                        <select
                            v-model="form.provider"
                            class="w-full rounded border px-3 py-2 text-sm"
                        >
                            <option v-for="p in providers" :key="p" :value="p">
                                {{ p }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.provider"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.provider }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Status</label>
                        <select
                            v-model="form.status"
                            class="w-full rounded border px-3 py-2 text-sm"
                        >
                            <option v-for="s in statuses" :key="s" :value="s">
                                {{ s }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.status"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.status }}
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Iznos</label>
                        <input
                            v-model="form.amount"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full rounded border px-3 py-2 text-sm"
                        />
                        <div
                            v-if="form.errors.amount"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.amount }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Valuta</label>
                        <input
                            v-model="form.currency"
                            maxlength="3"
                            class="w-full rounded border px-3 py-2 text-sm"
                        />
                        <div
                            v-if="form.errors.currency"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.currency }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Paid at</label>
                        <input
                            v-model="form.paid_at"
                            type="datetime-local"
                            class="w-full rounded border px-3 py-2 text-sm"
                        />
                        <div
                            v-if="form.errors.paid_at"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.paid_at }}
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium"
                        >Provider ref</label
                    >
                    <input
                        v-model="form.provider_ref"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.provider_ref"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.provider_ref }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium"
                        >Payload (JSON)</label
                    >
                    <textarea
                        v-model="payloadJson"
                        rows="8"
                        class="w-full rounded border px-3 py-2 font-mono text-xs"
                    />
                    <div class="text-xs text-gray-500">
                        Ako JSON nije validan, payload će se poslati kao null.
                    </div>
                    <div
                        v-if="form.errors.payload"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.payload }}
                    </div>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="rounded bg-blue-600 px-4 py-2 text-sm text-white"
                        :disabled="processing"
                    >
                        Spremi
                    </button>
                    <a
                        :href="`/admin/payments/${payment.id}`"
                        class="text-sm text-blue-600"
                        >Pregled</a
                    >
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
