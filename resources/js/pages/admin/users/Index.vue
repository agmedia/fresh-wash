<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface UserRow {
    id: number;
    email: string;
    first_name: string | null;
    last_name: string | null;
    roles?: Array<string | { name: string }>;
}

const props = defineProps<{
    users: { data: UserRow[]; links: any[] };
    filters: { search?: string };
}>();

const search = ref(props.filters.search ?? '');

const submit = () => {
    router.get(
        '/admin/users',
        { search: search.value || undefined },
        { preserveState: true, replace: true },
    );
};

const resetFilters = () => {
    search.value = '';
    router.get('/admin/users', {}, { preserveState: true, replace: true });
};

const fullName = (u: UserRow) => {
    const name = [u.first_name, u.last_name].filter(Boolean).join(' ').trim();
    return name || '—';
};

const rolesList = (u: UserRow) =>
    u.roles && u.roles.length
        ? u.roles.map((r) => (typeof r === 'string' ? r : r.name))
        : [];
</script>

<template>
    <AdminLayout title="Korisnici">
        <!-- Top bar -->
        <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <!-- Search -->
            <form @submit.prevent="submit" class="flex w-full items-center gap-2 lg:w-auto">
                <div class="relative w-full lg:w-96">
                    <input
                        v-model="search"
                        class="w-full rounded border border-input bg-background px-3 py-2 pr-10 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        placeholder="Traži (email / ime)..."
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
                href="/admin/users/create"
                class="rounded border border-border bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-muted"
            >
                Novi korisnik
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
                        Email
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Ime
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Uloge
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Akcije
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="u in users.data"
                    :key="u.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2 tabular-nums font-medium">
                        #{{ u.id }}
                    </td>

                    <td class="px-3 py-2">
                        <div class="font-medium">{{ u.email }}</div>
                    </td>

                    <td class="px-3 py-2">
                        {{ fullName(u) }}
                    </td>

                    <td class="px-3 py-2">
                        <div v-if="rolesList(u).length" class="flex flex-wrap gap-1">
                                <span
                                    v-for="r in rolesList(u)"
                                    :key="r"
                                    class="inline-flex items-center rounded-full border border-border bg-muted px-2 py-0.5 text-xs font-medium text-foreground"
                                >
                                    {{ r }}
                                </span>
                        </div>
                        <span v-else class="text-muted-foreground">—</span>
                    </td>

                    <td class="px-3 py-2 text-right">
                        <div class="inline-flex items-center gap-2">
                            <Link
                                :href="`/admin/users/${u.id}`"
                                class="rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                            >
                                Pregled
                            </Link>
                            <Link
                                :href="`/admin/users/${u.id}/edit`"
                                class="rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                            >
                                Uredi
                            </Link>
                        </div>
                    </td>
                </tr>

                <tr v-if="!users.data.length">
                    <td colspan="5" class="px-3 py-10 text-center text-muted-foreground">
                        Nema korisnika.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
