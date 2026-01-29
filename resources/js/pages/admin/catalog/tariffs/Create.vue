<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

type Service = { id: number; name: string; code: string };
type Location = { id: number; name: string };

const props = defineProps<{
    services: Service[];
    locations: Location[];
    defaults: { vat_rate: number; currency: string };
}>();

const form = useForm({
    service_id: props.services?.[0]?.id ?? null,
    location_id: props.locations?.[0]?.id ?? null,
    price_net: '',
    vat_rate: props.defaults?.vat_rate ?? 25.0,
    price_gross: '',
    currency: props.defaults?.currency ?? 'EUR',
    is_active: true,
    valid_from: '',
    valid_to: '',
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
    form.post('/admin/catalog/tariffs');
};
</script>

<template>
    <AdminLayout title="Nova tarifa">
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
                    <div
                        v-if="form.errors.location_id"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.location_id }}
                    </div>
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
                    <div
                        v-if="form.errors.service_id"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.service_id }}
                    </div>
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
                    <div
                        v-if="form.errors.price_net"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.price_net }}
                    </div>
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
                        v-model="form.price_gross"
                        type="text"
                        readonly
                        class="w-full rounded border bg-gray-50 px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.price_gross"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.price_gross }}
                    </div>
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
                    <div
                        v-if="form.errors.currency"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.currency }}
                    </div>
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
                    <div
                        v-if="form.errors.valid_to"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.valid_to }}
                    </div>
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
                <a href="/admin/catalog/tariffs" class="text-sm text-blue-600"
                    >Natrag</a
                >
            </div>
        </form>
    </AdminLayout>
</template>
