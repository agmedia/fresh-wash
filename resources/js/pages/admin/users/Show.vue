<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

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

const fullName =
    `${props.user.first_name ?? ''} ${props.user.last_name ?? ''}`.trim();
</script>

<template>
    <AdminLayout :title="`Korisnik #${user.id}`">
        <div class="max-w-3xl space-y-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold">
                        {{ fullName || user.email }}
                    </h2>
                    <p class="text-sm text-gray-500">{{ user.email }}</p>
                </div>

                <a
                    :href="`/admin/users/${user.id}/edit`"
                    class="rounded border px-3 py-2 text-sm"
                >
                    Uredi
                </a>
            </div>

            <div class="space-y-1 rounded border p-4 text-sm">
                <div><strong>Ime:</strong> {{ user.first_name ?? '-' }}</div>
                <div><strong>Prezime:</strong> {{ user.last_name ?? '-' }}</div>
                <div><strong>Email:</strong> {{ user.email }}</div>
                <div><strong>Telefon:</strong> {{ user.phone ?? '-' }}</div>
                <div>
                    <strong>WhatsApp opt-in:</strong>
                    {{ user.whatsapp_opt_in ? 'da' : 'ne' }}
                </div>
            </div>

            <div class="space-y-1 rounded border p-4 text-sm">
                <div>
                    <strong>Adresa 1:</strong> {{ user.address_line1 ?? '-' }}
                </div>
                <div>
                    <strong>Adresa 2:</strong> {{ user.address_line2 ?? '-' }}
                </div>
                <div>
                    <strong>Poštanski broj:</strong>
                    {{ user.postal_code ?? '-' }}
                </div>
                <div><strong>Grad:</strong> {{ user.city ?? '-' }}</div>
                <div>
                    <strong>Država:</strong> {{ user.country_code ?? '-' }}
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
