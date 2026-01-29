<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

type Payment = {
    id: number;
    provider: string;
    status: string;
    amount: string | number;
    currency: string;
    provider_ref?: string | null;
    paid_at?: string | null;
    payload?: any;
    order?: {
        id: number;
        code: string;
        user?: { email: string } | null;
    } | null;
};

defineProps<{ payment: Payment }>();
</script>

<template>
    <AdminLayout :title="`Plaćanje #${payment.id}`">
        <div class="max-w-4xl space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div
                    class="w-full space-y-1 rounded border bg-white p-4 text-sm"
                >
                    <div>
                        <strong>Order:</strong>
                        <Link
                            v-if="payment.order"
                            :href="`/admin/orders/${payment.order.id}`"
                            class="text-blue-600 hover:underline"
                        >
                            {{ payment.order.code }}
                        </Link>
                        <span v-else>-</span>
                    </div>
                    <div>
                        <strong>Email:</strong>
                        {{ payment.order?.user?.email ?? '-' }}
                    </div>
                    <div><strong>Provider:</strong> {{ payment.provider }}</div>
                    <div><strong>Status:</strong> {{ payment.status }}</div>
                    <div>
                        <strong>Iznos:</strong> {{ payment.amount }}
                        {{ payment.currency }}
                    </div>
                    <div>
                        <strong>Paid at:</strong> {{ payment.paid_at ?? '-' }}
                    </div>
                    <div>
                        <strong>Provider ref:</strong>
                        {{ payment.provider_ref ?? '-' }}
                    </div>
                </div>

                <div class="flex gap-2">
                    <Link
                        :href="`/admin/payments/${payment.id}/edit`"
                        class="rounded border bg-white px-3 py-2 text-sm hover:bg-gray-50"
                    >
                        Uredi
                    </Link>
                    <a
                        href="/admin/payments"
                        class="rounded border bg-white px-3 py-2 text-sm hover:bg-gray-50"
                    >
                        Natrag
                    </a>
                </div>
            </div>

            <div class="rounded border bg-white p-4">
                <div class="mb-2 font-medium">Payload</div>
                <pre
                    class="overflow-auto rounded border bg-gray-50 p-3 text-xs"
                    >{{ payment.payload ?? '-' }}</pre
                >
            </div>
        </div>
    </AdminLayout>
</template>
