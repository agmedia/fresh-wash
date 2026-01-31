<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    pages: { data: any[]; links?: any[]; meta?: any };
    filters: { search?: string | null; type?: string | null };
}>();

const search = ref(props.filters?.search ?? '');
const type = ref(props.filters?.type ?? '');

const apply = () => {
    router.get(
        '/admin/pages',
        { search: search.value || undefined, type: type.value || undefined },
        { preserveState: true, replace: true },
    );
};

const reset = () => {
    search.value = '';
    type.value = '';
    router.get('/admin/pages', {}, { preserveState: true, replace: true });
};

const typeLabel = (t: string) => (t === 'post' ? 'Blog' : t === 'page' ? 'Stranica' : t);

const typeBadgeClass = (t: string) => {
    switch (t) {
        case 'post':
            return 'border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-900/40 dark:bg-indigo-950/40 dark:text-indigo-300';
        case 'page':
            return 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300';
        default:
            return 'border-border bg-muted text-muted-foreground';
    }
};

const activeBadgeClass = (isActive: boolean) =>
    isActive
        ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300'
        : 'border-border bg-muted text-muted-foreground';
</script>

<template>
    <AdminLayout title="Stranice / Blog">
        <!-- Top bar -->
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <!-- Filters -->
            <form @submit.prevent="apply" class="flex w-full flex-col gap-2 sm:flex-row sm:items-center lg:w-auto">
                <div class="relative w-full lg:w-96">
                    <input
                        v-model="search"
                        class="w-full rounded border border-input bg-background px-3 py-2 pr-10 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        placeholder="Traži (title / slug)..."
                    />
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                        ⌕
                    </span>
                </div>

                <select
                    v-model="type"
                    @change="apply"
                    class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring/40 sm:w-44"
                >
                    <option value="">Sve</option>
                    <option value="page">Stranice</option>
                    <option value="post">Blog</option>
                </select>

                <div class="flex items-center gap-2">
                    <button
                        type="submit"
                        class="rounded border border-border bg-card px-3 py-2 text-sm font-medium text-foreground hover:bg-muted"
                    >
                        Apply
                    </button>

                    <button
                        type="button"
                        class="rounded border border-border bg-background px-3 py-2 text-sm text-foreground hover:bg-muted"
                        @click="reset"
                    >
                        Reset
                    </button>
                </div>
            </form>

            <!-- New -->
            <Link
                href="/admin/pages/create"
                class="rounded border border-border bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-muted"
            >
                Nova
            </Link>
        </div>

        <!-- Table -->
        <div class="mt-4 overflow-auto rounded border border-border bg-card card-elev">
            <table class="min-w-full text-sm text-foreground">
                <thead class="sticky top-0 z-10 bg-muted/70 backdrop-blur">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Naslov
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Type
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Kategorija
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Aktivna
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Published
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Akcije
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="p in props.pages.data"
                    :key="p.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2">
                        <div class="max-w-[520px] truncate font-medium">
                            {{ p.title }}
                        </div>
                        <div class="max-w-[520px] truncate text-xs text-muted-foreground">
                            {{ p.slug }}
                        </div>
                    </td>

                    <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                :class="typeBadgeClass(p.type)"
                            >
                                {{ typeLabel(p.type) }}
                            </span>
                    </td>

                    <td class="px-3 py-2">
                        {{ p.category?.name ?? '—' }}
                    </td>

                    <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                :class="activeBadgeClass(!!p.is_active)"
                            >
                                {{ p.is_active ? 'Aktivna' : 'Neaktivna' }}
                            </span>
                    </td>

                    <td class="px-3 py-2 text-sm text-muted-foreground">
                        {{ p.published_at ?? '—' }}
                    </td>

                    <td class="px-3 py-2 text-right">
                        <div class="inline-flex items-center gap-2">
                            <Link
                                :href="`/admin/pages/${p.id}`"
                                class="rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                            >
                                Pregled
                            </Link>
                            <Link
                                :href="`/admin/pages/${p.id}/edit`"
                                class="rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                            >
                                Uredi
                            </Link>
                        </div>
                    </td>
                </tr>

                <tr v-if="!props.pages.data.length">
                    <td colspan="6" class="px-3 py-10 text-center text-muted-foreground">
                        Nema zapisa.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
