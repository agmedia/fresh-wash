<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

interface LocationLite {
    id: number;
    name: string;
}

interface Locker {
    id: number;
    location_id: number;
    code: string;
    name: string | null;
    slug: string | null;
    size: 'S' | 'M' | 'L' | 'XL';
    is_active: boolean;
    is_available: boolean;
    hardware_uid: string | null;
    note: string | null;
    location?: LocationLite;
}

const props = defineProps<{
    locker: Locker;
}>();
</script>

<template>
    <AdminLayout :title="`Ormarić #${locker.id}`">
        <div class="max-w-3xl space-y-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold">
                        {{ locker.code }}
                        <span class="text-gray-500" v-if="locker.name"
                            >— {{ locker.name }}</span
                        >
                    </h2>
                    <p class="text-sm text-gray-500" v-if="locker.slug">
                        Slug: {{ locker.slug }}
                    </p>
                </div>

                <div class="flex gap-2">
                    <a
                        :href="`/admin/lockers/${locker.id}/edit`"
                        class="rounded border px-3 py-2 text-sm"
                    >
                        Uredi
                    </a>
                </div>
            </div>

            <div class="rounded border p-4">
                <h3
                    class="mb-2 text-sm font-semibold tracking-wide text-gray-500 uppercase"
                >
                    Lokacija
                </h3>
                <div class="text-sm">
                    <span class="font-medium">Naziv:</span>
                    <span>{{ locker.location?.name ?? '-' }}</span>
                </div>
            </div>

            <div class="rounded border p-4">
                <h3
                    class="mb-2 text-sm font-semibold tracking-wide text-gray-500 uppercase"
                >
                    Status
                </h3>
                <div class="space-y-1 text-sm">
                    <div>
                        <span class="font-medium">Aktivan:</span>
                        {{ locker.is_active ? 'da' : 'ne' }}
                    </div>
                    <div>
                        <span class="font-medium">Dostupan:</span>
                        {{ locker.is_available ? 'da' : 'ne' }}
                    </div>
                    <div>
                        <span class="font-medium">Veličina:</span>
                        {{ locker.size }}
                    </div>
                </div>
            </div>

            <div class="rounded border p-4">
                <h3
                    class="mb-2 text-sm font-semibold tracking-wide text-gray-500 uppercase"
                >
                    Hardware
                </h3>
                <div class="text-sm">
                    <span class="font-medium">UID:</span>
                    {{ locker.hardware_uid ?? '-' }}
                </div>
            </div>

            <div v-if="locker.note" class="rounded border p-4">
                <h3
                    class="mb-2 text-sm font-semibold tracking-wide text-gray-500 uppercase"
                >
                    Napomena
                </h3>
                <p class="text-sm whitespace-pre-line">{{ locker.note }}</p>
            </div>
        </div>
    </AdminLayout>
</template>
