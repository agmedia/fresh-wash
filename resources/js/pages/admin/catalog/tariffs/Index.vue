<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

type Tariff = {
    id: number;
    price_net: string | number;
    vat_rate: string | number;
    price_gross: string | number;
    currency: string;
    is_active: boolean;
    service?: { id: number; name: string; code: string };
    location?: { id: number; name: string };
};

type Paginator<T> = { data: T[]; links?: any[]; meta?: any };

const props = defineProps<{
    tariffs: Paginator<Tariff>;
    filters: { search?: string | null };
}>();

const search = ref(props.filters?.search ?? '');

const submitSearch = () => {
    router.get(
        '/admin/catalog/tariffs',
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    search.value = '';
    router.get('/admin/catalog/tariffs', {}, { preserveState: true, replace: true });
};

const activeBadgeClass = (isActive: boolean) =>
    isActive
        ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300'
        : 'border-border bg-muted text-muted-foreground';
</script>

<template>
    <AdminLayout title="Tarife">
        <!-- Top bar -->
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <!-- Search -->
            <form @submit.prevent="submitSearch" class="flex w-full items-center gap-2 lg:w-auto">
                <div class="relative w-full lg:w-96">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Pretraži (lokacija / usluga)"
                        class="w-full rounded border border-input bg-background px-3 py-2 pr-10 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
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
                href="/admin/catalog/tariffs/create"
                class="rounded border border-border bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-muted"
            >
                Nova tarifa
            </Link>
        </div>

        <!-- Table -->
        <div class="mt-4 overflow-auto rounded border border-border bg-card card-elev">
            <table class="min-w-full text-sm text-foreground">
                <thead class="sticky top-0 z-10 bg-muted/70 backdrop-blur">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        ID
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Lokacija
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Usluga
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Net
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        PDV
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Bruto
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Aktivno
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Akcije
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="t in tariffs.data"
                    :key="t.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2 tabular-nums font-medium">
                        #{{ t.id }}
                    </td>

                    <td class="px-3 py-2">
                        <div class="max-w-[340px] truncate font-medium">
                            {{ t.location?.name ?? '—' }}
                        </div>
                    </td>

                    <td class="px-3 py-2">
                        <div class="max-w-[360px] truncate font-medium">
                            {{ t.service?.name ?? '—' }}
                        </div>
                        <div v-if="t.service?.code" class="text-xs text-muted-foreground font-mono">
                            {{ t.service.code }}
                        </div>
                    </td>

                    <td class="px-3 py-2 text-right tabular-nums font-medium">
                        {{ t.price_net }} {{ t.currency }}
                    </td>

                    <td class="px-3 py-2 text-right tabular-nums text-muted-foreground">
                        {{ t.vat_rate }}%
                    </td>

                    <td class="px-3 py-2 text-right tabular-nums font-medium">
                        {{ t.price_gross }} {{ t.currency }}
                    </td>

                    <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                :class="activeBadgeClass(!!t.is_active)"
                            >
                                {{ t.is_active ? 'Aktivno' : 'Neaktivno' }}
                            </span>
                    </td>

                    <td class="px-3 py-2 text-right">
                        <div class="inline-flex items-center gap-2">
                            <Link
                                :href="`/admin/catalog/tariffs/${t.id}`"
                                class="rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                            >
                                Pregled
                            </Link>
                            <Link
                                :href="`/admin/catalog/tariffs/${t.id}/edit`"
                                class="rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                            >
                                Uredi
                            </Link>
                        </div>
                    </td>
                </tr>

                <tr v-if="!tariffs.data.length">
                    <td class="px-3 py-10 text-center text-muted-foreground" colspan="8">
                        Nema podataka.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
