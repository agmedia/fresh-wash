<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
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
        { search: search.value || undefined, status: status.value || undefined },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    search.value = '';
    status.value = '';
    router.get('/admin/reservations', {}, { preserveState: true, replace: true });
};

const statusClass = (s: string) => {
    switch (s) {
        case 'active':
            return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300';
        case 'draft':
            return 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300';
        case 'ended':
            return 'border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-900/40 dark:bg-indigo-950/40 dark:text-indigo-300';
        case 'cancelled':
            return 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-300';
        default:
            return 'border-border bg-muted text-muted-foreground';
    }
};

const userLabel = (u: ReservationRow['user']) => {
    const full = [u.first_name, u.last_name].filter(Boolean).join(' ').trim();
    return full || u.name || u.email;
};
</script>

<template>
    <AdminLayout title="Rezervacije">
        <!-- Top bar -->
        <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <form @submit.prevent="submit" class="flex w-full flex-col gap-2 sm:flex-row sm:items-center lg:w-auto">
                <div class="relative w-full lg:w-96">
                    <input
                        v-model="search"
                        class="w-full rounded border border-input bg-background px-3 py-2 pr-10 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        placeholder="Traži (user / locker)..."
                    />
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                        ⌕
                    </span>
                </div>

                <select
                    v-model="status"
                    class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring/40 sm:w-48"
                >
                    <option value="">Svi statusi</option>
                    <option value="draft">draft</option>
                    <option value="active">active</option>
                    <option value="ended">ended</option>
                    <option value="cancelled">cancelled</option>
                </select>

                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="rounded border border-border bg-card px-3 py-2 text-sm font-medium text-foreground hover:bg-muted"
                    >
                        Filtriraj
                    </button>

                    <button
                        type="button"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                        @click="resetFilters"
                    >
                        Reset
                    </button>
                </div>
            </form>

            <Link
                href="/admin/reservations/create"
                class="rounded border border-border bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-muted"
            >
                Nova rezervacija
            </Link>
        </div>

        <!-- Table -->
        <div class="overflow-auto rounded border border-border bg-card card-elev">
            <table class="min-w-full text-sm text-foreground">
                <thead class="sticky top-0 z-10 bg-muted/70 backdrop-blur">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        ID
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Status
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        User
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Locker
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Start
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        End
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Akcije
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="r in reservations.data"
                    :key="r.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2 tabular-nums">
                        <div class="font-medium">#{{ r.id }}</div>
                    </td>

                    <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                :class="statusClass(r.status)"
                            >
                                {{ r.status }}
                            </span>
                    </td>

                    <td class="px-3 py-2">
                        <div class="font-medium leading-tight">
                            {{ userLabel(r.user) }}
                        </div>
                        <div class="max-w-[260px] truncate text-xs text-muted-foreground">
                            {{ r.user.email }}
                        </div>
                    </td>

                    <td class="px-3 py-2">
                        <div class="font-medium tabular-nums">
                            {{ r.locker.code }}
                        </div>
                        <div v-if="r.locker.name" class="text-xs text-muted-foreground">
                            {{ r.locker.name }}
                        </div>
                    </td>

                    <td class="px-3 py-2 text-sm text-muted-foreground">
                        {{ r.starts_at }}
                    </td>

                    <td class="px-3 py-2 text-sm text-muted-foreground">
                        {{ r.ends_at ?? '—' }}
                    </td>

                    <td class="px-3 py-2 text-right">
                        <Link
                            :href="`/admin/reservations/${r.id}/edit`"
                            class="inline-flex items-center rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                        >
                            Uredi
                        </Link>
                    </td>
                </tr>

                <tr v-if="!reservations.data.length">
                    <td colspan="7" class="px-3 py-10 text-center text-muted-foreground">
                        Nema rezervacija.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
