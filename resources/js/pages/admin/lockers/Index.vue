<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    lockers: { data: any[] };
    filters: { search?: string | null };
}>();

const search = ref(props.filters?.search ?? '');

const submitSearch = () => {
    router.get(
        '/admin/lockers',
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    search.value = '';
    router.get('/admin/lockers', {}, { preserveState: true, replace: true });
};

const statusClass = (status: string) => {
    switch (status) {
        case 'online':
            return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300';
        case 'maintenance':
            return 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/40 dark:text-amber-300';
        default:
            return 'border-border bg-muted text-muted-foreground';
    }
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
                        placeholder="Pretraži (code, UID, naziv)..."
                        class="w-full rounded border border-input bg-background px-3 py-2 pr-10 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                    />
                    <span
                        class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground"
                    >
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
                href="/admin/lockers/create"
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
                    v-for="l in props.lockers.data"
                    :key="l.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2 font-medium tabular-nums">
                        {{ l.code }}
                    </td>

                    <td class="px-3 py-2 tabular-nums">
                        {{ l.hardware_uid }}
                    </td>

                    <td class="px-3 py-2">
                        {{ l.location?.name ?? '—' }}
                    </td>

                    <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                :class="statusClass(l.status)"
                            >
                                {{ l.status }}
                            </span>
                    </td>

                    <td class="px-3 py-2 text-sm text-muted-foreground">
                        {{ l.last_seen_at ?? '—' }}
                    </td>

                    <td class="px-3 py-2 text-right">
                        <div class="inline-flex items-center gap-2">
                            <Link
                                :href="`/admin/lockers/${l.id}`"
                                class="rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                            >
                                Pregled
                            </Link>
                            <Link
                                :href="`/admin/lockers/${l.id}/edit`"
                                class="rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                            >
                                Uredi
                            </Link>
                        </div>
                    </td>
                </tr>

                <tr v-if="!props.lockers.data.length">
                    <td colspan="6" class="px-3 py-10 text-center text-muted-foreground">
                        Nema uređaja.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
