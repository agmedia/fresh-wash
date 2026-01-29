<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

type Service = { id: number; name: string; code: string };
type Location = { id: number; name: string };

type Tariff = {
    id: number;
    service_id: number;
    location_id: number;
    price_net: string | number;
    vat_rate: string | number;
    price_gross: string | number;
    currency: string;
    is_active: boolean;
    valid_from?: string | null;
    valid_to?: string | null;
};

const props = defineProps<{
    tariff: Tariff;
    services: Service[];
    locations: Location[];
    defaults: { vat_rate: number; currency: string };
}>();

const form = useForm({
    service_id: props.tariff.service_id,
    location_id: props.tariff.location_id,
    price_net: String(props.tariff.price_net ?? ''),
    vat_rate: Number(props.tariff.vat_rate ?? props.defaults?.vat_rate ?? 25.0),
    price_gross: String(props.tariff.price_gross ?? ''),
    currency: props.tariff.currency ?? props.defaults?.currency ?? 'EUR',
    is_active: props.tariff.is_active ?? true,
    valid_from: (props.tariff.valid_from ?? '') as any,
    valid_to: (props.tariff.valid_to ?? '') as any,
});

const processing = computed(() => form.processing);

const calcGross = () => {
    const net = Number(form.price_net || 0);
    const vat = Number(form.vat_rate || 0);
    const gross = net * (1 + vat / 100);
    form.price_gross = isFinite(gross) ? gross.toFixed(2) : '';
};

watch(() => [form.price_net, form.vat_rate], calcGross);

const submit = () => {
    form.put(`/admin/catalog/tariffs/${props.tariff.id}`);
};
</script>

<template>
    <AdminLayout :title="`Uredi tarifu #${tariff.id}`">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Lokacija</label>
                    <select
                        v-model="form.location_id"
                        class="w-full rounded border px-3 py-2 text-sm"
                    >
                        <option
                            v-for="l in locations"
                            :key="l.id"
                            :value="l.id"
                        >
                            {{ l.name }}
                        </option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Usluga</label>
                    <select
                        v-model="form.service_id"
                        class="w-full rounded border px-3 py-2 text-sm"
                    >
                        <option v-for="s in services" :key="s.id" :value="s.id">
                            {{ s.name }} ({{ s.code }})
                        </option>
                    </select>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Net</label>
                    <input
                        v-model="form.price_net"
                        type="number"
                        step="0.01"
                        min="0"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">PDV %</label>
                    <input
                        v-model="form.vat_rate"
                        type="number"
                        step="0.01"
                        min="0"
                        max="100"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Bruto</label>
                    <input
                        v-model="form.price_gross"
                        type="text"
                        readonly
                        class="w-full rounded border bg-gray-50 px-3 py-2 text-sm"
                    />
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Valuta</label>
                    <input
                        v-model="form.currency"
                        maxlength="3"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Vrijedi od</label>
                    <input
                        v-model="form.valid_from"
                        type="date"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Vrijedi do</label>
                    <input
                        v-model="form.valid_to"
                        type="date"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="active"
                    type="checkbox"
                    v-model="form.is_active"
                    class="h-4 w-4"
                />
                <label for="active" class="text-sm">Aktivno</label>
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
                    :href="`/admin/catalog/tariffs/${tariff.id}`"
                    class="text-sm text-blue-600"
                    >Pregled</a
                >
            </div>
        </form>
    </AdminLayout>
</template>
