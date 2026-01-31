<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

interface Location {
    id: number;
    name: string;
    city: string | null;
    postal_code: string | null;
    is_active: boolean;
}

const props = defineProps<{
    locations: {
        data: Location[];
        links: any[];
    };
    filters: { search?: string };
}>();

const search = ref(props.filters.search ?? '');

const submitSearch = () => {
    router.get(
        '/admin/locations',
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    search.value = '';
    router.get('/admin/locations', {}, { preserveState: true, replace: true });
};
</script>

<template>
    <AdminLayout title="Lokacije">
        <!-- Top bar -->
        <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <!-- Search -->
            <form @submit.prevent="submitSearch" class="flex w-full items-center gap-2 lg:w-auto">
                <div class="relative w-full lg:w-96">
                    <input
                        v-model="search"
                        class="w-full rounded border border-input bg-background px-3 py-2 pr-10 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        placeholder="Traži (naziv, grad, pošt. broj)..."
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
                href="/admin/locations/create"
                class="rounded border border-border bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-muted"
            >
                Nova lokacija
            </Link>
        </div>

        <!-- Table -->
        <div class="overflow-auto rounded border border-border bg-card card-elev">
            <table class="min-w-full text-sm text-foreground">
                <thead class="sticky top-0 z-10 bg-muted/70 backdrop-blur">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Naziv
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Grad
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Pošt. broj
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Status
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Akcije
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="l in locations.data"
                    :key="l.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2">
                        <div class="max-w-[320px] truncate font-medium">
                            {{ l.name }}
                        </div>
                        <div class="text-xs text-muted-foreground">#{{ l.id }}</div>
                    </td>

                    <td class="px-3 py-2">
                        {{ l.city ?? '-' }}
                    </td>

                    <td class="px-3 py-2 tabular-nums">
                        {{ l.postal_code ?? '-' }}
                    </td>

                    <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                :class="
                                    l.is_active
                                        ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300'
                                        : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-300'
                                "
                            >
                                {{ l.is_active ? 'Aktivna' : 'Neaktivna' }}
                            </span>
                    </td>

                    <td class="px-3 py-2 text-right">
                        <Link
                            :href="`/admin/locations/${l.id}/edit`"
                            class="inline-flex items-center rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                        >
                            Uredi
                        </Link>
                    </td>
                </tr>

                <tr v-if="!locations.data.length">
                    <td colspan="5" class="px-3 py-10 text-center text-muted-foreground">
                        Nema lokacija.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
