<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{ log: any }>();

const pretty = (v: any) => {
    try {
        return JSON.stringify(v, null, 2);
    } catch {
        return String(v ?? '');
    }
};
</script>

<template>
    <AdminLayout :title="`Notification #${log.id}`">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-300">
                <span class="text-gray-100">{{ log.channel }}</span>
                <span class="text-gray-500"> • </span>
                <span class="text-gray-100">{{ log.status }}</span>
            </div>

            <Link
                href="/admin/notifications"
                class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-gray-700"
            >
                Nazad
            </Link>
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div
                class="rounded border border-gray-800 bg-gray-900 p-4 text-gray-200"
            >
                <div class="text-sm text-gray-400">To</div>
                <div class="mt-1">{{ log.to ?? '—' }}</div>

                <div class="mt-3 text-sm text-gray-400">Subject</div>
                <div class="mt-1">{{ log.subject ?? '—' }}</div>

                <div class="mt-3 text-sm text-gray-400">Template</div>
                <div class="mt-1">{{ log.template ?? '—' }}</div>

                <div class="mt-3 text-sm text-gray-400">Provider</div>
                <div class="mt-1">{{ log.provider ?? '—' }}</div>

                <div class="mt-3 text-sm text-gray-400">User</div>
                <div class="mt-1">{{ log.user?.email ?? '—' }}</div>

                <div class="mt-3 text-sm text-gray-400">Sent at</div>
                <div class="mt-1">{{ log.sent_at ?? '—' }}</div>
            </div>

            <div
                class="rounded border border-gray-800 bg-gray-900 p-4 text-gray-200"
            >
                <div class="text-sm text-gray-400">Error</div>
                <pre
                    class="mt-2 rounded bg-gray-950 p-3 text-xs whitespace-pre-wrap text-gray-200"
                    >{{ log.error_message ?? '—' }}</pre
                >
            </div>
        </div>

        <div
            class="mt-4 rounded border border-gray-800 bg-gray-900 p-4 text-gray-200"
        >
            <div class="text-sm text-gray-400">Payload</div>
            <pre
                class="mt-2 overflow-auto rounded bg-gray-950 p-3 text-xs text-gray-200"
                >{{ pretty(log.payload) }}</pre
            >

            <div class="mt-4 text-sm text-gray-400">Message</div>
            <pre
                class="mt-2 rounded bg-gray-950 p-3 text-xs whitespace-pre-wrap text-gray-200"
                >{{ log.message ?? '—' }}</pre
            >
        </div>
    </AdminLayout>
</template>
