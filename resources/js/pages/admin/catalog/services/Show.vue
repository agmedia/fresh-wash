<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { computed } from 'vue';

type Service = {
    id: number;
    code: string;
    name: string;
    description?: string | null;
    sort_order: number;
    is_active: boolean;
};

const props = defineProps<{ service: Service }>();

const editHref = computed(() => `/admin/catalog/services/${props.service.id}/edit`);
const backHref = '/admin/catalog/services';

const activeBadgeClass = computed(() =>
    props.service.is_active
        ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300'
        : 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-900/40 dark:bg-rose-950/40 dark:text-rose-300'
);
</script>

<template>
    <AdminLayout :title="`Usluga #${service.id}`">
        <div class="max-w-3xl space-y-6">
            <!-- Header -->
            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div class="min-w-0">
                    <div class="font-mono text-sm text-muted-foreground truncate">
                        {{ service.code || '-' }}
                    </div>
                    <h2 class="text-xl font-semibold text-foreground truncate">
                        {{ service.name || '-' }}
                    </h2>

                    <div class="mt-2 inline-flex items-center gap-2">
                        <span
                            class="inline-flex items-center rounded-full border px-2 py-0.5 text-xs font-medium"
                            :class="activeBadgeClass"
                        >
                            {{ service.is_active ? 'Aktivno' : 'Neaktivno' }}
                        </span>

                        <span class="text-xs text-muted-foreground">
                            Sort order: <span class="font-medium text-foreground">{{ service.sort_order }}</span>
                        </span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a
                        :href="editHref"
                        class="rounded border border-border bg-card px-3 py-2 text-sm hover:bg-muted"
                    >
                        Uredi
                    </a>
                    <a
                        :href="backHref"
                        class="rounded border border-border bg-card px-3 py-2 text-sm hover:bg-muted"
                    >
                        Natrag
                    </a>
                </div>
            </div>

            <!-- Summary card -->
            <div class="rounded border border-border bg-card p-5 card-elev">
                <div class="grid gap-4 text-sm md:grid-cols-2">
                    <div class="rounded border border-border bg-card p-4">
                        <div class="text-xs font-medium text-muted-foreground">Code</div>
                        <div class="mt-1 font-mono text-foreground">
                            {{ service.code || '-' }}
                        </div>
                    </div>

                    <div class="rounded border border-border bg-card p-4">
                        <div class="text-xs font-medium text-muted-foreground">Naziv</div>
                        <div class="mt-1 text-foreground font-medium">
                            {{ service.name || '-' }}
                        </div>
                    </div>

                    <div class="rounded border border-border bg-card p-4 md:col-span-2">
                        <div class="text-xs font-medium text-muted-foreground">Opis</div>
                        <div class="mt-2 whitespace-pre-wrap text-foreground">
                            {{ service.description || '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
