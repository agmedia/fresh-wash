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

interface Option {
    id: number;
    name?: string;
    email?: string;
    code?: string;
}

const props = defineProps<{
    reservation: Reservation;
    users: Option[];
    lockers: Option[];
}>();

const form = useForm({
    user_id: props.reservation.user_id ?? null,
    locker_id: props.reservation.locker_id ?? null,
    status: props.reservation.status ?? 'pending',
    starts_at: props.reservation.starts_at ?? '',
    ends_at: props.reservation.ends_at ?? '',
    price: props.reservation.price ?? '',
    currency_code: props.reservation.currency_code ?? 'EUR',
    note: props.reservation.note ?? '',
});

const processing = computed(() => form.processing);

const submit = () => {
    form.put(`/admin/reservations/${props.reservation.id}`, { preserveScroll: true });
};

const showHref = computed(() => `/admin/reservations/${props.reservation.id}`);
</script>

<template>
    <AdminLayout :title="`Uredi rezervaciju #${reservation.id}`">
        <div class="max-w-3xl space-y-6">
            <form
                @submit.prevent="submit"
                class="rounded border border-border bg-card p-5 card-elev space-y-6"
            >
                <!-- Selects -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Korisnik</label>
                        <select
                            v-model="form.user_id"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option :value="null" disabled>Odaberi korisnika...</option>
                            <option v-for="u in users" :key="u.id" :value="u.id">
                                {{ u.name ?? u.email ?? `#${u.id}` }}
                            </option>
                        </select>
                        <p v-if="form.errors.user_id" class="text-xs text-rose-600">
                            {{ form.errors.user_id }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Ormarić</label>
                        <select
                            v-model="form.locker_id"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option :value="null" disabled>Odaberi ormarić...</option>
                            <option v-for="l in lockers" :key="l.id" :value="l.id">
                                {{ l.code ?? l.name ?? `#${l.id}` }}
                            </option>
                        </select>
                        <p v-if="form.errors.locker_id" class="text-xs text-rose-600">
                            {{ form.errors.locker_id }}
                        </p>
                    </div>
                </div>

                <!-- Status / Time -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Status</label>
                        <select
                            v-model="form.status"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option value="pending">Pending</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <p v-if="form.errors.status" class="text-xs text-rose-600">
                            {{ form.errors.status }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Valuta</label>
                        <input
                            v-model="form.currency_code"
                            maxlength="3"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm uppercase
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                            placeholder="npr. EUR"
                        />
                        <p v-if="form.errors.currency_code" class="text-xs text-rose-600">
                            {{ form.errors.currency_code }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Početak</label>
                        <input
                            v-model="form.starts_at"
                            type="datetime-local"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.starts_at" class="text-xs text-rose-600">
                            {{ form.errors.starts_at }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Kraj (opcionalno)</label>
                        <input
                            v-model="form.ends_at"
                            type="datetime-local"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.ends_at" class="text-xs text-rose-600">
                            {{ form.errors.ends_at }}
                        </p>
                    </div>
                </div>

                <!-- Price -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Cijena (opcionalno)</label>
                        <input
                            v-model="form.price"
                            type="text"
                            inputmode="decimal"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                            placeholder="npr. 9.99"
                        />
                        <p v-if="form.errors.price" class="text-xs text-rose-600">
                            {{ form.errors.price }}
                        </p>
                    </div>
                </div>

                <!-- Note -->
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-foreground">Napomena</label>
                    <textarea
                        v-model="form.note"
                        rows="4"
                        class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                               placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        placeholder="Interna napomena..."
                    />
                    <p v-if="form.errors.note" class="text-xs text-rose-600">
                        {{ form.errors.note }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground
                               hover:opacity-90 disabled:opacity-60"
                        :disabled="processing"
                    >
                        Spremi promjene
                    </button>

                    <a :href="showHref" class="text-sm text-muted-foreground hover:text-foreground">
                        Pregled
                    </a>

                    <span v-if="processing" class="text-xs text-muted-foreground">
                        Spremanje…
                    </span>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
