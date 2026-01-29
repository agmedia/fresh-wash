<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    devices: any;
    filters: { search?: string | null };
}>();

const search = ref(props.filters?.search ?? '');

const submitSearch = () => {
    router.get('/admin/devices', { search: search.value }, { preserveState: true, replace: true });
};
</script>

<template>
    <AdminLayout title="Uređaji">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <input v-model="search" @keyup.enter="submitSearch" class="w-72 rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200" placeholder="Pretraži (code, uid, name)..." />
                <button @click="submitSearch" class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-gray-700">Traži</button>
            </div>

            <Link href="/admin/devices/create" class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-500">
                + Novi uređaj
            </Link>
        </div>

        <div class="mt-4 overflow-hidden rounded border border-gray-800">
            <table class="min-w-full bg-gray-900 text-sm text-gray-200">
                <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-3 py-2 text-left">Code</th>
                    <th class="px-3 py-2 text-left">UID</th>
                    <th class="px-3 py-2 text-left">Lokacija</th>
                    <th class="px-3 py-2 text-left">Status</th>
                    <th class="px-3 py-2 text-left">Last seen</th>
                    <th class="px-3 py-2 text-right">Akcije</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="d in props.devices.data" :key="d.id" class="border-t border-gray-800">
                    <td class="px-3 py-2 font-medium">{{ d.code }}</td>
                    <td class="px-3 py-2">{{ d.hardware_uid }}</td>
                    <td class="px-3 py-2">{{ d.location?.name ?? '—' }}</td>
                    <td class="px-3 py-2">
              <span class="rounded px-2 py-0.5 text-xs"
                    :class="d.status === 'online' ? 'bg-green-900 text-green-200' : d.status === 'maintenance' ? 'bg-yellow-900 text-yellow-200' : 'bg-gray-800 text-gray-300'">
                {{ d.status }}
              </span>
                    </td>
                    <td class="px-3 py-2">{{ d.last_seen_at ?? '—' }}</td>
                    <td class="px-3 py-2 text-right">
                        <div class="inline-flex gap-2">
                            <Link :href="`/admin/devices/${d.id}`" class="text-blue-400 hover:underline">Show</Link>
                            <Link :href="`/admin/devices/${d.id}/edit`" class="text-blue-400 hover:underline">Edit</Link>
                        </div>
                    </td>
                </tr>

                <tr v-if="!props.devices.data.length">
                    <td colspan="6" class="px-3 py-6 text-center text-gray-400">Nema uređaja.</td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>