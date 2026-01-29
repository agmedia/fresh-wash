<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import TopBar from './components/TopBar.vue';
import FrontFooterBar from '@/pages/front/components/FrontFooterBar.vue';

type User = {
    id: number;
    first_name: string | null;
    last_name: string | null;
    name: string;
    email: string;
    address_line1: string | null;
    address_line2: string | null;
    postal_code: string | null;
    city: string | null;
    country_code: string | null;
    phone: string | null;
    whatsapp_opt_in: boolean;
};

const page = usePage();

// controller može slati `user`, ali fallback je auth.user
const user = computed<User | null>(
    () => (page.props as any)?.user ?? (page.props as any)?.auth?.user ?? null,
);

const isAuthenticated = computed(() =>
    Boolean((page.props as any).isAuthenticated),
);

const displayName = computed(() => {
    const u = user.value;
    if (!u) return '';

    const fn = (u.first_name ?? '').trim();
    const ln = (u.last_name ?? '').trim();
    const full = `${fn} ${ln}`.trim();

    return full || u.name || u.email;
});

const displayLocation = computed(() => {
    const u = user.value;
    if (!u) return '';
    const parts = [u.city, u.country_code].filter(Boolean);
    return parts.join(', ');
});

const form = useForm({
    first_name: user.value?.first_name ?? '',
    last_name: user.value?.last_name ?? '',
    email: user.value?.email ?? '',
    phone: user.value?.phone ?? '',
    whatsapp_opt_in: Boolean(user.value?.whatsapp_opt_in ?? false),

    address_line1: user.value?.address_line1 ?? '',
    address_line2: user.value?.address_line2 ?? '',
    postal_code: user.value?.postal_code ?? '',
    city: user.value?.city ?? '',
    country_code: user.value?.country_code ?? 'HR',
});

const flashSuccess = computed<string | null>(
    () => (page.props as any)?.flash?.success ?? null,
);

const submit = () => {
    // koristimo hardcoded URL da izbjegnemo Ziggy/route() probleme
    form.patch('/app/profil', {
        preserveScroll: true,
    });
};

const destroyAccount = () => {
    if (!confirm('Jesi siguran? Ovo će obrisati tvoj račun i podatke.')) return;

    form.delete('/app/profil', {
        preserveScroll: true,
    });
};
</script>

<template>

    <div id="page" class="theme-light" data-highlight="highlight-red">
        <!-- Header -->
        <TopBar title="Profil" />

        <!-- Content -->
        <div class="page-content">
            <!-- Hero card (kao u template-u page-user-edit.html) -->
            <div class="card card-style bg-28" data-card-height="420">
                <div class="card-bottom ms-3 me-3">
                    <h1 class="font-40 line-height-xl color-white">
                        {{ displayName || 'Korisnik' }}
                    </h1>
                    <p v-if="displayLocation" class="color-white opacity-60">
                        <i class="fa fa-map-marker me-2"></i
                        >{{ displayLocation }}
                    </p>
                    <p class="color-white font-15 mb-0 opacity-80">
                        Ovdje možeš urediti svoje podatke, uključiti obavijesti
                        i upravljati sigurnosnim postavkama.
                    </p>
                </div>

                <div class="card-overlay bg-gradient"></div>
            </div>

            <!-- Flash message -->
            <div v-if="flashSuccess" class="card card-style">
                <div class="content">
                    <div
                        class="bg-green-dark color-white font-13 rounded-s px-3 py-2"
                    >
                        {{ flashSuccess }}
                    </div>
                </div>
            </div>

            <!-- Basic info -->
            <div class="card card-style">
                <div class="content">
                    <p class="mb-n1 color-highlight font-600 font-12">
                        Uredi račun
                    </p>
                    <h4>Osnovni podaci</h4>
                    <p class="mb-0">
                        Podaci koje koristimo za komunikaciju i izdavanje
                        računa.
                    </p>

                    <div class="mt-4 mb-2">
                        <div
                            class="input-style has-borders no-icon input-style-always-active validate-field mb-4"
                        >
                            <input
                                v-model="form.first_name"
                                type="text"
                                class="form-control"
                                placeholder="Ime"
                            />
                            <label class="color-highlight">Ime</label>
                            <i
                                class="fa fa-times disabled invalid color-red-dark"
                            ></i>
                            <i
                                class="fa fa-check disabled valid color-green-dark"
                            ></i>
                            <em v-if="form.errors.first_name">{{
                                form.errors.first_name
                            }}</em>
                        </div>

                        <div
                            class="input-style has-borders no-icon input-style-always-active validate-field mb-4"
                        >
                            <input
                                v-model="form.last_name"
                                type="text"
                                class="form-control"
                                placeholder="Prezime"
                            />
                            <label class="color-highlight">Prezime</label>
                            <i
                                class="fa fa-times disabled invalid color-red-dark"
                            ></i>
                            <i
                                class="fa fa-check disabled valid color-green-dark"
                            ></i>
                            <em v-if="form.errors.last_name">{{
                                form.errors.last_name
                            }}</em>
                        </div>

                        <div
                            class="input-style has-borders no-icon input-style-always-active validate-field mb-4"
                        >
                            <input
                                v-model="form.email"
                                type="email"
                                class="form-control"
                                placeholder="name@domain.com"
                            />
                            <label class="color-highlight">Email</label>
                            <i
                                class="fa fa-times disabled invalid color-red-dark"
                            ></i>
                            <i
                                class="fa fa-check disabled valid color-green-dark"
                            ></i>
                            <em v-if="form.errors.email">{{
                                form.errors.email
                            }}</em>
                        </div>

                        <div
                            class="input-style has-borders no-icon input-style-always-active validate-field mb-4"
                        >
                            <input
                                v-model="form.phone"
                                type="text"
                                class="form-control"
                                placeholder="Telefon"
                            />
                            <label class="color-highlight">Telefon</label>
                            <i
                                class="fa fa-times disabled invalid color-red-dark"
                            ></i>
                            <i
                                class="fa fa-check disabled valid color-green-dark"
                            ></i>
                            <em v-if="form.errors.phone">{{
                                form.errors.phone
                            }}</em>
                        </div>

                        <div
                            class="input-style has-borders no-icon input-style-always-active validate-field mb-4"
                        >
                            <input
                                v-model="form.address_line1"
                                type="text"
                                class="form-control"
                                placeholder="Ulica i broj"
                            />
                            <label class="color-highlight"
                                >Adresa (linija 1)</label
                            >
                            <i
                                class="fa fa-times disabled invalid color-red-dark"
                            ></i>
                            <i
                                class="fa fa-check disabled valid color-green-dark"
                            ></i>
                            <em v-if="form.errors.address_line1">{{
                                form.errors.address_line1
                            }}</em>
                        </div>

                        <div
                            class="input-style has-borders no-icon input-style-always-active validate-field mb-4"
                        >
                            <input
                                v-model="form.address_line2"
                                type="text"
                                class="form-control"
                                placeholder="Kat, stan, napomena..."
                            />
                            <label class="color-highlight"
                                >Adresa (linija 2)</label
                            >
                            <i
                                class="fa fa-times disabled invalid color-red-dark"
                            ></i>
                            <i
                                class="fa fa-check disabled valid color-green-dark"
                            ></i>
                            <em v-if="form.errors.address_line2">{{
                                form.errors.address_line2
                            }}</em>
                        </div>

                        <div class="row">
                            <div class="col-5">
                                <div
                                    class="input-style has-borders no-icon input-style-always-active validate-field mb-4"
                                >
                                    <input
                                        v-model="form.postal_code"
                                        type="text"
                                        class="form-control"
                                        placeholder="10000"
                                    />
                                    <label class="color-highlight"
                                        >Poštanski broj</label
                                    >
                                    <i
                                        class="fa fa-times disabled invalid color-red-dark"
                                    ></i>
                                    <i
                                        class="fa fa-check disabled valid color-green-dark"
                                    ></i>
                                    <em v-if="form.errors.postal_code">{{
                                        form.errors.postal_code
                                    }}</em>
                                </div>
                            </div>

                            <div class="col-4">
                                <div
                                    class="input-style has-borders no-icon input-style-always-active validate-field mb-4"
                                >
                                    <input
                                        v-model="form.city"
                                        type="text"
                                        class="form-control"
                                        placeholder="Zagreb"
                                    />
                                    <label class="color-highlight">Grad</label>
                                    <i
                                        class="fa fa-times disabled invalid color-red-dark"
                                    ></i>
                                    <i
                                        class="fa fa-check disabled valid color-green-dark"
                                    ></i>
                                    <em v-if="form.errors.city">{{
                                        form.errors.city
                                    }}</em>
                                </div>
                            </div>

                            <div class="col-3">
                                <div
                                    class="input-style has-borders no-icon input-style-always-active validate-field mb-4"
                                >
                                    <input
                                        v-model="form.country_code"
                                        type="text"
                                        class="form-control"
                                        placeholder="HR"
                                    />
                                    <label class="color-highlight"
                                        >Država</label
                                    >
                                    <i
                                        class="fa fa-times disabled invalid color-red-dark"
                                    ></i>
                                    <i
                                        class="fa fa-check disabled valid color-green-dark"
                                    ></i>
                                    <em v-if="form.errors.country_code">{{
                                        form.errors.country_code
                                    }}</em>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn btn-full btn-m gradient-highlight font-13 font-600 mt-2 rounded-s"
                        :disabled="form.processing"
                        @click="submit"
                    >
                        Spremi osnovne podatke
                    </button>

                    <div class="divider mt-4 mb-3"></div>

                    <div class="d-flex gap-2">
                        <Link
                            href="/settings/two-factor"
                            class="btn btn-m font-13 font-700 btn-border border-gray-dark color-gray-dark flex-fill rounded-s"
                        >
                            2FA
                        </Link>
                        <Link
                            href="/settings/appearance"
                            class="btn btn-m font-13 font-700 btn-border border-gray-dark color-gray-dark flex-fill rounded-s"
                        >
                            Tema
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Privacy / Notifications -->
            <div class="card card-style">
                <div class="content">
                    <p class="mb-n1 color-highlight font-600 font-12">
                        Postavke
                    </p>
                    <h4>Obavijesti</h4>
                    <p class="mb-3">
                        Uključi ili isključi WhatsApp obavijesti.
                    </p>

                    <div class="d-flex mb-2">
                        <div class="pt-1">
                            <h5 class="font-600 font-14 mb-0">
                                WhatsApp obavijesti
                            </h5>
                            <p class="font-12 mb-0 opacity-70">
                                Primaj statusne poruke o narudžbi.
                            </p>
                        </div>
                        <div class="ms-auto me-2 pe-2">
                            <div class="custom-control ios-switch small-switch">
                                <input
                                    id="toggle-whatsapp"
                                    v-model="form.whatsapp_opt_in"
                                    type="checkbox"
                                    class="ios-input"
                                />
                                <label
                                    class="custom-control-label"
                                    for="toggle-whatsapp"
                                ></label>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="form.errors.whatsapp_opt_in"
                        class="font-12 color-red-light mt-2"
                    >
                        {{ form.errors.whatsapp_opt_in }}
                    </div>

                    <button
                        type="button"
                        class="btn btn-full btn-m gradient-highlight font-13 font-600 mt-4 rounded-s"
                        :disabled="form.processing"
                        @click="submit"
                    >
                        Spremi postavke
                    </button>
                </div>
            </div>

            <!-- GDPR delete -->
            <div class="card card-style">
                <div class="content">
                    <p class="mb-n1 color-highlight font-600 font-12">GDPR</p>
                    <h4>Brisanje računa</h4>
                    <p class="mb-3">
                        Ovo će obrisati tvoj račun i povezane podatke.
                    </p>

                    <button
                        type="button"
                        class="btn btn-full btn-m bg-red-dark font-13 font-700 rounded-s"
                        :disabled="form.processing"
                        @click="destroyAccount"
                    >
                        Obriši račun
                    </button>
                </div>
            </div>
        </div>

        <!-- Menus -->
        <div
            id="menu-main"
            class="menu menu-box-left rounded-0"
            data-menu-width="280"
            data-menu-load="/menu-main.html"
        ></div>

        <div
            id="menu-colors"
            class="menu menu-box-bottom rounded-m"
            data-menu-load="/front-theme/menu-colors.html"
            data-menu-height="480"
        ></div>

        <!-- Optional footer bar (ako ti već postoji globalno, slobodno obriši ovaj blok) -->
        <FrontFooterBar
            :active="'profil'"
            :is-authenticated="isAuthenticated"
        />
    </div>
</template>