<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

type NotificationLog = {
    id: number;
    channel?: string | null;
    status?: string | null;
    to?: string | null;
    subject?: string | null;
    template?: string | null;
    provider?: string | null;
    user?: { email?: string | null } | null;
    sent_at?: string | null;
    error_message?: string | null;
    payload?: any;
    message?: string | null;
};

const props = withDefaults(defineProps<{ log?: NotificationLog | null }>(), {
    log: null,
});

const log = computed(() => props.log);

const title = computed(() => (log.value ? `Notification #${log.value.id}` : 'Notification'));
const backHref = '/admin/notifications';

const pretty = (v: any) => {
    try {
        if (v == null) return '-';
        if (typeof v === 'string') return v;
        return JSON.stringify(v, null, 2);
    } catch {
        return String(v ?? '');
    }
};

const statusBadgeClass = computed(() => {
    const s = String(log.value?.status ?? '').toLowerCase();
    if (['sent', 'succeeded', 'success', 'delivered'].includes(s)) {
        return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300';
    }
    if (['queued', 'pending', 'processing'].includes(s)) {
        return 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-900/40 dark:bg-amber-950/40 dark:text-amber-300';
    }
    if (['failed', 'error', 'bounced'].includes(s)) {
        return 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-200';
    }
    return 'border-border bg-muted text-muted-foreground';
});
</script>

<template>
    <AdminLayout :title="title.value">
        <div class="max-w-5xl space-y-6">
            <!-- Fallback -->
            <div v-if="!log" class="rounded border border-border bg-card p-5 card-elev">
                <div class="text-sm font-medium text-foreground">Log nije učitan</div>
                <div class="mt-1 text-sm text-muted-foreground">(log prop nedostaje ili je null)</div>
                <div class="mt-4">
                    <a :href="backHref" class="text-sm text-muted-foreground hover:text-foreground">Natrag</a>
                </div>
            </div>

            <!-- Content -->
            <div v-else class="space-y-6">
                <!-- Sticky header -->
                <div class="sticky top-3 z-20">
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                        <div class="min-w-0">
                            <div class="text-sm font-semibold text-foreground truncate">
                                Notification #{{ log.id }}
                            </div>

                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                <span class="text-xs text-muted-foreground">
                                    {{ log.channel ?? '-' }}
                                </span>

                                <span
                                    class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                                    :class="statusBadgeClass"
                                >
                                    {{ log.status ?? '-' }}
                                </span>

                                <span class="text-xs text-muted-foreground">
                                    {{ log.sent_at ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <Link
                                :href="backHref"
                                class="rounded border border-border bg-card px-3 py-2 text-sm hover:bg-muted"
                            >
                                Natrag
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Cards -->
                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Meta -->
                    <div class="rounded border border-border bg-card p-5 card-elev">
                        <div class="mb-3">
                            <div class="text-sm font-semibold text-foreground">Detalji</div>
                            <div class="text-xs text-muted-foreground">Osnovne informacije o poruci.</div>
                        </div>

                        <div class="grid gap-2 text-sm">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-medium text-muted-foreground">To</span>
                                <span class="text-foreground break-all">{{ log.to ?? '—' }}</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-medium text-muted-foreground">Subject</span>
                                <span class="text-foreground break-words">{{ log.subject ?? '—' }}</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-medium text-muted-foreground">Template</span>
                                <span class="text-foreground">{{ log.template ?? '—' }}</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-medium text-muted-foreground">Provider</span>
                                <span class="text-foreground">{{ log.provider ?? '—' }}</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-medium text-muted-foreground">User</span>
                                <span class="text-foreground">{{ log.user?.email ?? '—' }}</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-medium text-muted-foreground">Sent at</span>
                                <span class="text-foreground">{{ log.sent_at ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Error -->
                    <div class="rounded border border-border bg-card p-5 card-elev">
                        <div class="mb-3">
                            <div class="text-sm font-semibold text-foreground">Error</div>
                            <div class="text-xs text-muted-foreground">Detalji greške (ako postoji).</div>
                        </div>

                        <pre class="overflow-auto rounded border border-border bg-muted p-3 text-xs whitespace-pre-wrap">{{ log.error_message ?? '—' }}</pre>
                    </div>
                </div>

                <!-- Payload + Message -->
                <div class="rounded border border-border bg-card p-5 card-elev space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Payload</div>
                        <div class="text-xs text-muted-foreground">Sirovi payload provider-a.</div>
                        <pre class="mt-2 overflow-auto rounded border border-border bg-muted p-3 text-xs">{{ pretty(log.payload) }}</pre>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-foreground">Message</div>
                        <div class="text-xs text-muted-foreground">Sadržaj poruke.</div>
                        <pre class="mt-2 rounded border border-border bg-muted p-3 text-xs whitespace-pre-wrap">{{ log.message ?? '—' }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
