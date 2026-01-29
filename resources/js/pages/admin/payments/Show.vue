<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

type Payment = {
    id: number;
    provider: string;
    status: string;
    amount: string | number;
    currency: string;
    paid_at?: string | null;
};

type Order = {
    id: number;
    code: string;
    status: string;
    currency: string;
    total_net: string | number;
    vat_rate: string | number;
    total_gross: string | number;
    note?: string | null;
    user?: {
        email: string;
        first_name?: string | null;
        last_name?: string | null;
        name?: string | null;
    } | null;
    location?: { name: string } | null;
    payments?: Payment[];
};

const props = defineProps<{ order: Order }>();

const userName = () => {
    const u = props.order.user;
    if (!u) return '-';
    const full = [u.first_name, u.last_name].filter(Boolean).join(' ').trim();
    return full || u.name || u.email;
};
</script>

<template>
    <AdminLayout :title="`Narudžba ${order.code}`">
        <div class="max-w-4xl space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div class="w-full rounded border bg-white p-4">
                    <div class="text-sm text-gray-500">Korisnik</div>
                    <div class="text-lg font-semibold">{{ userName() }}</div>
                    <div class="text-sm text-gray-600">
                        {{ order.user?.email ?? '-' }}
                    </div>
                </div>

                <div class="flex gap-2">
                    <Link
                        :href="`/admin/orders/${order.id}/edit`"
                        class="rounded border bg-white px-3 py-2 text-sm hover:bg-gray-50"
                    >
                        Uredi
                    </Link>
                    <a
                        href="/admin/orders"
                        class="rounded border bg-white px-3 py-2 text-sm hover:bg-gray-50"
                    >
                        Natrag
                    </a>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-1 rounded border bg-white p-4 text-sm">
                    <div><strong>Status:</strong> {{ order.status }}</div>
                    <div>
                        <strong>Lokacija:</strong>
                        {{ order.location?.name ?? '-' }}
                    </div>
                    <div><strong>Valuta:</strong> {{ order.currency }}</div>
                </div>

                <div class="space-y-1 rounded border bg-white p-4 text-sm">
                    <div>
                        <strong>Net:</strong> {{ order.total_net }}
                        {{ order.currency }}
                    </div>
                    <div><strong>PDV:</strong> {{ order.vat_rate }}%</div>
                    <div>
                        <strong>Bruto:</strong> {{ order.total_gross }}
                        {{ order.currency }}
                    </div>
                </div>
            </div>

            <div class="rounded border bg-white p-4 text-sm">
                <div class="mb-2 font-medium">Napomena</div>
                <div class="whitespace-pre-wrap text-gray-700">
                    {{ order.note || '-' }}
                </div>
            </div>

            <div class="rounded border bg-white p-4">
                <div class="mb-3 flex items-center justify-between">
                    <div class="font-medium">Plaćanja</div>
                    <Link
                        href="/admin/payments"
                        class="text-sm text-blue-600 hover:underline"
                        >Sva plaćanja</Link
                    >
                </div>

                <div v-if="order.payments?.length" class="overflow-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left">ID</th>
                                <th class="px-3 py-2 text-left">Provider</th>
                                <th class="px-3 py-2 text-left">Status</th>
                                <th class="px-3 py-2 text-right">Iznos</th>
                                <th class="px-3 py-2 text-left">Paid at</th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="p in order.payments"
                                :key="p.id"
                                class="border-t"
                            >
                                <td class="px-3 py-2">{{ p.id }}</td>
                                <td class="px-3 py-2">{{ p.provider }}</td>
                                <td class="px-3 py-2">{{ p.status }}</td>
                                <td class="px-3 py-2 text-right">
                                    {{ p.amount }} {{ p.currency }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ p.paid_at ?? '-' }}
                                </td>
                                <td class="px-3 py-2 text-right">
                                    <Link
                                        :href="`/admin/payments/${p.id}`"
                                        class="text-blue-600 hover:underline"
                                        >Pregled</Link
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="text-sm text-gray-500">Nema plaćanja.</div>
            </div>
        </div>
    </AdminLayout>
</template>
