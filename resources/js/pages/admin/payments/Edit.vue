<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
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
    user?: {
        email: string;
        first_name?: string | null;
        last_name?: string | null;
        name?: string | null;
    } | null;
    location?: { name: string } | null;
};

const props = defineProps<{
    order: Order;
    statuses: string[];
}>();

const form = useForm({
    status: props.order.status ?? 'pending',
    note: props.order.note ?? '',
    currency: props.order.currency ?? 'EUR',
    total_net: String(props.order.total_net ?? '0'),
    vat_rate: String(props.order.vat_rate ?? '25'),
    total_gross: String(props.order.total_gross ?? '0'),
});

const processing = computed(() => form.processing);

const submit = () => {
    form.put(`/admin/orders/${props.order.id}`);
};
</script>

<template>
    <AdminLayout :title="`Uredi narudžbu ${order.code}`">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="space-y-1 rounded border bg-white p-4 text-sm">
                <div>
                    <strong>Korisnik:</strong> {{ order.user?.email ?? '-' }}
                </div>
                <div>
                    <strong>Lokacija:</strong> {{ order.location?.name ?? '-' }}
                </div>
            </div>

            <div class="space-y-4 rounded border bg-white p-4">
                <div class="grid gap-4 md:grid-cols-2">
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
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Net</label>
                        <input
                            v-model="form.total_net"
                            type="number"
                            step="0.01"
                            class="w-full rounded border px-3 py-2 text-sm"
                        />
                        <div
                            v-if="form.errors.total_net"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.total_net }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium">PDV %</label>
                        <input
                            v-model="form.vat_rate"
                            type="number"
                            step="0.01"
                            class="w-full rounded border px-3 py-2 text-sm"
                        />
                        <div
                            v-if="form.errors.vat_rate"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.vat_rate }}
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Bruto</label>
                        <input
                            v-model="form.total_gross"
                            type="number"
                            step="0.01"
                            class="w-full rounded border px-3 py-2 text-sm"
                        />
                        <div
                            v-if="form.errors.total_gross"
                            class="text-sm text-red-600"
                        >
                            {{ form.errors.total_gross }}
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Napomena</label>
                    <textarea
                        v-model="form.note"
                        rows="4"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div v-if="form.errors.note" class="text-sm text-red-600">
                        {{ form.errors.note }}
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
                        :href="`/admin/orders/${order.id}`"
                        class="text-sm text-blue-600"
                        >Pregled</a
                    >
                </div>
            </div>
        </form>
    </AdminLayout>
</template>
