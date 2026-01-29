<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Reservation {
    id: number;
    user_id: number;
    locker_id: number;
    status: string;
    starts_at: string;
    ends_at: string | null;
    price: string | null;
    currency_code: string;
    note: string | null;
}

const props = defineProps<{
    reservation: Reservation;
    users: any[];
    lockers: any[];
}>();

const form = useForm({
    user_id: props.reservation.user_id,
    locker_id: props.reservation.locker_id,
    status: props.reservation.status,
    starts_at: props.reservation.starts_at,
    ends_at: props.reservation.ends_at ?? '',
    price: props.reservation.price ?? '',
    currency_code: props.reservation.currency_code,
    note: props.reservation.note ?? '',
});

const processing = computed(() => form.processing);

const submit = () => {
    form.put(`/admin/reservations/${props.reservation.id}`);
};
</script>

<template>
    <AdminLayout :title="`Uredi rezervaciju #${reservation.id}`">
        <form @submit.prevent="submit" class="max-w-3xl space-y-6">
            <!-- isti selecti kao u Create.vue -->
            <div class="flex gap-3">
                <button
                    class="rounded bg-blue-600 px-4 py-2 text-sm text-white"
                    :disabled="processing"
                >
                    Spremi
                </button>
                <a
                    :href="`/admin/reservations/${reservation.id}`"
                    class="text-sm text-blue-600"
                    >Pregled</a
                >
            </div>
        </form>
    </AdminLayout>
</template>
