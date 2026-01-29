<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

interface Reservation {
    id: number;
    status: string;
    starts_at: string;
    ends_at: string | null;
    price: string | null;
    currency_code: string;
    note: string | null;
    user: { email: string };
    locker: { code: string; name: string | null };
}

const props = defineProps<{
    reservation: Reservation;
}>();
</script>

<template>
    <AdminLayout :title="`Rezervacija #${reservation.id}`">
        <div class="max-w-3xl space-y-6">
            <div class="flex justify-between">
                <div>
                    <div class="text-sm">
                        <strong>Status:</strong> {{ reservation.status }}
                    </div>
                    <div class="text-sm">
                        <strong>Korisnik:</strong> {{ reservation.user.email }}
                    </div>
                    <div class="text-sm">
                        <strong>Ormarić:</strong> {{ reservation.locker.code }}
                    </div>
                </div>
                <a
                    :href="`/admin/reservations/${reservation.id}/edit`"
                    class="rounded border px-3 py-2 text-sm"
                >
                    Uredi
                </a>
            </div>

            <div class="space-y-1 rounded border p-4 text-sm">
                <div><strong>Početak:</strong> {{ reservation.starts_at }}</div>
                <div>
                    <strong>Kraj:</strong> {{ reservation.ends_at ?? '-' }}
                </div>
                <div>
                    <strong>Cijena:</strong> {{ reservation.price ?? '-' }}
                    {{ reservation.currency_code }}
                </div>
            </div>

            <div
                v-if="reservation.note"
                class="rounded border p-4 text-sm whitespace-pre-line"
            >
                {{ reservation.note }}
            </div>
        </div>
    </AdminLayout>
</template>
