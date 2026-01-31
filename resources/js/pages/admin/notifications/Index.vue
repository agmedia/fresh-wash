<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    logs: { data: any[]; links?: any[]; meta?: any };
    filters: { search?: string | null; channel?: string | null; status?: string | null };
    channels: string[];
    statuses: string[];
}>();

const search = ref(props.filters.search ?? '');
const channel = ref(props.filters.channel ?? '');
const status = ref(props.filters.status ?? '');

const apply = () => {
    router.get(
        '/admin/notifications',
        {
            search: search.value || undefined,
            channel: channel.value || undefined,
            status: status.value || undefined,
        },
        { preserveState: true, replace: true },
    );
};

const reset = () => {
    search.value = '';
    channel.value = '';
    status.value = '';
    router.get('/admin/notifications', {}, { preserveState: true, replace: true });
};

const statusClass = (s: string) => {
    switch (s) {
        case 'sent':
            return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300';
        case 'failed':
            return 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-300';
        case 'queued':
        case 'pending':
            return 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/40 dark:text-amber-300';
        default:
            return 'border-border bg-muted text-muted-foreground';
    }
};
</script>

<template>
    <AdminLayout title="Notification log">
        <!-- Filters -->
        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
            <form @submit.prevent="apply" class="flex w-full flex-col gap-2 sm:flex-row sm:items-center lg:w-auto">
                <div class="relative w-full lg:w-96">
                    <input
                        v-model="search"
                        class="w-full rounded border border-input bg-background px-3 py-2 pr-10 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        placeholder="Search (to, subject, template)..."
                    />
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                        ⌕
                    </span>
                </div>

                <select
                    v-model="channel"
                    @change="apply"
                    class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring/40 sm:w-52"
                >
                    <option value="">All channels</option>
                    <option v-for="c in props.channels" :key="c" :value="c">{{ c }}</option>
                </select>

                <select
                    v-model="status"
                    @change="apply"
                    class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring/40 sm:w-44"
                >
                    <option value="">All statuses</option>
                    <option v-for="s in props.statuses" :key="s" :value="s">{{ s }}</option>
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
        </div>

        <!-- Table -->
        <div class="mt-4 overflow-auto rounded border border-border bg-card card-elev">
            <table class="min-w-full text-sm text-foreground">
                <thead class="sticky top-0 z-10 bg-muted/70 backdrop-blur">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        #
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Channel
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        To
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Template
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Status
                    </th>
                    <th class="px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Sent
                    </th>
                    <th class="px-3 py-2 text-right text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                        Action
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="l in props.logs.data"
                    :key="l.id"
                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                >
                    <td class="px-3 py-2 tabular-nums font-medium">
                        #{{ l.id }}
                    </td>

                    <td class="px-3 py-2">
                        {{ l.channel ?? '—' }}
                    </td>

                    <td class="px-3 py-2">
                        <div class="max-w-[360px] truncate font-medium">
                            {{ l.to ?? '—' }}
                        </div>
                    </td>

                    <td class="px-3 py-2">
                        <div class="max-w-[320px] truncate text-sm">
                            {{ l.template ?? '—' }}
                        </div>
                        <div v-if="l.subject" class="max-w-[360px] truncate text-xs text-muted-foreground">
                            {{ l.subject }}
                        </div>
                    </td>

                    <td class="px-3 py-2">
                            <span
                                class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                :class="statusClass(l.status)"
                            >
                                {{ l.status ?? '—' }}
                            </span>
                    </td>

                    <td class="px-3 py-2 text-sm text-muted-foreground">
                        {{ l.sent_at ?? '—' }}
                    </td>

                    <td class="px-3 py-2 text-right">
                        <Link
                            :href="`/admin/notifications/${l.id}`"
                            class="inline-flex items-center rounded border border-border bg-background px-2.5 py-1 text-xs font-medium text-foreground hover:bg-muted"
                        >
                            Pregled
                        </Link>
                    </td>
                </tr>

                <tr v-if="!props.logs.data.length">
                    <td colspan="7" class="px-3 py-10 text-center text-muted-foreground">
                        No logs.
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
