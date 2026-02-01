<script setup lang="ts">
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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

const processing = computed(() => form.processing);

const showHref = computed(() => `/admin/pages/${props.page.id}`);
const backHref = '/admin/pages';

const onFiles = (e: Event) => {
    const input = e.target as HTMLInputElement;
    form.images = Array.from(input.files ?? []);
};



const submit = () => {
    form.put(`/admin/pages/${props.page.id}`, {
        forceFormData: true,
        preserveScroll: true,
    });
};

/** UX: prikaz odabranih fileova */
const fileNames = computed(() => form.images?.map((f) => f.name) ?? []);
</script>

<template>
    <AdminLayout :title="`Uredi: ${page.title}`">
        <div class="max-w-4xl space-y-6">
            <!-- Sticky actions -->
            <div class="sticky top-3 z-20">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                    <div class="min-w-0">
                        <div class="truncate text-sm font-semibold text-foreground">
                            {{ form.title || page.title || 'Stranica' }}
                        </div>
                        <div class="truncate text-xs text-muted-foreground">
                            {{ form.type }} · {{ form.slug || '—' }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            @click="submit"
                            class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground
                                   hover:opacity-90 disabled:opacity-60"
                            :disabled="processing"
                        >
                            {{ processing ? 'Spremanje…' : 'Spremi' }}
                        </button>

                        <Link :href="showHref" class="text-sm text-muted-foreground hover:text-foreground">
                            Pregled
                        </Link>

                        <Link :href="backHref" class="text-sm text-muted-foreground hover:text-foreground">
                            Natrag
                        </Link>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="submit"
                class="rounded border border-border bg-card p-5 card-elev space-y-6"
            >
                <!-- Basic -->
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Osnovno</div>
                        <div class="text-xs text-muted-foreground">Tip, kategorija, naslov, slug i objava.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Type *</label>
                            <select
                                v-model="form.type"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            >
                                <option value="page">page</option>
                                <option value="post">post</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Kategorija</label>
                            <select
                                v-model="form.category_id"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            >
                                <option :value="null">—</option>
                                <option v-for="c in categories" :key="c.id" :value="c.id">
                                    {{ c.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.category_id" class="text-xs text-rose-600">
                                {{ form.errors.category_id }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Naslov *</label>
                            <input
                                v-model="form.title"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="Naslov..."
                            />
                            <p v-if="form.errors.title" class="text-xs text-rose-600">
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Slug *</label>
                            <input
                                v-model="form.slug"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="npr. moj-post"
                            />
                            <p v-if="form.errors.slug" class="text-xs text-rose-600">
                                {{ form.errors.slug }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Published at</label>
                            <input
                                type="datetime-local"
                                v-model="form.published_at"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.published_at" class="text-xs text-rose-600">
                                {{ form.errors.published_at }}
                            </p>
                        </div>

                        <div class="flex items-end">
                            <label class="inline-flex items-center gap-2 text-sm text-foreground">
                                <input
                                    type="checkbox"
                                    v-model="form.is_active"
                                    class="h-4 w-4 rounded border border-input bg-background"
                                />
                                Aktivna
                            </label>
                            <p v-if="form.errors.is_active" class="ml-3 text-xs text-rose-600">
                                {{ form.errors.is_active }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Tags -->
                <section class="space-y-3">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Tagovi</div>
                        <div class="text-xs text-muted-foreground">Odaberi više tagova (CTRL/CMD + klik).</div>
                    </div>

                    <select
                        v-model="form.tag_ids"
                        multiple
                        class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                               focus:outline-none focus:ring-2 focus:ring-ring/40"
                        style="min-height: 140px"
                    >
                        <option v-for="t in tags" :key="t.id" :value="t.id">
                            {{ t.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.tag_ids" class="text-xs text-rose-600">
                        {{ form.errors.tag_ids }}
                    </p>
                </section>

                <!-- Body -->
                <section class="space-y-3">

                    <!-- Body -->

                        <div>
                            <div class="text-sm font-semibold text-foreground">Sadržaj</div>
                            <div class="text-xs text-muted-foreground">Glavni tekst stranice/posta.</div>
                        </div>

                        <div class="rounded border border-input bg-background">
                            <QuillEditor
                                v-model:content="form.body"
                                contentType="html"
                                theme="snow"
                                class="quill-editor"
                                :toolbar="[
                ['bold', 'italic', 'underline', 'strike'],
                [{ header: [2, 3, false] }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['link', 'blockquote', 'code-block'],
                ['clean'],
            ]"
                            />
                        </div>

                        <p v-if="form.errors.body" class="text-xs text-rose-600">
                            {{ form.errors.body }}
                        </p>


                    <p v-if="form.errors.body" class="text-xs text-rose-600">
                        {{ form.errors.body }}
                    </p>
                </section>

                <!-- SEO -->
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">SEO</div>
                        <div class="text-xs text-muted-foreground">Meta title i description.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Meta title</label>
                            <input
                                v-model="form.meta_title"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="Meta title..."
                            />
                            <p v-if="form.errors.meta_title" class="text-xs text-rose-600">
                                {{ form.errors.meta_title }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Meta description</label>
                            <input
                                v-model="form.meta_description"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="Meta description..."
                            />
                            <p v-if="form.errors.meta_description" class="text-xs text-rose-600">
                                {{ form.errors.meta_description }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Images -->
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Slike</div>
                        <div class="text-xs text-muted-foreground">Dodaj nove slike (upload) + pregled trenutnih.</div>
                    </div>

                    <div class="rounded border border-border bg-card p-4">
                        <label class="text-sm font-medium text-foreground">Dodaj slike</label>

                        <input
                            type="file"
                            multiple
                            @change="onFiles"
                            class="mt-2 block w-full text-sm text-foreground
                                   file:mr-3 file:rounded file:border file:border-border file:bg-card file:px-3 file:py-2
                                   file:text-sm file:font-medium file:text-foreground hover:file:bg-muted"
                        />

                        <div v-if="fileNames.length" class="mt-3 text-xs text-muted-foreground">
                            Odabrano: <span class="text-foreground">{{ fileNames.join(', ') }}</span>
                        </div>

                        <p v-if="form.errors.images" class="mt-2 text-xs text-rose-600">
                            {{ form.errors.images }}
                        </p>
                    </div>

                    <div v-if="images?.length" class="rounded border border-border bg-card p-4">
                        <div class="mb-2 text-sm font-medium text-foreground">Trenutne slike</div>
                        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                            <a
                                v-for="img in images"
                                :key="img.id"
                                :href="img.url"
                                target="_blank"
                                class="group block overflow-hidden rounded border border-border bg-muted"
                                :title="img.name"
                            >
                                <img :src="img.url" class="h-24 w-full object-cover transition group-hover:scale-[1.02]" />
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Bottom actions -->
                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground
                               hover:opacity-90 disabled:opacity-60"
                        :disabled="processing"
                    >
                        {{ processing ? 'Spremanje…' : 'Spremi promjene' }}
                    </button>

                    <Link :href="showHref" class="text-sm text-muted-foreground hover:text-foreground">
                        Pregled
                    </Link>

                    <Link :href="backHref" class="text-sm text-muted-foreground hover:text-foreground">
                        Natrag
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<style scoped>
.quill-editor :deep(.ql-toolbar) {
    border: 0;
    border-bottom: 1px solid hsl(var(--border));
    background: hsl(var(--card));
}

.quill-editor :deep(.ql-container) {
    border: 0;
    background: hsl(var(--background));
}

.quill-editor :deep(.ql-editor) {
    min-height: 320px; /* umjesto rows=12 */
    color: hsl(var(--foreground));
}

.quill-editor :deep(.ql-editor.ql-blank::before) {
    color: hsl(var(--muted-foreground));
    opacity: 0.8;
}
</style>

