<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface User {
    id: number;
    first_name: string | null;
    last_name: string | null;
    email: string;
    address_line1: string | null;
    address_line2: string | null;
    postal_code: string | null;
    city: string | null;
    country_code: string | null;
    phone: string | null;
    whatsapp_opt_in: boolean;
}

const props = defineProps<{
    user: User;
}>();

const form = useForm({
    first_name: props.user.first_name ?? '',
    last_name: props.user.last_name ?? '',
    email: props.user.email ?? '',
    password: '', // opcionalno: promjena lozinke
    address_line1: props.user.address_line1 ?? '',
    address_line2: props.user.address_line2 ?? '',
    postal_code: props.user.postal_code ?? '',
    city: props.user.city ?? '',
    country_code: props.user.country_code ?? 'HR',
    phone: props.user.phone ?? '',
    whatsapp_opt_in: props.user.whatsapp_opt_in ?? false,
});

const processing = computed(() => form.processing);

const submit = () => {
    form.put(`/admin/users/${props.user.id}`);
};
</script>

<template>
    <AdminLayout :title="`Uredi korisnika #${user.id}`">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Ime</label>
                    <input
                        v-model="form.first_name"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.first_name"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.first_name }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Prezime</label>
                    <input
                        v-model="form.last_name"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.last_name"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.last_name }}
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div v-if="form.errors.email" class="text-sm text-red-600">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium"
                        >Nova lozinka (opcionalno)</label
                    >
                    <input
                        v-model="form.password"
                        type="password"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.password"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.password }}
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Adresa 1</label>
                    <input
                        v-model="form.address_line1"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.address_line1"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.address_line1 }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Adresa 2</label>
                    <input
                        v-model="form.address_line2"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.address_line2"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.address_line2 }}
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-2">
                    <label class="block text-sm font-medium"
                        >Poštanski broj</label
                    >
                    <input
                        v-model="form.postal_code"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.postal_code"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.postal_code }}
                    </div>
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="block text-sm font-medium">Grad</label>
                    <input
                        v-model="form.city"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div v-if="form.errors.city" class="text-sm text-red-600">
                        {{ form.errors.city }}
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium"
                        >Država (ISO2)</label
                    >
                    <input
                        v-model="form.country_code"
                        maxlength="2"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.country_code"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.country_code }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Telefon</label>
                    <input
                        v-model="form.phone"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div v-if="form.errors.phone" class="text-sm text-red-600">
                        {{ form.errors.phone }}
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input
                    id="wa"
                    v-model="form.whatsapp_opt_in"
                    type="checkbox"
                    class="h-4 w-4"
                />
                <label for="wa" class="text-sm">WhatsApp opt-in</label>
                <div
                    v-if="form.errors.whatsapp_opt_in"
                    class="text-sm text-red-600"
                >
                    {{ form.errors.whatsapp_opt_in }}
                </div>
            </div>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="rounded bg-blue-600 px-4 py-2 text-sm text-white"
                    :disabled="processing"
                >
                    Spremi promjene
                </button>

                <a
                    :href="`/admin/users/${user.id}`"
                    class="text-sm text-blue-600"
                    >Pregled</a
                >
            </div>
        </form>
    </AdminLayout>
</template>
