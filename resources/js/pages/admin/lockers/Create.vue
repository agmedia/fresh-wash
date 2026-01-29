<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface LocationOption {
    id: number;
    name: string;
}

const props = defineProps<{
    locations: LocationOption[];
}>();

const form = useForm({
    location_id: props.locations?.[0]?.id ?? null,
    code: '',
    name: '',
    slug: '',
    size: 'M' as 'S' | 'M' | 'L' | 'XL',
    hardware_uid: '',
    is_active: true,
    is_available: true,
    note: '',
});

const processing = computed(() => form.processing);

const submit = () => {
    form.post('/admin/lockers');
};
</script>

<template>
    <AdminLayout title="Novi ormarić">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="space-y-2">
                <label class="block text-sm font-medium">Lokacija</label>
                <select
                    v-model="form.location_id"
                    class="w-full rounded border px-3 py-2 text-sm"
                >
                    <option :value="null" disabled>Odaberi lokaciju...</option>
                    <option
                        v-for="loc in locations"
                        :key="loc.id"
                        :value="loc.id"
                    >
                        {{ loc.name }}
                    </option>
                </select>
                <div
                    v-if="form.errors.location_id"
                    class="text-sm text-red-600"
                >
                    {{ form.errors.location_id }}
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Code</label>
                    <input
                        v-model="form.code"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div v-if="form.errors.code" class="text-sm text-red-600">
                        {{ form.errors.code }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium"
                        >Naziv (opcionalno)</label
                    >
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div v-if="form.errors.name" class="text-sm text-red-600">
                        {{ form.errors.name }}
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium"
                    >Slug (opcionalno)</label
                >
                <input
                    v-model="form.slug"
                    type="text"
                    class="w-full rounded border px-3 py-2 text-sm"
                />
                <div v-if="form.errors.slug" class="text-sm text-red-600">
                    {{ form.errors.slug }}
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Veličina</label>
                    <select
                        v-model="form.size"
                        class="w-full rounded border px-3 py-2 text-sm"
                    >
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                    <div v-if="form.errors.size" class="text-sm text-red-600">
                        {{ form.errors.size }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium"
                        >Hardware UID (opcionalno)</label
                    >
                    <input
                        v-model="form.hardware_uid"
                        type="text"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.hardware_uid"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.hardware_uid }}
                    </div>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Aktivan</label>
                    <select
                        v-model="form.is_active"
                        class="w-full rounded border px-3 py-2 text-sm"
                    >
                        <option :value="true">Da</option>
                        <option :value="false">Ne</option>
                    </select>
                    <div
                        v-if="form.errors.is_active"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.is_active }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Dostupan</label>
                    <select
                        v-model="form.is_available"
                        class="w-full rounded border px-3 py-2 text-sm"
                    >
                        <option :value="true">Da</option>
                        <option :value="false">Ne</option>
                    </select>
                    <div
                        v-if="form.errors.is_available"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.is_available }}
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Napomena</label>
                <textarea
                    v-model="form.note"
                    rows="4"
                    class="w-full rounded border px-3 py-2 text-sm"
                ></textarea>
                <div v-if="form.errors.note" class="text-sm text-red-600">
                    {{ form.errors.note }}
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="submit"
                    class="rounded bg-blue-600 px-4 py-2 text-sm text-white"
                    :disabled="processing"
                >
                    Kreiraj
                </button>

                <a
                    href="/admin/lockers"
                    class="text-sm text-blue-600"
                >Natrag</a>
            </div>
        </form>
    </AdminLayout>
</template>
