<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

type ImageItem = { id: number; name: string; url: string };

const props = withDefaults(
    defineProps<{
        page?: any | null;
        images?: ImageItem[];
    }>(),
    { page: null, images: () => [] }
);

const p = computed(() => props.page);

const title = computed(() => (p.value ? `Stranica #${p.value.id}` : 'Stranica'));

const backHref = '/admin/pages';
const editHref = computed(() => (p.value ? `/admin/pages/${p.value.id}/edit` : backHref));

const typeLabel = computed(() => p.value?.type ?? '-');
const slugLabel = computed(() => p.value?.slug ?? '-');

const tagList = computed(() => (Array.isArray(p.value?.tags) ? p.value.tags : []));

const publishedLabel = computed(() => p.value?.published_at ?? '-');

const isActive = computed(() => {
    const v = p.value?.is_active;
    if (v === undefined || v === null) return null;
    return !!v;
});

const activeBadgeClass = computed(() => {
    if (isActive.value === true)
        return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-300';
    if (isActive.value === false)
        return 'border-slate-200 bg-slate-50 text-slate-700 dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-300';
    return 'border-border bg-muted text-muted-foreground';
});

const bodyText = computed(() => p.value?.body ?? '-');
</script>

<template>
    <AdminLayout :title="title.value">
        <div class="max-w-5xl space-y-4">
            <!-- Fallback -->
            <div
                v-if="!p"
                class="rounded border border-border bg-card p-5 card-elev"
            >
                <div class="text-sm font-medium text-foreground">
                    Stranica nije učitana
                </div>
                <div class="mt-1 text-sm text-muted-foreground">
                    (page prop nedostaje ili je null)
                </div>

                <div class="mt-4">
                    <Link
                        :href="backHref"
                        class="text-sm text-muted-foreground hover:text-foreground"
                    >
                        Natrag
                    </Link>
                </div>
            </div>

            <!-- Content -->
            <div v-else class="space-y-4">
                <!-- Header -->
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div class="w-full rounded border border-border bg-card p-5 card-elev">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="truncate text-sm font-semibold text-foreground">
                                    {{ p.title ?? '-' }}
                                </div>

                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
                                    <span class="inline-flex items-center rounded-full border px-2 py-0.5"
                                          :class="activeBadgeClass">
                                        {{ isActive === null ? '—' : (isActive ? 'aktivna' : 'neaktivna') }}
                                    </span>

                                    <span class="rounded border border-border bg-muted px-2 py-0.5">
                                        {{ typeLabel }}
                                    </span>

                                    <span class="truncate">
                                        / {{ slugLabel }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <Link
                                    :href="editHref.value"
                                    class="rounded border border-border bg-card px-3 py-2 text-sm hover:bg-muted"
                                >
                                    Uredi
                                </Link>
                                <Link
                                    :href="backHref"
                                    class="rounded border border-border bg-card px-3 py-2 text-sm hover:bg-muted"
                                >
                                    Natrag
                                </Link>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 text-sm md:grid-cols-2">
                            <!-- Meta -->
                            <div class="rounded border border-border bg-card p-4">
                                <div class="grid gap-2">
                                    <div>
                                        <div class="text-xs font-medium text-muted-foreground">Kategorija</div>
                                        <div class="mt-1 text-foreground">
                                            {{ p.category?.name ?? '—' }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-xs font-medium text-muted-foreground">Published</div>
                                        <div class="mt-1 text-foreground">
                                            {{ publishedLabel }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-xs font-medium text-muted-foreground">Tagovi</div>
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <span v-if="tagList.length === 0" class="text-foreground">—</span>
                                            <span
                                                v-for="t in tagList"
                                                :key="t.id"
                                                class="inline-flex items-center rounded-full border border-border bg-muted px-2 py-0.5 text-xs text-foreground"
                                            >
                                                {{ t.name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO -->
                            <div class="rounded border border-border bg-card p-4">
                                <div class="text-xs font-medium text-muted-foreground">SEO</div>

                                <div class="mt-3">
                                    <div class="text-xs font-medium text-muted-foreground">Meta title</div>
                                    <div class="mt-1 text-foreground break-words">
                                        {{ p.meta_title ?? '—' }}
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="text-xs font-medium text-muted-foreground">Meta description</div>
                                    <div class="mt-1 text-foreground break-words">
                                        {{ p.meta_description ?? '—' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="rounded border border-border bg-card p-5 card-elev">
                    <div class="mb-2 text-sm font-medium text-foreground">Sadržaj</div>
                    <div class="rounded border border-border bg-muted p-4">
                        <pre class="whitespace-pre-wrap text-sm text-foreground">{{ bodyText }}</pre>
                    </div>
                </div>

                <!-- Images -->
                <div v-if="props.images?.length" class="rounded border border-border bg-card p-5 card-elev">
                    <div class="mb-3 text-sm font-medium text-foreground">Slike</div>
                    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                        <a
                            v-for="img in props.images"
                            :key="img.id"
                            :href="img.url"
                            target="_blank"
                            class="group block overflow-hidden rounded border border-border bg-muted"
                            :title="img.name"
                        >
                            <img
                                :src="img.url"
                                class="h-28 w-full object-cover transition group-hover:scale-[1.02]"
                                :alt="img.name"
                            />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
