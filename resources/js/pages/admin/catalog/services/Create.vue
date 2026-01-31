<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({
    code: '',
    name: '',
    description: '',
    sort_order: 0,
    is_active: true,
});

const processing = computed(() => form.processing);

const submit = () => {
    form.post('/admin/catalog/services', { preserveScroll: true });
};

const backHref = '/admin/catalog/services';
</script>

<template>
    <AdminLayout title="Nova usluga">
        <div class="max-w-3xl space-y-6">
            <!-- Sticky actions -->
            <div class="sticky top-3 z-20">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-foreground truncate">
                            {{ form.name || 'Nova usluga' }}
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Code: <span class="font-medium text-foreground">{{ form.code || '-' }}</span>
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

                        <a :href="backHref" class="text-sm text-muted-foreground hover:text-foreground">
                            Natrag
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card -->
            <form
                @submit.prevent="submit"
                class="rounded border border-border bg-card p-5 card-elev space-y-6"
            >
                <!-- Basic -->
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Osnovno</div>
                        <div class="text-xs text-muted-foreground">Naziv, code i opis usluge.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Code</label>
                            <input
                                v-model="form.code"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm font-mono text-foreground uppercase
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="WASH"
                                autocomplete="off"
                            />
                            <p v-if="form.errors.code" class="text-xs text-rose-600">
                                {{ form.errors.code }}
                            </p>
                            <p class="text-xs text-muted-foreground">
                                Kratki identifikator (npr. WASH, STORAGE_24H).
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Naziv</label>
                            <input
                                v-model="form.name"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="Pranje"
                            />
                            <p v-if="form.errors.name" class="text-xs text-rose-600">
                                {{ form.errors.name }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Opis</label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                   placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                            placeholder="Kratki opis usluge (opcionalno)…"
                        />
                        <p v-if="form.errors.description" class="text-xs text-rose-600">
                            {{ form.errors.description }}
                        </p>
                    </div>
                </section>

                <!-- Meta -->
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Postavke</div>
                        <div class="text-xs text-muted-foreground">Redoslijed i aktivnost.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Sort order</label>
                            <input
                                v-model.number="form.sort_order"
                                type="number"
                                min="0"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.sort_order" class="text-xs text-rose-600">
                                {{ form.errors.sort_order }}
                            </p>
                            <p class="text-xs text-muted-foreground">
                                Manji broj = više u listi.
                            </p>
                        </div>

                        <div class="rounded border border-border bg-card p-4">
                            <label class="inline-flex items-center gap-2 text-sm text-foreground">
                                <input
                                    id="active"
                                    type="checkbox"
                                    v-model="form.is_active"
                                    class="h-4 w-4 rounded border border-input bg-background"
                                />
                                Aktivno
                            </label>
                            <p v-if="form.errors.is_active" class="mt-1 text-xs text-rose-600">
                                {{ form.errors.is_active }}
                            </p>
                            <p class="mt-1 text-xs text-muted-foreground">
                                Neaktivne usluge se tipično ne nude korisnicima.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Bottom actions -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground
                               hover:opacity-90 disabled:opacity-60"
                        :disabled="processing"
                    >
                        {{ processing ? 'Spremanje…' : 'Spremi promjene' }}
                    </button>

                    <a :href="backHref" class="text-sm text-muted-foreground hover:text-foreground">
                        Natrag
                    </a>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
