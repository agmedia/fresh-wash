<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

interface Location {
    id: number;
    name: string;
    slug: string | null;
    address_line1: string | null;
    address_line2: string | null;
    postal_code: string | null;
    city: string | null;
    country_code: string | null;
    lat: string | number | null;
    lng: string | number | null;
    is_active: boolean;
    note: string | null;
    created_at?: string;
    updated_at?: string;
}

const props = defineProps<{
    location: Location;
}>();
</script>

<template>
    <AdminLayout :title="`Lokacija #${location.id}`">
        <div class="max-w-3xl space-y-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold">{{ location.name }}</h2>
                    <p class="text-sm text-gray-500" v-if="location.slug">
                        Slug: {{ location.slug }}
                    </p>
                </div>

                <div class="flex gap-2">
                    <a
                        :href="route('admin.locations.edit', location.id)"
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
                    Status
                </h3>
                <div class="text-sm">
                    <span class="font-medium">Aktivna:</span>
                    <span v-if="location.is_active">da</span>
                    <span v-else>ne</span>
                </div>
            </div>

            <div class="rounded border p-4">
                <h3
                    class="mb-2 text-sm font-semibold tracking-wide text-gray-500 uppercase"
                >
                    Adresa
                </h3>
                <div class="space-y-1 text-sm">
                    <div v-if="location.address_line1">
                        {{ location.address_line1 }}
                    </div>
                    <div v-if="location.address_line2">
                        {{ location.address_line2 }}
                    </div>
                    <div>{{ location.postal_code }} {{ location.city }}</div>
                    <div v-if="location.country_code">
                        {{ location.country_code }}
                    </div>
                </div>
            </div>

            <div class="rounded border p-4">
                <h3
                    class="mb-2 text-sm font-semibold tracking-wide text-gray-500 uppercase"
                >
                    Koordinate
                </h3>
                <div class="space-y-1 text-sm">
                    <div>
                        <span class="font-medium">Lat:</span>
                        {{ location.lat ?? '-' }}
                    </div>
                    <div>
                        <span class="font-medium">Lng:</span>
                        {{ location.lng ?? '-' }}
                    </div>
                </div>
            </div>

            <div v-if="location.note" class="rounded border p-4">
                <h3
                    class="mb-2 text-sm font-semibold tracking-wide text-gray-500 uppercase"
                >
                    Napomena
                </h3>
                <p class="text-sm whitespace-pre-line">{{ location.note }}</p>
            </div>
        </div>
    </AdminLayout>
</template>
