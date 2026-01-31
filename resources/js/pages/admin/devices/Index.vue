<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    devices: { data: any[]; links?: any[]; meta?: any };
    filters: { search?: string | null };
}>();

const search = ref(props.filters?.search ?? '');

const submitSearch = () => {
    router.get(
        '/admin/devices',
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    search.value = '';
    router.get('/admin/devices', {}, { preserveState: true, replace: true });
};

const statusClass = (s: string) => {
    switch (s) {
        case 'online':
            return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300';
        case 'maintenance':
            return 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/40 dark:text-amber-300';
        case 'offline':
        case 'unknown':
        default:
            return 'border-border bg-muted text-muted-foreground';
    }
};

const statusLabel = (s: string) => {
    if (s === 'online') return 'Online';
    if (s === 'maintenance') return 'Maintenance';
    if (s === 'offline') return 'Offline';
    return s || '—';
};
</script>

<template>
    <AdminLayout title="Uređaji">
        <!-- Top bar -->
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <!-- Search -->
            <form @submit.prevent="submitSearch" class="flex w-full items-center gap-2 lg:w-auto">
                <div class="relative w-full lg:w-96">
                    <input
                        v-model="search"
                        class="w-full rounded border border-input bg-background px-3 py-2 pr-10 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        placeholder="Pretraži (code, uid, name)..."
                    />
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                        ⌕
                    </span>
                </div>

                <button
                    type="submit"
                    class="rounded border border-border bg-card px-3 py-2 text-sm font-medium text-foreground hover:bg-muted"
                >
                    Traži
                </button>

                <button
                    type="button"
                    class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                    @click="resetFilters"
                >
                    Reset
                </button>
            </form>

            <!-- New -->
            <Link
                href="/admin/devices/create"
                class="rounded border border-border bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-muted"
            >
                Novi uređaj
            </Link>
        </div>

        <!-- Table -->
        <div class="mt-4 overflow-auto rounded border border-border bg-card card-elev">
            <table class="min-w-full text-sm text-foreground">
                <thead class="sticky top-0 z-10 bg-muted/70 backdrop-blur">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Code
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        UID
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Lokacija
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Status
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Last seen
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Akcije
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="d in props.devices.data"
                    :key="d.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2">
                        <div class="font-medium tabular-nums">{{ d.code }}</div>
                        <div class="text-xs text-muted-foreground">#{{ d.id }}</div>
                    </td>

                    <td class="px-3 py-2">
                        <div class="max-w-[320px] truncate font-mono text-xs tabular-nums text-foreground/90">
                            {{ d.hardware_uid ?? '—' }}
                        </div>
                    </td>

                    <td class="px-3 py-2">
                        <div class="max-w-[360px] truncate">
                            {{ d.location?.name ?? '—' }}
                        </div>
                    </td>

                    <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                :class="statusClass(d.status)"
                            >
                                {{ statusLabel(d.status) }}
                            </span>
                    </td>

                    <td class="px-3 py-2 text-sm text-muted-foreground">
                        {{ d.last_seen_at ?? '—' }}
                    </td>

                    <td class="px-3 py-2 text-right">
                        <div class="inline-flex items-center gap-2">
                            <Link
                                :href="`/admin/devices/${d.id}`"
                                class="rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                            >
                                Pregled
                            </Link>
                            <Link
                                :href="`/admin/devices/${d.id}/edit`"
                                class="rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                            >
                                Uredi
                            </Link>
                        </div>
                    </td>
                </tr>

                <tr v-if="!props.devices.data.length">
                    <td colspan="6" class="px-3 py-10 text-center text-muted-foreground">
                        Nema uređaja.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
