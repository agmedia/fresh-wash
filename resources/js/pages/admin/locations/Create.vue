<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({
    name: '',
    slug: '',
    address_line1: '',
    address_line2: '',
    postal_code: '',
    city: '',
    country_code: 'HR',
    lat: '',
    lng: '',
    is_active: true,
    note: '',
});

const processing = computed(() => form.processing);

const submit = () => {
    form.post('/admin/locations', {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout title="Nova lokacija">
        <div class="max-w-3xl space-y-6">
            <form
                @submit.prevent="submit"
                class="rounded border border-border bg-card p-5 card-elev space-y-6"
            >
                <!-- Basic -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Naziv
                        </label>
                        <input
                            v-model="form.name"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   text-foreground placeholder:text-muted-foreground
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.name" class="text-xs text-rose-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Slug
                        </label>
                        <input
                            v-model="form.slug"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   text-foreground placeholder:text-muted-foreground
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.slug" class="text-xs text-rose-600">
                            {{ form.errors.slug }}
                        </p>
                    </div>
                </div>

                <!-- Address -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium">Adresa 1</label>
                        <input
                            v-model="form.address_line1"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.address_line1" class="text-xs text-rose-600">
                            {{ form.errors.address_line1 }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium">Adresa 2</label>
                        <input
                            v-model="form.address_line2"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.address_line2" class="text-xs text-rose-600">
                            {{ form.errors.address_line2 }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium">Poštanski broj</label>
                        <input
                            v-model="form.postal_code"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.postal_code" class="text-xs text-rose-600">
                            {{ form.errors.postal_code }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium">Grad</label>
                        <input
                            v-model="form.city"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.city" class="text-xs text-rose-600">
                            {{ form.errors.city }}
                        </p>
                    </div>
                </div>

                <!-- Meta -->
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium">Država</label>
                        <input
                            v-model="form.country_code"
                            maxlength="2"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm uppercase
                                   focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.country_code" class="text-xs text-rose-600">
                            {{ form.errors.country_code }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium">Lat</label>
                        <input
                            v-model="form.lat"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.lat" class="text-xs text-rose-600">
                            {{ form.errors.lat }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium">Lng</label>
                        <input
                            v-model="form.lng"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.lng" class="text-xs text-rose-600">
                            {{ form.errors.lng }}
                        </p>
                    </div>
                </div>

                <!-- Status -->
                <div class="space-y-1.5">
                    <label class="text-sm font-medium">Status</label>
                    <select
                        v-model="form.is_active"
                        class="w-48 rounded border border-input bg-background px-3 py-2 text-sm
                               focus:ring-2 focus:ring-ring/40"
                    >
                        <option :value="true">Aktivna</option>
                        <option :value="false">Neaktivna</option>
                    </select>
                    <p v-if="form.errors.is_active" class="text-xs text-rose-600">
                        {{ form.errors.is_active }}
                    </p>
                </div>

                <!-- Note -->
                <div class="space-y-1.5">
                    <label class="text-sm font-medium">Napomena</label>
                    <textarea
                        v-model="form.note"
                        rows="4"
                        class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                               focus:ring-2 focus:ring-ring/40"
                    />
                    <p v-if="form.errors.note" class="text-xs text-rose-600">
                        {{ form.errors.note }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium
                               text-primary-foreground hover:opacity-90 disabled:opacity-60"
                        :disabled="processing"
                    >
                        Kreiraj lokaciju
                    </button>

                    <a
                        href="/admin/locations"
                        class="text-sm text-muted-foreground hover:text-foreground"
                    >
                        Odustani
                    </a>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
