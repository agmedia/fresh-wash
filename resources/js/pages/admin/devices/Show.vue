<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    device: any;
    heartbeats: any[];
}>();

const statusClass = computed(() => {
    const s = String(props.device.status ?? '').toLowerCase();
    if (s === 'online')
        return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300';
    if (s === 'maintenance')
        return 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/40 dark:text-amber-300';
    if (s === 'offline')
        return 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300';
    return 'border-border bg-muted text-muted-foreground';
});

const editHref = `/admin/devices/${props.device.id}/edit`;
const backHref = '/admin/devices';
</script>

<template>
    <AdminLayout :title="`Uređaj ${device.code}`">
        <div class="max-w-4xl space-y-6">
            <!-- Sticky header -->
            <div class="sticky top-3 z-20">
                <div
                    class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev"
                >
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-foreground truncate">
                            {{ device.code }}
                        </div>
                        <div class="text-xs text-muted-foreground">
                            UID: {{ device.hardware_uid }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span
                            class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                            :class="statusClass"
                        >
                            {{ device.status }}
                        </span>

                        <Link
                            :href="editHref"
                            class="rounded border border-border bg-card px-3 py-2 text-sm hover:bg-muted"
                        >
                            Uredi
                        </Link>

                        <a
                            :href="backHref"
                            class="text-sm text-muted-foreground hover:text-foreground"
                        >
                            Natrag
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info cards -->
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded border border-border bg-card p-5 card-elev">
                    <div class="grid gap-3 text-sm">
                        <div>
                            <div class="text-xs font-medium text-muted-foreground">
                                Lokacija
                            </div>
                            <div class="mt-1 text-foreground">
                                {{ device.location?.name ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-medium text-muted-foreground">
                                Firmware
                            </div>
                            <div class="mt-1 text-foreground">
                                {{ device.firmware_version ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-medium text-muted-foreground">
                                Last seen
                            </div>
                            <div class="mt-1 text-foreground">
                                {{ device.last_seen_at ?? '—' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded border border-border bg-card p-5 card-elev">
                    <div class="grid gap-3 text-sm">
                        <div>
                            <div class="text-xs font-medium text-muted-foreground">
                                IP / MAC
                            </div>
                            <div class="mt-1 text-foreground font-mono text-xs">
                                {{ device.ip_address ?? '—' }} /
                                {{ device.mac_address ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-medium text-muted-foreground">
                                Napomena
                            </div>
                            <div class="mt-1 whitespace-pre-wrap text-foreground">
                                {{ device.note ?? '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Heartbeats -->
            <div class="rounded border border-border bg-card card-elev overflow-hidden">
                <div class="border-b border-border px-4 py-3 text-sm font-medium text-foreground">
                    Zadnjih 25 heartbeatova
                </div>

                <div class="overflow-auto">
                    <table class="min-w-full text-sm text-foreground">
                        <thead class="bg-muted/60">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-muted-foreground">
                                Reported
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-muted-foreground">
                                Firmware
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-muted-foreground">
                                RSSI
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-muted-foreground">
                                Uptime
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="h in heartbeats"
                            :key="h.id"
                            class="border-t border-border/60 even:bg-muted/30"
                        >
                            <td class="px-3 py-2 tabular-nums">
                                {{ h.reported_at }}
                            </td>
                            <td class="px-3 py-2">
                                {{ h.firmware_version ?? '—' }}
                            </td>
                            <td class="px-3 py-2">
                                {{ h.rssi ?? '—' }}
                            </td>
                            <td class="px-3 py-2 tabular-nums">
                                {{ h.uptime_sec ?? '—' }}
                            </td>
                        </tr>

                        <tr v-if="!heartbeats.length">
                            <td
                                colspan="4"
                                class="px-3 py-8 text-center text-muted-foreground"
                            >
                                Nema heartbeatova.
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
