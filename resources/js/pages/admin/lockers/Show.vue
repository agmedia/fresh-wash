<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

interface LocationLite {
    id: number;
    name: string;
}

interface Locker {
    id: number;
    location_id: number;
    code: string;
    name: string | null;
    slug: string | null;
    size: 'S' | 'M' | 'L' | 'XL';
    is_active: boolean;
    is_available: boolean;
    hardware_uid: string | null;
    note: string | null;
    location?: LocationLite;
}

const props = defineProps<{
    locker: Locker;
}>();
</script>

<template>
    <AdminLayout :title="`Ormarić #${locker.id}`">
        <div class="max-w-3xl space-y-6">
            <!-- Header -->
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-foreground">
                        {{ locker.code }}
                        <span v-if="locker.name" class="text-muted-foreground">
                            — {{ locker.name }}
                        </span>
                    </h2>

                    <p v-if="locker.slug" class="mt-1 text-sm text-muted-foreground">
                        Slug:
                        <span class="font-mono">{{ locker.slug }}</span>
                    </p>
                </div>

                <a
                    :href="`/admin/lockers/${locker.id}/edit`"
                    class="inline-flex items-center rounded border border-border bg-background px-3 py-2 text-sm font-medium text-foreground hover:bg-muted"
                >
                    Uredi
                </a>
            </div>

            <!-- Location -->
            <section class="rounded border border-border bg-card p-4">
                <h3
                    class="mb-3 text-xs font-semibold uppercase tracking-wide text-muted-foreground"
                >
                    Lokacija
                </h3>

                <div class="text-sm text-foreground">
                    <span class="text-muted-foreground">Naziv:</span>
                    <span class="ml-1 font-medium">
                        {{ locker.location?.name ?? '—' }}
                    </span>
                </div>
            </section>

            <!-- Status -->
            <section class="rounded border border-border bg-card p-4">
                <h3
                    class="mb-3 text-xs font-semibold uppercase tracking-wide text-muted-foreground"
                >
                    Status
                </h3>

                <div class="grid gap-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-muted-foreground">Aktivan</span>
                        <span
                            class="inline-flex rounded-full border px-2 py-0.5 text-xs font-medium"
                            :class="
                                locker.is_active
                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300'
                                    : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-300'
                            "
                        >
                            {{ locker.is_active ? 'Da' : 'Ne' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-muted-foreground">Dostupan</span>
                        <span
                            class="inline-flex rounded-full border px-2 py-0.5 text-xs font-medium"
                            :class="
                                locker.is_available
                                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300'
                                    : 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300'
                            "
                        >
                            {{ locker.is_available ? 'Da' : 'Ne' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-muted-foreground">Veličina</span>
                        <span class="font-medium tabular-nums">
                            {{ locker.size }}
                        </span>
                    </div>
                </div>
            </section>

            <!-- Hardware -->
            <section class="rounded border border-border bg-card p-4">
                <h3
                    class="mb-3 text-xs font-semibold uppercase tracking-wide text-muted-foreground"
                >
                    Hardware
                </h3>

                <div class="text-sm">
                    <span class="text-muted-foreground">UID:</span>
                    <span class="ml-1 font-mono">
                        {{ locker.hardware_uid ?? '—' }}
                    </span>
                </div>
            </section>

            <!-- Note -->
            <section
                v-if="locker.note"
                class="rounded border border-border bg-card p-4"
            >
                <h3
                    class="mb-3 text-xs font-semibold uppercase tracking-wide text-muted-foreground"
                >
                    Napomena
                </h3>

                <p class="whitespace-pre-line text-sm text-foreground">
                    {{ locker.note }}
                </p>
            </section>
        </div>
    </AdminLayout>
</template>
