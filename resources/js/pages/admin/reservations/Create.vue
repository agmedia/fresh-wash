<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface UserOption {
    id: number;
    email: string;
    first_name?: string | null;
    last_name?: string | null;
    name?: string | null;
}

interface LockerOption {
    id: number;
    code: string;
    name: string | null;
    location?: { name: string };
}

const props = defineProps<{
    users: UserOption[];
    lockers: LockerOption[];
}>();

const form = useForm({
    user_id: null as number | null,
    locker_id: null as number | null,
    status: 'active',
    starts_at: '',
    ends_at: '',
    price: '',
    currency_code: 'EUR',
    note: '',
});

const processing = computed(() => form.processing);

const submit = () => {
    form.post('/admin/reservations');
};
</script>

<template>
    <AdminLayout title="Nova rezervacija">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <div class="space-y-2">
                <label class="block text-sm font-medium">Korisnik</label>
                <select v-model="form.user_id" class="w-full rounded border px-3 py-2 text-sm">
                    <option :value="null" disabled>Odaberi korisnika...</option>
                    <option v-for="u in users" :key="u.id" :value="u.id">
                        {{ u.email }}
                    </option>
                </select>
                <div v-if="form.errors.user_id" class="text-sm text-red-600">{{ form.errors.user_id }}</div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Ormarić</label>
                <select v-model="form.locker_id" class="w-full rounded border px-3 py-2 text-sm">
                    <option :value="null" disabled>Odaberi ormarić...</option>
                    <option v-for="l in lockers" :key="l.id" :value="l.id">
                        {{ l.code }} <span v-if="l.name">— {{ l.name }}</span>
                    </option>
                </select>
                <div v-if="form.errors.locker_id" class="text-sm text-red-600">{{ form.errors.locker_id }}</div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Status</label>
                    <select v-model="form.status" class="w-full rounded border px-3 py-2 text-sm">
                        <option value="draft">draft</option>
                        <option value="active">active</option>
                        <option value="ended">ended</option>
                        <option value="cancelled">cancelled</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Cijena</label>
                    <input v-model="form.price" type="number" step="0.01" class="w-full rounded border px-3 py-2 text-sm" />
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="block text-sm font-medium">Početak</label>
                    <input v-model="form.starts_at" type="datetime-local" class="w-full rounded border px-3 py-2 text-sm" />
                    <div v-if="form.errors.starts_at" class="text-sm text-red-600">{{ form.errors.starts_at }}</div>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium">Kraj</label>
                    <input v-model="form.ends_at" type="datetime-local" class="w-full rounded border px-3 py-2 text-sm" />
                    <div v-if="form.errors.ends_at" class="text-sm text-red-600">{{ form.errors.ends_at }}</div>
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium">Napomena</label>
                <textarea v-model="form.note" rows="4" class="w-full rounded border px-3 py-2 text-sm"></textarea>
            </div>

            <div class="flex gap-3">
                <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white" :disabled="processing">
                    Kreiraj
                </button>
                <a href="/admin/reservations" class="text-sm text-blue-600">Natrag</a>
            </div>
        </form>
    </AdminLayout>
</template>