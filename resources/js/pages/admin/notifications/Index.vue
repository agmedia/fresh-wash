<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    logs: any;
    filters: { search?: string|null; channel?: string|null; status?: string|null };
    channels: string[];
    statuses: string[];
}>();

const search = ref(props.filters.search ?? '');
const channel = ref(props.filters.channel ?? '');
const status = ref(props.filters.status ?? '');

const apply = () => {
    router.get(
        '/admin/notifications',
        { search: search.value || null, channel: channel.value || null, status: status.value || null },
        { preserveState: true, replace: true }
    );
};

const reset = () => {
    search.value = '';
    channel.value = '';
    status.value = '';
    apply();
};
</script>

<template>
    <AdminLayout title="Notification log">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <input
                    v-model="search"
                    @keyup.enter="apply"
                    class="w-72 rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    placeholder="Search (to, subject, template)..."
                />

                <select v-model="channel" @change="apply"
                        class="rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200">
                    <option value="">All channels</option>
                    <option v-for="c in props.channels" :key="c" :value="c">{{ c }}</option>
                </select>

                <select v-model="status" @change="apply"
                        class="rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200">
                    <option value="">All statuses</option>
                    <option v-for="s in props.statuses" :key="s" :value="s">{{ s }}</option>
                </select>

                <button @click="apply" class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-gray-700">
                    Apply
                </button>
                <button @click="reset" class="rounded bg-gray-900 px-3 py-2 text-sm text-gray-300 hover:bg-gray-800">
                    Reset
                </button>
            </div>
        </div>

        <div class="mt-4 overflow-hidden rounded border border-gray-800">
            <table class="min-w-full bg-gray-900 text-sm text-gray-200">
                <thead class="bg-gray-800 text-gray-300">
                <tr>
                    <th class="px-3 py-2 text-left">#</th>
                    <th class="px-3 py-2 text-left">Channel</th>
                    <th class="px-3 py-2 text-left">To</th>
                    <th class="px-3 py-2 text-left">Template</th>
                    <th class="px-3 py-2 text-left">Status</th>
                    <th class="px-3 py-2 text-left">Sent</th>
                    <th class="px-3 py-2 text-right">Action</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="l in props.logs.data" :key="l.id" class="border-t border-gray-800">
                    <td class="px-3 py-2">{{ l.id }}</td>
                    <td class="px-3 py-2">{{ l.channel }}</td>
                    <td class="px-3 py-2">{{ l.to ?? '—' }}</td>
                    <td class="px-3 py-2">{{ l.template ?? '—' }}</td>
                    <td class="px-3 py-2">
              <span class="rounded px-2 py-0.5 text-xs"
                    :class="l.status === 'sent' ? 'bg-green-900 text-green-200'
                      : l.status === 'failed' ? 'bg-red-900 text-red-200'
                      : 'bg-gray-800 text-gray-300'">
                {{ l.status }}
              </span>
                    </td>
                    <td class="px-3 py-2">{{ l.sent_at ?? '—' }}</td>
                    <td class="px-3 py-2 text-right">
                        <Link :href="`/admin/notifications/${l.id}`" class="text-blue-400 hover:underline">Show</Link>
                    </td>
                </tr>

                <tr v-if="!props.logs.data.length">
                    <td colspan="7" class="px-3 py-6 text-center text-gray-400">No logs.</td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>