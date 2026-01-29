<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{ device: any; heartbeats: any[] }>();
</script>

<template>
    <AdminLayout :title="`Uređaj ${device.code}`">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-300">
                UID:
                <span class="text-gray-100">{{ device.hardware_uid }}</span>
            </div>
            <div class="flex gap-2">
                <Link
                    :href="`/admin/devices/${device.id}/edit`"
                    class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-gray-700"
                    >Uredi</Link
                >
                <Link
                    href="/admin/devices"
                    class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-gray-700"
                    >Nazad</Link
                >
            </div>
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div
                class="rounded border border-gray-800 bg-gray-900 p-4 text-gray-200"
            >
                <div class="text-sm text-gray-400">Lokacija</div>
                <div class="mt-1">{{ device.location?.name ?? '—' }}</div>

                <div class="mt-3 text-sm text-gray-400">Status</div>
                <div class="mt-1">{{ device.status }}</div>

                <div class="mt-3 text-sm text-gray-400">Firmware</div>
                <div class="mt-1">{{ device.firmware_version ?? '—' }}</div>

                <div class="mt-3 text-sm text-gray-400">Last seen</div>
                <div class="mt-1">{{ device.last_seen_at ?? '—' }}</div>
            </div>

            <div
                class="rounded border border-gray-800 bg-gray-900 p-4 text-gray-200"
            >
                <div class="text-sm text-gray-400">IP / MAC</div>
                <div class="mt-1">
                    {{ device.ip_address ?? '—' }} /
                    {{ device.mac_address ?? '—' }}
                </div>

                <div class="mt-3 text-sm text-gray-400">Napomena</div>
                <div class="mt-1 text-sm whitespace-pre-wrap text-gray-200">
                    {{ device.note ?? '—' }}
                </div>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded border border-gray-800">
            <div class="bg-gray-800 px-3 py-2 text-sm text-gray-200">
                Zadnjih 25 heartbeatova
            </div>
            <table class="min-w-full bg-gray-900 text-sm text-gray-200">
                <thead class="bg-gray-800 text-gray-300">
                    <tr>
                        <th class="px-3 py-2 text-left">Reported</th>
                        <th class="px-3 py-2 text-left">Firmware</th>
                        <th class="px-3 py-2 text-left">RSSI</th>
                        <th class="px-3 py-2 text-left">Uptime</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="h in props.heartbeats"
                        :key="h.id"
                        class="border-t border-gray-800"
                    >
                        <td class="px-3 py-2">{{ h.reported_at }}</td>
                        <td class="px-3 py-2">
                            {{ h.firmware_version ?? '—' }}
                        </td>
                        <td class="px-3 py-2">{{ h.rssi ?? '—' }}</td>
                        <td class="px-3 py-2">{{ h.uptime_sec ?? '—' }}</td>
                    </tr>
                    <tr v-if="!props.heartbeats.length">
                        <td
                            colspan="4"
                            class="px-3 py-6 text-center text-gray-400"
                        >
                            Nema heartbeatova.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
