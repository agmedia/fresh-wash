<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface UserRow {
    id: number;
    email: string;
    first_name: string | null;
    last_name: string | null;
    roles?: Array<string | { name: string }>; // can be ['admin'] or [{name:'admin'}]
}

const props = defineProps<{
    users: { data: UserRow[]; links: any[] };
    filters: { search?: string };
}>();

const search = ref(props.filters.search ?? '');

const submit = () => {
    router.get(
        '/admin/users',
        { search: search.value },
        { preserveState: true, replace: true },
    );
};
</script>

<template>
    <AdminLayout title="Korisnici">
        <div class="mb-4 flex items-center justify-between gap-3">
            <form @submit.prevent="submit" class="flex items-center gap-2">
                <input
                    v-model="search"
                    class="w-72 rounded border px-3 py-2 text-sm"
                    placeholder="Traži (email/ime)..."
                />
                <button class="rounded border px-3 py-2 text-sm">Traži</button>
            </form>

            <a
                href="/admin/users/create"
                class="rounded border px-3 py-2 text-sm"
                >Novi korisnik</a
            >
        </div>

        <table class="min-w-full border text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">Email</th>
                    <th class="px-3 py-2 text-left">Ime</th>
                    <th class="px-3 py-2 text-left">Uloga</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="u in users.data" :key="u.id" class="border-t">
                    <td class="px-3 py-2">{{ u.id }}</td>
                    <td class="px-3 py-2">{{ u.email }}</td>
                    <td class="px-3 py-2">
                        {{ (u.first_name ?? '') + ' ' + (u.last_name ?? '') }}
                    </td>
                    <td class="px-3 py-2">
                        {{
                            (u.roles && u.roles.length)
                                ? u.roles
                                      .map((r) => (typeof r === 'string' ? r : r.name))
                                      .join(', ')
                                : '-'
                        }}
                    </td>
                    <td class="px-3 py-2 text-right">
                        <div class="flex justify-end gap-3">
                            <a
                                :href="`/admin/users/${u.id}`"
                                class="text-blue-600"
                            >Pregled</a>
                            <a
                                :href="`/admin/users/${u.id}/edit`"
                                class="text-blue-600"
                            >Uredi</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </AdminLayout>
</template>
