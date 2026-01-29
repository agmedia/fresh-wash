<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    page: any;
    categories: Array<{ id: number; name: string }>;
    tags: Array<{ id: number; name: string }>;
    images: Array<{ id: number; name: string; url: string }>;
}>();

const form = useForm({
    category_id: props.page.category_id ?? null,
    title: props.page.title ?? '',
    slug: props.page.slug ?? '',
    body: props.page.body ?? '',
    meta_title: props.page.meta_title ?? '',
    meta_description: props.page.meta_description ?? '',
    type: props.page.type ?? 'page',
    published_at: props.page.published_at ? String(props.page.published_at).slice(0, 16) : '',
    is_active: !!props.page.is_active,
    tag_ids: (props.page.tags ?? []).map((t: any) => t.id) as number[],
    images: [] as File[],
});

const onFiles = (e: Event) => {
    const input = e.target as HTMLInputElement;
    form.images = Array.from(input.files ?? []);
};

const submit = () => form.post(`/admin/pages/${props.page.id}`, { forceFormData: true, _method: 'put' as any });
</script>

<template>
    <AdminLayout :title="`Uredi: ${page.title}`">
        <form @submit.prevent="submit" class="max-w-4xl space-y-4">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm text-gray-400">Type *</label>
                    <select
                        v-model="form.type"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    >
                        <option value="page">page</option>
                        <option value="post">post</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400">Kategorija</label>
                    <select
                        v-model="form.category_id"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    >
                        <option :value="null">—</option>
                        <option v-for="c in props.categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400">Naslov *</label>
                    <input
                        v-model="form.title"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                    <div v-if="form.errors.title" class="mt-1 text-xs text-red-400">{{ form.errors.title }}</div>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400">Slug *</label>
                    <input
                        v-model="form.slug"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                    <div v-if="form.errors.slug" class="mt-1 text-xs text-red-400">{{ form.errors.slug }}</div>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400">Published at</label>
                    <input
                        type="datetime-local"
                        v-model="form.published_at"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                </div>

                <div class="flex items-end">
                    <label class="flex items-center gap-2 text-sm text-gray-200">
                        <input type="checkbox" v-model="form.is_active" />
                        Aktivna
                    </label>
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm text-gray-400">Tagovi</label>
                <select
                    v-model="form.tag_ids"
                    multiple
                    class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                >
                    <option v-for="t in props.tags" :key="t.id" :value="t.id">{{ t.name }}</option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm text-gray-400">Sadržaj</label>
                <textarea
                    v-model="form.body"
                    rows="10"
                    class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                />
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm text-gray-400">Meta title</label>
                    <input
                        v-model="form.meta_title"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                </div>
                <div>
                    <label class="mb-1 block text-sm text-gray-400">Meta description</label>
                    <input
                        v-model="form.meta_description"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm text-gray-400">Dodaj slike</label>
                <input
                    type="file"
                    multiple
                    @change="onFiles"
                    class="block w-full text-sm text-gray-200 file:mr-3 file:rounded file:border-0 file:bg-gray-800 file:px-3 file:py-2 file:text-sm file:text-gray-200 hover:file:bg-gray-700"
                />
            </div>

            <div v-if="props.images?.length" class="rounded border border-gray-800 bg-gray-900 p-4">
                <div class="mb-2 text-sm text-gray-300">Trenutne slike</div>
                <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                    <a
                        v-for="img in props.images"
                        :key="img.id"
                        :href="img.url"
                        target="_blank"
                        class="block overflow-hidden rounded border border-gray-800"
                    >
                        <img :src="img.url" class="h-24 w-full object-cover" />
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-500" :disabled="form.processing">
                    Spremi
                </button>
                <Link :href="`/admin/pages/${page.id}`" class="text-sm text-gray-300 hover:underline">Show</Link>
                <Link href="/admin/pages" class="text-sm text-gray-300 hover:underline">Nazad</Link>
            </div>
        </form>
    </AdminLayout>
</template>
