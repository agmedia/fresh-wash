<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Button } from '@/components/ui/button';

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
        { search: search.value },
        { preserveState: true, replace: true },
    );
};
</script>

<template>
    <AdminLayout title="Lokacije">
        <div class="mb-4 flex items-center justify-between gap-3">
            <!-- Search -->
            <form @submit.prevent="submitSearch" class="flex items-center gap-2">
                <input
                    v-model="search"
                    class="w-72 rounded border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                    placeholder="Traži..."
                />
                <button
                    class="rounded border border-border bg-card px-3 py-2 text-sm text-foreground hover:bg-muted"
                >
                    Traži
                </button>
            </form>

            <!-- New -->
            <Link
                href="/admin/locations/create"
                class="rounded border border-border bg-background px-4 py-2 text-sm font-medium text-foreground hover:bg-muted"
            >
                Nova lokacija
            </Link>
        </div>

        <!-- Table -->
        <div class="overflow-auto rounded border border-border bg-card">
            <table class="min-w-full text-sm text-foreground">
                <thead class="bg-muted/60">
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
                        Aktivna
                    </th>
                    <th class="px-3 py-2"></th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="l in locations.data"
                    :key="l.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2 font-medium">
                        {{ l.name }}
                    </td>
                    <td class="px-3 py-2">
                        {{ l.city }}
                    </td>
                    <td class="px-3 py-2 tabular-nums">
                        {{ l.postal_code }}
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
                            class="text-primary hover:underline"
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
