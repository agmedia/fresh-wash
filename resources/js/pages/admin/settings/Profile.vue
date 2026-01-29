<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

// Starter-kit forms/actions
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import TwoFactorRecoveryCodes from '@/components/TwoFactorRecoveryCodes.vue';
import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.vue';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { Form, Link, usePage } from '@inertiajs/vue3';
import { ShieldBan, ShieldCheck } from 'lucide-vue-next';

import { edit as profileEdit } from '@/routes/profile';
import { send as verificationSend } from '@/routes/verification';
import {
    disable as twoFactorDisable,
    enable as twoFactorEnable,
} from '@/routes/two-factor';

import { useAppearance } from '@/composables/useAppearance';

interface Props {
    // tvoje admin postavke iz baze
    settings: {
        theme: 'system' | 'dark' | 'light';
        sidebar: 'expanded' | 'collapsed';
    };

    // za profile starter-kit
    mustVerifyEmail?: boolean;
    status?: string;

    // za 2FA starter-kit
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    mustVerifyEmail: false,
    status: undefined,
    requiresConfirmation: false,
    twoFactorEnabled: false,
});

const page = usePage();
const user = page.props.auth.user;

// Appearance (tvoj dio)
const appearanceForm = {
    theme: props.settings.theme ?? 'system',
    sidebar: props.settings.sidebar ?? 'expanded',
};

const { updateAppearance } = useAppearance();

const setCookie = (name: string, value: string, days = 365) => {
    const maxAge = days * 24 * 60 * 60;
    document.cookie = `${name}=${encodeURIComponent(value)};path=/;max-age=${maxAge};SameSite=Lax`;
};

const saveAppearance = async () => {
    // ovo je tvoja admin ruta (pretpostavka da već postoji)
    // ako je route helper, zamijeni s route('admin.settings.profile.update')
    // ili ostavi ovako ako ti radi:
    //   form.put('/admin/settings/profile')
    // ovdje idemo “ručno” preko fetch? Ne, ostajemo na Form komponenti u templateu.
    updateAppearance(appearanceForm.theme as any);
    setCookie(
        'sidebar_state',
        appearanceForm.sidebar === 'collapsed' ? 'closed' : 'open',
    );
    window.location.reload();
};
</script>

<template>
    <AdminLayout title="Moj profil">
        <div class="max-w-4xl space-y-6">
            <!-- PROFILE INFO (name/email + verify + delete) -->
            <div
                class="rounded border p-5"
            >
                <HeadingSmall title="Profil" description="Uredi ime i email" />

                <Form
                    v-bind="ProfileController.update.form()"
                    class="mt-4 space-y-5"
                    preserve-scroll
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Ime</Label>
                        <Input
                            id="name"
                            name="name"
                            :default-value="user.name"
                        />
                        <InputError :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            name="email"
                            type="email"
                            :default-value="user.email"
                        />
                        <InputError :message="errors.email" />
                    </div>

                    <div
                        v-if="mustVerifyEmail && !user.email_verified_at"
                        class="rounded border border-gray-800 bg-gray-950 p-3 text-sm"
                    >
                        <p class="text-gray-300">
                            Email nije verificiran.
                            <Link
                                :href="verificationSend()"
                                method="post"
                                as="button"
                                class="ml-1 text-blue-400 hover:underline"
                            >
                                Pošalji ponovo verifikaciju
                            </Link>
                        </p>
                        <p
                            v-if="status === 'verification-link-sent'"
                            class="mt-2 text-green-400"
                        >
                            Link za verifikaciju je poslan.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <Button type="submit" :disabled="processing">
                            Spremi profil
                        </Button>
                        <span
                            v-if="recentlySuccessful"
                            class="text-sm text-green-400"
                            >Spremljeno.</span
                        >
                    </div>
                </Form>
            </div>

            <!-- PASSWORD -->
            <div
                class="rounded border p-5"
            >
                <HeadingSmall
                    title="Lozinka"
                    description="Promijeni lozinku računa"
                />

                <Form
                    v-bind="PasswordController.update.form()"
                    class="mt-4 space-y-5"
                    :options="{ preserveScroll: true }"
                    reset-on-success
                    :reset-on-error="[
                        'password',
                        'password_confirmation',
                        'current_password',
                    ]"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="grid gap-2">
                        <Label for="current_password">Trenutna lozinka</Label>
                        <Input
                            id="current_password"
                            name="current_password"
                            type="password"
                        />
                        <InputError :message="errors.current_password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Nova lozinka</Label>
                        <Input id="password" name="password" type="password" />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation"
                            >Potvrdi novu lozinku</Label
                        >
                        <Input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <div class="flex items-center gap-3">
                        <Button type="submit" :disabled="processing">
                            Spremi lozinku
                        </Button>
                        <span
                            v-if="recentlySuccessful"
                            class="text-sm text-green-400"
                            >Spremljeno.</span
                        >
                    </div>
                </Form>
            </div>

            <!-- APPEARANCE (tvoj dio + AppearanceTabs) -->
            <div
                class="rounded border p-5"
            >
                <HeadingSmall title="Izgled" description="Tema i sidebar" />

                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm text-gray-400"
                            >Tema</label
                        >
                        <select
                            v-model="appearanceForm.theme"
                            class="w-full rounded border px-3 py-2 text-sm"
                        >
                            <option value="system">System</option>
                            <option value="dark">Dark</option>
                            <option value="light">Light</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm text-gray-400"
                            >Sidebar</label
                        >
                        <select
                            v-model="appearanceForm.sidebar"
                            class="w-full rounded border px-3 py-2 text-sm"
                        >
                            <option value="expanded">Expanded</option>
                            <option value="collapsed">Collapsed</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <Button type="button" @click="saveAppearance">
                        Primijeni
                    </Button>
                    <span class="text-xs text-gray-400">
                        (tema se primjenjuje odmah; sidebar nakon refresh)
                    </span>
                </div>

                <div class="mt-10 mb-3">
                    <!-- ovo je starter-kit tabs (ako želiš zadržati njihove opcije) -->
                    <AppearanceTabs />
                </div>
            </div>

            <!-- TWO FACTOR -->
            <div
                class="rounded border p-5"
            >
                <HeadingSmall
                    title="Two-Factor Authentication"
                    description="Upravljanje 2FA postavkama"
                />

                <div class="mt-4 flex items-center gap-2">
                    <Badge
                        :variant="twoFactorEnabled ? 'default' : 'secondary'"
                    >
                        <span class="inline-flex items-center gap-2">
                            <ShieldCheck
                                v-if="twoFactorEnabled"
                                class="h-4 w-4"
                            />
                            <ShieldBan v-else class="h-4 w-4" />
                            {{ twoFactorEnabled ? 'Uključeno' : 'Isključeno' }}
                        </span>
                    </Badge>

                    <span
                        v-if="requiresConfirmation"
                        class="text-sm text-gray-400"
                    >
                        (Potrebna potvrda)
                    </span>
                </div>

                <div class="mt-4">
                    <TwoFactorSetupModal v-if="!twoFactorEnabled" />
                    <TwoFactorRecoveryCodes v-else />
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <Form
                        v-if="!twoFactorEnabled"
                        v-bind="twoFactorEnable.form()"
                        class="inline"
                    >
                        <Button type="submit">Uključi 2FA</Button>
                    </Form>

                    <Form
                        v-else
                        v-bind="twoFactorDisable.form()"
                        class="inline"
                    >
                        <Button type="submit" variant="destructive"
                            >Isključi 2FA</Button
                        >
                    </Form>
                </div>
            </div>

            <div
                class="rounded border p-5"
            >
                <DeleteUser />
            </div>
        </div>
    </AdminLayout>
</template>