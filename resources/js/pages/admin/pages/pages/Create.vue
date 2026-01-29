<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    categories: Array<{ id: number; name: string }>;
    tags: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    category_id: null as number | null,
    title: '',
    slug: '',
    body: '',
    meta_title: '',
    meta_description: '',
    type: 'page',
    published_at: '',
    is_active: true,
    tag_ids: [] as number[],
    images: [] as File[],
});

const onFiles = (e: Event) => {
    const input = e.target as HTMLInputElement;
    form.images = Array.from(input.files ?? []);
};

const submit = () => form.post('/admin/pages', { forceFormData: true });
</script>

<template>
    <AdminLayout title="Nova stranica">
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
                    <div v-if="form.errors.type" class="mt-1 text-xs text-red-400">{{ form.errors.type }}</div>
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
                    <div v-if="form.errors.published_at" class="mt-1 text-xs text-red-400">{{ form.errors.published_at }}</div>
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
                <div class="mt-1 text-xs text-gray-400">Tip: drži Ctrl/⌘ za multi-select.</div>
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
                <label class="mb-1 block text-sm text-gray-400">Slike (MediaLibrary)</label>
                <input
                    type="file"
                    multiple
                    @change="onFiles"
                    class="block w-full text-sm text-gray-200 file:mr-3 file:rounded file:border-0 file:bg-gray-800 file:px-3 file:py-2 file:text-sm file:text-gray-200 hover:file:bg-gray-700"
                />
                <div v-if="form.errors.images" class="mt-1 text-xs text-red-400">{{ form.errors.images }}</div>
            </div>

            <div class="flex items-center gap-3">
                <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-500" :disabled="form.processing">
                    Spremi
                </button>
                <Link href="/admin/pages" class="text-sm text-gray-300 hover:underline">Nazad</Link>
            </div>
        </form>
    </AdminLayout>
</template>
