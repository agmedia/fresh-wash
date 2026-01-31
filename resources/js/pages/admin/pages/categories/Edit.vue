<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

// 1) Quill
import { QuillEditor } from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const props = defineProps<{ category: any }>();

const form = useForm({
    name: props.category.name ?? '',
    slug: props.category.slug ?? '',
    description: props.category.description ?? '', // HTML string
    is_active: !!props.category.is_active,
});

const processing = computed(() => form.processing);

const showHref = computed(() => `/admin/page-categories/${props.category.id}`);
const backHref = '/admin/page-categories';

const submit = () => {
    form.put(`/admin/page-categories/${props.category.id}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout :title="`Uredi kategoriju #${category.id}`">
        <div class="max-w-4xl space-y-6">
            <!-- Sticky actions -->
            <div class="sticky top-3 z-20">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                    <div class="min-w-0">
                        <div class="truncate text-sm font-semibold text-foreground">
                            {{ form.name || category.name || 'Kategorija' }}
                        </div>
                        <div class="truncate text-xs text-muted-foreground">
                            {{ form.slug || '—' }} · #{{ category.id }}
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
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Osnovno</div>
                        <div class="text-xs text-muted-foreground">Naziv, slug, opis i status.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Naziv *</label>
                            <input
                                v-model="form.name"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="Naziv kategorije..."
                            />
                            <p v-if="form.errors.name" class="text-xs text-rose-600">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Slug *</label>
                            <input
                                v-model="form.slug"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="npr. moja-kategorija"
                            />
                            <p v-if="form.errors.slug" class="text-xs text-rose-600">
                                {{ form.errors.slug }}
                            </p>
                        </div>

                        <!-- 2) Opis kao editor -->
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-medium text-foreground">Opis</label>

                            <div class="rounded border border-input bg-background">
                                <QuillEditor
                                    v-model:content="form.description"
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

                            <p v-if="form.errors.description" class="text-xs text-rose-600">
                                {{ form.errors.description }}
                            </p>

                            <p class="text-xs text-muted-foreground">
                                Sprema se kao HTML u <code>description</code>.
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
/* malo “u istom stilu” + da editor ne izgleda kao bijeli strani element */
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
    min-height: 140px;
    color: hsl(var(--foreground));
}
</style>
