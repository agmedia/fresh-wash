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

import { send as verificationSend } from '@/routes/verification';
import {
    disable as twoFactorDisable,
    enable as twoFactorEnable,
} from '@/routes/two-factor';

import { useForm } from '@inertiajs/vue3';
import { useAppearance } from '@/composables/useAppearance';

interface Props {
    settings: {
        theme: 'system' | 'dark' | 'light';
        sidebar: 'expanded' | 'collapsed';
    };

    mustVerifyEmail?: boolean;
    status?: string;

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

const setCookie = (name: string, value: string, days = 365) => {
    const maxAge = days * 24 * 60 * 60;
    document.cookie = `${name}=${encodeURIComponent(value)};path=/;max-age=${maxAge};SameSite=Lax`;
};

const { updateAppearance } = useAppearance();

const appearanceForm = useForm({
    theme: (props.settings?.theme ?? 'system') as 'system' | 'dark' | 'light',
    sidebar: (props.settings?.sidebar ?? 'expanded') as
        | 'expanded'
        | 'collapsed',
});

const saveAppearance = () => {
    appearanceForm.put('/admin/settings/profile', {
        preserveScroll: true,
        onSuccess: () => {
            // tema se primjenjuje odmah (UI)
            updateAppearance(appearanceForm.theme);

            // sidebar obično tek nakon reload-a/layout reinit
            window.location.reload();
        },
    });
};
</script>

<template>
    <AdminLayout title="Moj profil">
        <div class="max-w-4xl space-y-6">
            <!-- PROFIL -->
            <section class="card-elev rounded border border-border bg-card p-5">
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
                        class="rounded border border-border bg-muted/40 p-3 text-sm"
                    >
                        <p class="text-foreground">
                            Email nije verificiran.
                            <Link
                                :href="verificationSend()"
                                method="post"
                                as="button"
                                class="ml-1 inline-flex items-center text-primary hover:underline"
                            >
                                Pošalji ponovo verifikaciju
                            </Link>
                        </p>

                        <p
                            v-if="status === 'verification-link-sent'"
                            class="mt-2 text-sm text-emerald-600 dark:text-emerald-300"
                        >
                            Link za verifikaciju je poslan.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <Button type="submit" :disabled="processing">
                            Spremi profil
                        </Button>

                        <span
                            v-if="recentlySuccessful"
                            class="text-sm text-emerald-600 dark:text-emerald-300"
                        >
                            Spremljeno.
                        </span>
                    </div>
                </Form>
            </section>

            <!-- LOZINKA -->
            <section class="card-elev rounded border border-border bg-card p-5">
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

                    <div class="flex flex-wrap items-center gap-3">
                        <Button type="submit" :disabled="processing">
                            Spremi lozinku
                        </Button>

                        <span
                            v-if="recentlySuccessful"
                            class="text-sm text-emerald-600 dark:text-emerald-300"
                        >
                            Spremljeno.
                        </span>
                    </div>
                </Form>
            </section>

            <!-- IZGLED -->
            <section class="card-elev rounded border border-border bg-card p-5">
                <HeadingSmall title="Izgled" description="Tema i sidebar" />

                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div class="grid gap-2">
                        <Label for="theme">Tema</Label>
                        <select
                            id="theme"
                            v-model="appearanceForm.theme"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground focus:ring-2 focus:ring-ring/40 focus:outline-none"
                        >
                            <option value="system">System</option>
                            <option value="dark">Dark</option>
                            <option value="light">Light</option>
                        </select>
                        <p class="text-xs text-muted-foreground">
                            Tema se primjenjuje odmah.
                        </p>
                    </div>

                    <div class="grid gap-2">
                        <Label for="sidebar">Sidebar</Label>
                        <select
                            id="sidebar"
                            v-model="appearanceForm.sidebar"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground focus:ring-2 focus:ring-ring/40 focus:outline-none"
                        >
                            <option value="expanded">Expanded</option>
                            <option value="collapsed">Collapsed</option>
                        </select>
                        <p class="text-xs text-muted-foreground">
                            Sidebar state nakon refresh-a.
                        </p>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <Button type="button" @click="saveAppearance" :disabled="appearanceForm.processing">
                        Primijeni
                    </Button>
                    <span class="text-xs text-muted-foreground">
                        (Tema odmah • sidebar nakon refresh)
                    </span>
                </div>

                <div class="mt-8">
                    <div class="mb-3 text-sm font-semibold text-foreground">
                        Dodatne opcije izgleda
                    </div>
                    <div class="rounded border border-border bg-muted/20 p-3">
                        <AppearanceTabs />
                    </div>
                </div>
            </section>

            <!-- 2FA -->
            <section class="card-elev rounded border border-border bg-card p-5">
                <HeadingSmall
                    title="Two-Factor Authentication"
                    description="Upravljanje 2FA postavkama"
                />

                <div class="mt-4 flex flex-wrap items-center gap-3">
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
                        class="text-sm text-muted-foreground"
                    >
                        Potrebna potvrda
                    </span>
                </div>

                <div class="mt-4 rounded border border-border bg-muted/20 p-4">
                    <TwoFactorSetupModal v-if="!twoFactorEnabled" />
                    <TwoFactorRecoveryCodes v-else />
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-3">
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
            </section>

            <!-- DELETE -->
            <section class="card-elev rounded border border-border bg-card p-5">
                <div class="mb-3 text-sm font-semibold text-foreground">
                    Brisanje računa
                </div>
                <DeleteUser />
            </section>
        </div>
    </AdminLayout>
</template>
