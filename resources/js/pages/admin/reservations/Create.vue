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
    form.post('/admin/reservations', { preserveScroll: true });
};

const userLabel = (u: UserOption) => {
    const full =
        u.name ??
        [u.first_name, u.last_name].filter(Boolean).join(' ').trim();
    return full ? `${full} — ${u.email}` : u.email;
};

const lockerLabel = (l: LockerOption) => {
    const bits = [l.code];
    if (l.name) bits.push(l.name);
    if (l.location?.name) bits.push(l.location.name);
    return bits.join(' — ');
};
</script>

<template>
    <AdminLayout title="Nova rezervacija">
        <div class="max-w-3xl space-y-6">
            <form
                @submit.prevent="submit"
                class="rounded border border-border bg-card p-5 card-elev space-y-6"
            >
                <!-- Selects -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Korisnik
                        </label>
                        <select
                            v-model="form.user_id"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option :value="null" disabled>
                                Odaberi korisnika...
                            </option>
                            <option v-for="u in users" :key="u.id" :value="u.id">
                                {{ userLabel(u) }}
                            </option>
                        </select>
                        <p v-if="form.errors.user_id" class="text-xs text-rose-600">
                            {{ form.errors.user_id }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Ormarić
                        </label>
                        <select
                            v-model="form.locker_id"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option :value="null" disabled>
                                Odaberi ormarić...
                            </option>
                            <option v-for="l in lockers" :key="l.id" :value="l.id">
                                {{ lockerLabel(l) }}
                            </option>
                        </select>
                        <p v-if="form.errors.locker_id" class="text-xs text-rose-600">
                            {{ form.errors.locker_id }}
                        </p>
                    </div>
                </div>

                <!-- Status / Price / Currency -->
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Status
                        </label>
                        <select
                            v-model="form.status"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option value="draft">draft</option>
                            <option value="active">active</option>
                            <option value="ended">ended</option>
                            <option value="cancelled">cancelled</option>
                        </select>
                        <p v-if="form.errors.status" class="text-xs text-rose-600">
                            {{ form.errors.status }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Cijena (opcionalno)
                        </label>
                        <input
                            v-model="form.price"
                            type="text"
                            inputmode="decimal"
                            placeholder="npr. 9.99"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.price" class="text-xs text-rose-600">
                            {{ form.errors.price }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Valuta
                        </label>
                        <input
                            v-model="form.currency_code"
                            maxlength="3"
                            placeholder="EUR"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm uppercase
                                   placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.currency_code" class="text-xs text-rose-600">
                            {{ form.errors.currency_code }}
                        </p>
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Početak
                        </label>
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
                        <label class="text-sm font-medium text-foreground">
                            Kraj (opcionalno)
                        </label>
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

                <!-- Note -->
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-foreground">
                        Napomena
                    </label>
                    <textarea
                        v-model="form.note"
                        rows="4"
                        placeholder="Interna napomena..."
                        class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                               placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
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
                        Kreiraj rezervaciju
                    </button>

                    <a
                        href="/admin/reservations"
                        class="text-sm text-muted-foreground hover:text-foreground"
                    >
                        Natrag
                    </a>

                    <span v-if="processing" class="text-xs text-muted-foreground">
                        Spremanje…
                    </span>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
