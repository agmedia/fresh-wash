<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface ReservationRow {
    id: number;
    status: string;
    starts_at: string;
    ends_at: string | null;
    user: {
        id: number;
        email: string;
        first_name?: string | null;
        last_name?: string | null;
        name?: string | null;
    };
    locker: { id: number; code: string; name: string | null };
}

const props = defineProps<{
    reservations: { data: ReservationRow[]; links: any[] };
    filters: { search?: string; status?: string };
}>();

const search = ref(props.filters.search ?? '');
const status = ref(props.filters.status ?? '');

const submit = () => {
    router.get(
        '/admin/reservations',
        { search: search.value, status: status.value || undefined },
        { preserveState: true, replace: true },
    );
};
</script>

<template>
    <AdminLayout title="Reservations">
        <div class="mb-4 flex items-center justify-between gap-3">
            <form @submit.prevent="submit" class="flex items-center gap-2">
                <input
                    v-model="search"
                    class="w-72 rounded border px-3 py-2 text-sm"
                    placeholder="Traži (user / locker)..."
                />

                <select
                    v-model="status"
                    class="rounded border px-3 py-2 text-sm"
                >
                    <option value="">Svi statusi</option>
                    <option value="draft">draft</option>
                    <option value="active">active</option>
                    <option value="ended">ended</option>
                    <option value="cancelled">cancelled</option>
                </select>

                <button class="rounded border px-3 py-2 text-sm">
                    Filtriraj
                </button>
            </form>

            <a
                href="/admin/reservations/create"
                class="rounded border px-3 py-2 text-sm"
                >Nova rezervacija</a
            >
        </div>

        <table class="min-w-full border text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">Status</th>
                    <th class="px-3 py-2 text-left">User</th>
                    <th class="px-3 py-2 text-left">Locker</th>
                    <th class="px-3 py-2 text-left">Start</th>
                    <th class="px-3 py-2 text-left">End</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="r in reservations.data" :key="r.id" class="border-t">
                    <td class="px-3 py-2">{{ r.id }}</td>
                    <td class="px-3 py-2">{{ r.status }}</td>
                    <td class="px-3 py-2">{{ r.user.email }}</td>
                    <td class="px-3 py-2">
                        {{ r.locker.code }}
                        <span class="text-gray-500" v-if="r.locker.name"
                            >— {{ r.locker.name }}</span
                        >
                    </td>
                    <td class="px-3 py-2">{{ r.starts_at }}</td>
                    <td class="px-3 py-2">{{ r.ends_at ?? '-' }}</td>
                    <td class="px-3 py-2 text-right">
                        <a
                            :href="`/admin/reservations/${r.id}/edit`"
                            class="text-blue-600"
                            >Uredi</a
                        >
                    </td>
                </tr>
            </tbody>
        </table>
    </AdminLayout>
</template>
