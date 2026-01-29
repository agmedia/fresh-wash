<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Button } from '@/components/ui/button';

interface Location {
    id: number;
    name: string;
    city: string | null;
    postal_code: string | null;
    is_active: boolean;
}

const props = defineProps<{
    locations: {
        data: Location[];
        links: any[];
    };
    filters: { search?: string };
}>();

const search = ref(props.filters.search ?? '');

const submitSearch = () => {
    router.get(
        '/admin/locations',
        { search: search.value },
        { preserveState: true, replace: true },
    );
};
</script>

<template>
    <AdminLayout title="Lokacije">
        <div class="mb-4 flex items-center justify-between gap-3">
            <form
                @submit.prevent="submitSearch"
                class="flex items-center gap-2"
            >
                <input
                    v-model="search"
                    class="w-72 rounded border px-3 py-2 text-sm"
                    placeholder="Traži..."
                />
                <button class="rounded border px-3 py-2 text-sm">Traži</button>
            </form>

            <Button variant="outline" size="lg">
                <Link :href="`/admin/locations/create`">Nova lokacija</Link>
            </Button>
        </div>

        <table class="min-w-full border text-sm">
            <thead>
                <tr class="bg-neutral-900">
                    <th class="px-3 py-2 text-left">Naziv</th>
                    <th class="px-3 py-2 text-left">Grad</th>
                    <th class="px-3 py-2 text-left">Pošt. broj</th>
                    <th class="px-3 py-2 text-left">Aktivna</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="l in locations.data" :key="l.id" class="border-t">
                    <td class="px-3 py-2">{{ l.name }}</td>
                    <td class="px-3 py-2">{{ l.city }}</td>
                    <td class="px-3 py-2">{{ l.postal_code }}</td>
                    <td class="px-3 py-2">{{ l.is_active ? 'da' : 'ne' }}</td>
                    <td class="px-3 py-2 text-right">
                        <Button asChild variant="outline" class="ml-2">
                            <Link :href="`/admin/locations/${l.id}/edit`"
                                >Uredi</Link
                            >
                        </Button>
                    </td>
                </tr>
            </tbody>
        </table>
    </AdminLayout>
</template>