<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    tags: { data: any[]; links?: any[]; meta?: any };
    filters: { search?: string | null };
}>();

const search = ref(props.filters?.search ?? '');

const submitSearch = () => {
    router.get(
        '/admin/page-tags',
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    search.value = '';
    router.get('/admin/page-tags', {}, { preserveState: true, replace: true });
};
</script>

<template>
    <AdminLayout title="Tagovi">
        <!-- Top bar -->
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <!-- Search -->
            <form @submit.prevent="submitSearch" class="flex w-full items-center gap-2 lg:w-auto">
                <div class="relative w-full lg:w-96">
                    <input
                        v-model="search"
                        class="w-full rounded border border-input bg-background px-3 py-2 pr-10 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        placeholder="Pretraži (naziv / slug)..."
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
                href="/admin/page-tags/create"
                class="rounded border border-border bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-muted"
            >
                Novi tag
            </Link>
        </div>

        <!-- Table -->
        <div class="mt-4 overflow-auto rounded border border-border bg-card card-elev">
            <table class="min-w-full text-sm text-foreground">
                <thead class="sticky top-0 z-10 bg-muted/70 backdrop-blur">
                <tr>
                    <th
                        class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground"
                    >
                        Naziv
                    </th>
                    <th
                        class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground"
                    >
                        Slug
                    </th>
                    <th
                        class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground"
                    >
                        Akcije
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="t in props.tags.data"
                    :key="t.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2">
                        <div class="font-medium max-w-[420px] truncate">
                            {{ t.name }}
                        </div>
                        <div class="text-xs text-muted-foreground">#{{ t.id }}</div>
                    </td>

                    <td class="px-3 py-2">
                        <div class="max-w-[420px] truncate text-sm">
                            {{ t.slug }}
                        </div>
                    </td>

                    <td class="px-3 py-2 text-right">
                        <Link
                            :href="`/admin/page-tags/${t.id}/edit`"
                            class="inline-flex items-center rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                        >
                            Uredi
                        </Link>
                    </td>
                </tr>

                <tr v-if="!props.tags.data.length">
                    <td colspan="3" class="px-3 py-10 text-center text-muted-foreground">
                        Nema zapisa.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
