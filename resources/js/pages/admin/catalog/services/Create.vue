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
    form.post('/admin/catalog/services');
};
</script>

<template>
    <AdminLayout title="Nova usluga">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Code</label>
                    <input
                        v-model="form.code"
                        class="w-full rounded border px-3 py-2 text-sm"
                        placeholder="wash"
                    />
                    <div v-if="form.errors.code" class="text-sm text-red-600">
                        {{ form.errors.code }}
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Naziv</label>
                    <input
                        v-model="form.name"
                        class="w-full rounded border px-3 py-2 text-sm"
                        placeholder="Pranje"
                    />
                    <div v-if="form.errors.name" class="text-sm text-red-600">
                        {{ form.errors.name }}
                    </div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Opis</label>
                <textarea
                    v-model="form.description"
                    class="w-full rounded border px-3 py-2 text-sm"
                    rows="4"
                />
                <div
                    v-if="form.errors.description"
                    class="text-sm text-red-600"
                >
                    {{ form.errors.description }}
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Sort order</label>
                    <input
                        v-model.number="form.sort_order"
                        type="number"
                        min="0"
                        class="w-full rounded border px-3 py-2 text-sm"
                    />
                    <div
                        v-if="form.errors.sort_order"
                        class="text-sm text-red-600"
                    >
                        {{ form.errors.sort_order }}
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-7">
                    <input
                        id="active"
                        type="checkbox"
                        v-model="form.is_active"
                        class="h-4 w-4"
                    />
                    <label for="active" class="text-sm">Aktivno</label>
                </div>
            </div>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="rounded bg-blue-600 px-4 py-2 text-sm text-white"
                    :disabled="processing"
                >
                    Spremi
                </button>
                <a href="/admin/catalog/services" class="text-sm text-blue-600"
                    >Natrag</a
                >
            </div>
        </form>
    </AdminLayout>
</template>
