<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',

    address_line1: '',
    address_line2: '',
    postal_code: '',
    city: '',
    country_code: 'HR',
    phone: '',
    whatsapp_opt_in: false,
});

const processing = computed(() => form.processing);

const submit = () => {
    form.post('/admin/users', { preserveScroll: true });
};

const backHref = '/admin/users';
</script>

<template>
    <AdminLayout title="Novi korisnik">
        <div class="max-w-3xl space-y-6">
            <!-- Sticky header -->
            <div class="sticky top-3 z-20">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-foreground truncate">
                            Novi korisnik
                        </div>
                        <div class="text-xs text-muted-foreground">
                            Unesi osnovne podatke i spremi.
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
                            {{ processing ? 'Spremanje…' : 'Kreiraj' }}
                        </button>

                        <a :href="backHref" class="text-sm text-muted-foreground hover:text-foreground">
                            Natrag
                        </a>
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
                        <div class="text-xs text-muted-foreground">Ime, prezime i prijava.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Ime</label>
                            <input
                                v-model="form.first_name"
                                type="text"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.first_name" class="text-xs text-rose-600">
                                {{ form.errors.first_name }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Prezime</label>
                            <input
                                v-model="form.last_name"
                                type="text"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.last_name" class="text-xs text-rose-600">
                                {{ form.errors.last_name }}
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                autocomplete="email"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.email" class="text-xs text-rose-600">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Lozinka</label>
                            <input
                                v-model="form.password"
                                type="password"
                                autocomplete="new-password"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="Obavezno za novog korisnika"
                            />
                            <p v-if="form.errors.password" class="text-xs text-rose-600">
                                {{ form.errors.password }}
                            </p>
                            <p class="text-xs text-muted-foreground">
                                Preporuka: minimalno 8 znakova.
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Telefon</label>
                            <input
                                v-model="form.phone"
                                type="text"
                                autocomplete="tel"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.phone" class="text-xs text-rose-600">
                                {{ form.errors.phone }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">WhatsApp opt-in</label>
                            <div class="flex items-center gap-2 pt-1">
                                <input
                                    id="wa"
                                    v-model="form.whatsapp_opt_in"
                                    type="checkbox"
                                    class="h-4 w-4 rounded border border-input bg-background"
                                />
                                <label for="wa" class="text-sm text-foreground">
                                    Dozvola za WhatsApp poruke
                                </label>
                            </div>
                            <p v-if="form.errors.whatsapp_opt_in" class="text-xs text-rose-600">
                                {{ form.errors.whatsapp_opt_in }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Address -->
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Adresa</div>
                        <div class="text-xs text-muted-foreground">Opcionalno, ali korisno za evidenciju.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Adresa 1</label>
                            <input
                                v-model="form.address_line1"
                                type="text"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.address_line1" class="text-xs text-rose-600">
                                {{ form.errors.address_line1 }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Adresa 2</label>
                            <input
                                v-model="form.address_line2"
                                type="text"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.address_line2" class="text-xs text-rose-600">
                                {{ form.errors.address_line2 }}
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Poštanski broj</label>
                            <input
                                v-model="form.postal_code"
                                type="text"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.postal_code" class="text-xs text-rose-600">
                                {{ form.errors.postal_code }}
                            </p>
                        </div>

                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-sm font-medium text-foreground">Grad</label>
                            <input
                                v-model="form.city"
                                type="text"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.city" class="text-xs text-rose-600">
                                {{ form.errors.city }}
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Država (ISO2)</label>
                            <input
                                v-model="form.country_code"
                                maxlength="2"
                                type="text"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm uppercase
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.country_code" class="text-xs text-rose-600">
                                {{ form.errors.country_code }}
                            </p>
                            <p class="text-xs text-muted-foreground">npr. HR</p>
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
                        {{ processing ? 'Spremanje…' : 'Kreiraj korisnika' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
