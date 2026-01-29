<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    page: any;
    images: Array<{ id: number; name: string; url: string }>;
}>();
</script>

<template>
    <AdminLayout :title="`Stranica #${page.id}`">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-300">
                <span class="text-gray-100">{{ page.type }}</span>
                <span class="text-gray-500"> • </span>
                <span class="text-gray-100">{{ page.slug }}</span>
            </div>

            <div class="flex gap-2">
                <Link
                    :href="`/admin/pages/${page.id}/edit`"
                    class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-gray-700"
                >
                    Uredi
                </Link>
                <Link href="/admin/pages" class="rounded bg-gray-800 px-3 py-2 text-sm text-gray-200 hover:bg-gray-700">
                    Nazad
                </Link>
            </div>
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div class="rounded border border-gray-800 bg-gray-900 p-4 text-gray-200">
                <div class="text-sm text-gray-400">Naslov</div>
                <div class="mt-1 text-lg font-semibold">{{ page.title }}</div>

                <div class="mt-3 text-sm text-gray-400">Kategorija</div>
                <div class="mt-1">{{ page.category?.name ?? '—' }}</div>

                <div class="mt-3 text-sm text-gray-400">Tagovi</div>
                <div class="mt-1 text-sm">
                    <span v-if="page.tags?.length" class="flex flex-wrap gap-2">
                        <span
                            v-for="t in page.tags"
                            :key="t.id"
                            class="rounded bg-gray-800 px-2 py-0.5 text-xs text-gray-200"
                            >{{ t.name }}</span
                        >
                    </span>
                    <span v-else class="text-gray-400">—</span>
                </div>

                <div class="mt-3 text-sm text-gray-400">Published</div>
                <div class="mt-1">{{ page.published_at ?? '—' }}</div>
            </div>

            <div class="rounded border border-gray-800 bg-gray-900 p-4 text-gray-200">
                <div class="text-sm text-gray-400">SEO</div>
                <div class="mt-2 text-sm"><span class="text-gray-400">Meta title:</span> {{ page.meta_title ?? '—' }}</div>
                <div class="mt-1 text-sm"><span class="text-gray-400">Meta desc:</span> {{ page.meta_description ?? '—' }}</div>
            </div>
        </div>

        <div class="mt-4 rounded border border-gray-800 bg-gray-900 p-4 text-gray-200">
            <div class="text-sm text-gray-400">Sadržaj</div>
            <pre class="mt-2 whitespace-pre-wrap rounded bg-gray-950 p-3 text-sm text-gray-200">{{ page.body ?? '—' }}</pre>
        </div>

        <div v-if="props.images?.length" class="mt-4 rounded border border-gray-800 bg-gray-900 p-4">
            <div class="mb-2 text-sm text-gray-300">Slike</div>
            <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                <a
                    v-for="img in props.images"
                    :key="img.id"
                    :href="img.url"
                    target="_blank"
                    class="block overflow-hidden rounded border border-gray-800"
                >
                    <img :src="img.url" class="h-28 w-full object-cover" />
                </a>
            </div>
        </div>
    </AdminLayout>
</template>
