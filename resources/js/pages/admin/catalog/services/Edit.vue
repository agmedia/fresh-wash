<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

type Service = {
    id: number;
    code: string;
    name: string;
    description?: string | null;
    sort_order: number;
    is_active: boolean;
};

const props = defineProps<{ service: Service }>();

const form = useForm({
    code: props.service.code ?? '',
    name: props.service.name ?? '',
    description: props.service.description ?? '',
    sort_order: props.service.sort_order ?? 0,
    is_active: props.service.is_active ?? true,
});

const processing = computed(() => form.processing);

const submit = () => {
    form.put(`/admin/catalog/services/${props.service.id}`);
};
</script>

<template>
    <AdminLayout :title="`Uredi uslugu #${service.id}`">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Code</label>
                    <input
                        v-model="form.code"
                        class="w-full rounded border px-3 py-2 text-sm"
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
                <a
                    :href="`/admin/catalog/services/${service.id}`"
                    class="text-sm text-blue-600"
                    >Pregled</a
                >
            </div>
        </form>
    </AdminLayout>
</template>
