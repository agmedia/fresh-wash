<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

type Service = {
    id: number;
    code: string;
    name: string;
    description?: string | null;
    sort_order: number;
    is_active: boolean;
};

defineProps<{ service: Service }>();
</script>

<template>
    <AdminLayout :title="`Usluga #${service.id}`">
        <div class="max-w-3xl space-y-6">
            <div class="flex items-start justify-between">
                <div>
                    <div class="font-mono text-sm text-gray-500">
                        {{ service.code }}
                    </div>
                    <h2 class="text-xl font-semibold">{{ service.name }}</h2>
                </div>
                <a
                    :href="`/admin/catalog/services/${service.id}/edit`"
                    class="rounded border px-3 py-2 text-sm"
                    >Uredi</a
                >
            </div>

            <div class="space-y-1 rounded border p-4 text-sm">
                <div>
                    <strong>Aktivno:</strong>
                    {{ service.is_active ? 'da' : 'ne' }}
                </div>
                <div><strong>Sort order:</strong> {{ service.sort_order }}</div>
            </div>

            <div class="rounded border p-4 text-sm">
                <div class="mb-2 font-medium">Opis</div>
                <div class="whitespace-pre-wrap text-gray-700">
                    {{ service.description || '-' }}
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
