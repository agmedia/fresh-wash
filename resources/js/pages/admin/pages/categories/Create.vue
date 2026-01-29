<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    slug: '',
    description: '',
    is_active: true,
});

const submit = () => form.post('/admin/page-categories');
</script>

<template>
    <AdminLayout title="Nova kategorija">
        <form @submit.prevent="submit" class="max-w-3xl space-y-4">
            <div>
                <label class="mb-1 block text-sm text-gray-400">Naziv *</label>
                <input v-model="form.name" class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200" />
                <div v-if="form.errors.name" class="mt-1 text-xs text-red-400">{{ form.errors.name }}</div>
            </div>

            <div>
                <label class="mb-1 block text-sm text-gray-400">Slug *</label>
                <input v-model="form.slug" class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200" />
                <div v-if="form.errors.slug" class="mt-1 text-xs text-red-400">{{ form.errors.slug }}</div>
            </div>

            <div>
                <label class="mb-1 block text-sm text-gray-400">Opis</label>
                <textarea v-model="form.description" rows="3" class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200" />
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-200">
                <input type="checkbox" v-model="form.is_active" />
                Aktivna
            </label>

            <div class="flex items-center gap-3">
                <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-500" :disabled="form.processing">
                    Spremi
                </button>
                <Link href="/admin/page-categories" class="text-sm text-gray-300 hover:underline">Nazad</Link>
            </div>
        </form>
    </AdminLayout>
</template>
