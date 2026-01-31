<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({
    name: '',
    slug: '',
});

const processing = computed(() => form.processing);

const backHref = '/admin/page-tags';

const submit = () => {
    form.post('/admin/page-tags', {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout title="Novi tag">
        <div class="max-w-4xl space-y-6">
            <!-- Sticky actions -->
            <div class="sticky top-3 z-20">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                    <div class="min-w-0">
                        <div class="truncate text-sm font-semibold text-foreground">
                            {{ form.name || 'Novi tag' }}
                        </div>
                        <div class="truncate text-xs text-muted-foreground">
                            {{ form.slug || '—' }}
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
                        <div class="text-xs text-muted-foreground">Naziv i slug taga.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Naziv *</label>
                            <input
                                v-model="form.name"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="Naziv taga..."
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
                                placeholder="npr. moj-tag"
                            />
                            <p v-if="form.errors.slug" class="text-xs text-rose-600">
                                {{ form.errors.slug }}
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
                        {{ processing ? 'Spremanje…' : 'Spremi tag' }}
                    </button>

                    <Link :href="backHref" class="text-sm text-muted-foreground hover:text-foreground">
                        Natrag
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
