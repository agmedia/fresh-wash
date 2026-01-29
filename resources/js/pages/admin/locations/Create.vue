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
    form.post(route('admin.locations.store'));
};
</script>

<template>
    <AdminLayout title="Nova lokacija">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="space-y-2">
                <label class="block text-sm font-medium">Naziv</label>
                <input v-model="form.name" type="text" class="w-full rounded border px-3 py-2 text-sm" />
                <div v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Slug (opcionalno)</label>
                <input v-model="form.slug" type="text" class="w-full rounded border px-3 py-2 text-sm" />
                <div v-if="form.errors.slug" class="text-sm text-red-600">{{ form.errors.slug }}</div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Adresa 1</label>
                    <input v-model="form.address_line1" type="text" class="w-full rounded border px-3 py-2 text-sm" />
                    <div v-if="form.errors.address_line1" class="text-sm text-red-600">
                        {{ form.errors.address_line1 }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Adresa 2</label>
                    <input v-model="form.address_line2" type="text" class="w-full rounded border px-3 py-2 text-sm" />
                    <div v-if="form.errors.address_line2" class="text-sm text-red-600">
                        {{ form.errors.address_line2 }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Poštanski broj</label>
                    <input v-model="form.postal_code" type="text" class="w-full rounded border px-3 py-2 text-sm" />
                    <div v-if="form.errors.postal_code" class="text-sm text-red-600">
                        {{ form.errors.postal_code }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Grad</label>
                    <input v-model="form.city" type="text" class="w-full rounded border px-3 py-2 text-sm" />
                    <div v-if="form.errors.city" class="text-sm text-red-600">{{ form.errors.city }}</div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Država (ISO2)</label>
                    <input v-model="form.country_code" type="text" maxlength="2" class="w-full rounded border px-3 py-2 text-sm" />
                    <div v-if="form.errors.country_code" class="text-sm text-red-600">
                        {{ form.errors.country_code }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Aktivna</label>
                    <select v-model="form.is_active" class="w-full rounded border px-3 py-2 text-sm">
                        <option :value="true">Da</option>
                        <option :value="false">Ne</option>
                    </select>
                    <div v-if="form.errors.is_active" class="text-sm text-red-600">
                        {{ form.errors.is_active }}
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Lat</label>
                    <input v-model="form.lat" type="text" class="w-full rounded border px-3 py-2 text-sm" />
                    <div v-if="form.errors.lat" class="text-sm text-red-600">{{ form.errors.lat }}</div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Lng</label>
                    <input v-model="form.lng" type="text" class="w-full rounded border px-3 py-2 text-sm" />
                    <div v-if="form.errors.lng" class="text-sm text-red-600">{{ form.errors.lng }}</div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Napomena</label>
                <textarea v-model="form.note" rows="4" class="w-full rounded border px-3 py-2 text-sm"></textarea>
                <div v-if="form.errors.note" class="text-sm text-red-600">{{ form.errors.note }}</div>
            </div>

            <div>
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm text-white" :disabled="processing">
                    Kreiraj lokaciju
                </button>
            </div>
        </form>
    </AdminLayout>
</template>