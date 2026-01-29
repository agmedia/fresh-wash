<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

type Tariff = {
    id: number;
    price_net: string | number;
    vat_rate: string | number;
    price_gross: string | number;
    currency: string;
    is_active: boolean;
    valid_from?: string | null;
    valid_to?: string | null;
    service?: { id: number; name: string; code: string };
    location?: { id: number; name: string };
};

defineProps<{ tariff: Tariff }>();
</script>

<template>
    <AdminLayout :title="`Tarifa #${tariff.id}`">
        <div class="max-w-3xl space-y-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-sm text-gray-500">
                        {{ tariff.location?.name ?? '-' }}
                    </div>
                    <h2 class="text-xl font-semibold">
                        {{ tariff.service?.name ?? '-' }}
                    </h2>
                </div>
                <a
                    :href="`/admin/catalog/tariffs/${tariff.id}/edit`"
                    class="rounded border px-3 py-2 text-sm"
                    >Uredi</a
                >
            </div>

            <div class="space-y-1 rounded border p-4 text-sm">
                <div>
                    <strong>Net:</strong> {{ tariff.price_net }}
                    {{ tariff.currency }}
                </div>
                <div><strong>PDV:</strong> {{ tariff.vat_rate }}%</div>
                <div>
                    <strong>Bruto:</strong> {{ tariff.price_gross }}
                    {{ tariff.currency }}
                </div>
                <div>
                    <strong>Aktivno:</strong>
                    {{ tariff.is_active ? 'da' : 'ne' }}
                </div>
                <div>
                    <strong>Vrijedi od:</strong> {{ tariff.valid_from ?? '-' }}
                </div>
                <div>
                    <strong>Vrijedi do:</strong> {{ tariff.valid_to ?? '-' }}
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
