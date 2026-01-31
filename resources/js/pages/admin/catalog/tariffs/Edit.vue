<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

type Service = { id: number; name: string; code: string };
type Location = { id: number; name: string };

type Tariff = {
    id: number;
    service_id: number;
    location_id: number;
    price_net: string | number;
    vat_rate: string | number;
    price_gross: string | number;
    currency: string;
    is_active: boolean;
    valid_from?: string | null;
    valid_to?: string | null;
};

const { tariff, services, locations, defaults } = defineProps<{
    tariff: Tariff;
    services: Service[];
    locations: Location[];
    defaults: { vat_rate: number; currency: string };
}>();

const form = useForm({
    service_id: tariff.service_id,
    location_id: tariff.location_id,
    price_net: String(tariff.price_net ?? ''),
    vat_rate: String(tariff.vat_rate ?? defaults?.vat_rate ?? 25),
    price_gross: String(tariff.price_gross ?? ''),
    currency: tariff.currency ?? defaults?.currency ?? 'EUR',
    is_active: tariff.is_active ?? true,
    valid_from: (tariff.valid_from ?? '') as any,
    valid_to: (tariff.valid_to ?? '') as any,
});

const processing = computed(() => form.processing);

const showHref = computed(() => `/admin/catalog/tariffs/${tariff.id}`);
const backHref = '/admin/catalog/tariffs';

const selectedService = computed(() => services.find((s) => s.id === form.service_id));
const selectedLocation = computed(() => locations.find((l) => l.id === form.location_id));

/** UX: auto-gross toggle */
const grossAuto = ref(true);

const toNum = (v: unknown) => {
    const n = Number(String(v ?? '').replace(',', '.'));
    return Number.isFinite(n) ? n : 0;
};

const calcGross = () => {
    const net = toNum(form.price_net);
    const vat = toNum(form.vat_rate);
    const gross = net * (1 + vat / 100);
    return (Math.round(gross * 100) / 100).toFixed(2);
};

watch(
    () => [form.price_net, form.vat_rate],
    () => {
        if (grossAuto.value) form.price_gross = calcGross();
    },
    { immediate: true }
);

const submit = () => {
    form.put(`/admin/catalog/tariffs/${tariff.id}`, { preserveScroll: true });
};

/** UX: unsaved indicator */
const initial = JSON.stringify(form.data());
const hasChanges = computed(() => JSON.stringify(form.data()) !== initial);
</script>

<template>
    <AdminLayout :title="`Uredi tarifu #${tariff.id}`">
        <div class="max-w-3xl space-y-6">
            <!-- Sticky actions -->
            <div class="sticky top-3 z-20">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-foreground truncate">
                            {{ selectedService?.name ?? 'Tarifa' }}
                        </div>
                        <div class="text-xs text-muted-foreground">
                            {{ selectedLocation?.name ?? '-' }}
                            <span v-if="selectedService?.code"> · {{ selectedService.code }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span v-if="hasChanges" class="text-xs text-amber-700 dark:text-amber-300">
                            Nespremljene promjene
                        </span>

                        <button
                            type="button"
                            @click="submit"
                            class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground
                                   hover:opacity-90 disabled:opacity-60"
                            :disabled="processing"
                        >
                            {{ processing ? 'Spremanje…' : 'Spremi' }}
                        </button>

                        <a :href="showHref" class="text-sm text-muted-foreground hover:text-foreground">
                            Pregled
                        </a>

                        <a :href="backHref" class="text-sm text-muted-foreground hover:text-foreground">
                            Natrag
                        </a>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="submit"
                class="rounded border border-border bg-card p-5 card-elev space-y-6"
            >
                <!-- Selects -->
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Osnovno</div>
                        <div class="text-xs text-muted-foreground">Lokacija i usluga.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Lokacija</label>
                            <select
                                v-model="form.location_id"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            >
                                <option v-for="l in locations" :key="l.id" :value="l.id">
                                    {{ l.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.location_id" class="text-xs text-rose-600">
                                {{ form.errors.location_id }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Usluga</label>
                            <select
                                v-model="form.service_id"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            >
                                <option v-for="s in services" :key="s.id" :value="s.id">
                                    {{ s.name }} ({{ s.code }})
                                </option>
                            </select>
                            <p v-if="form.errors.service_id" class="text-xs text-rose-600">
                                {{ form.errors.service_id }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Amounts -->
                <section class="space-y-4">
                    <div class="flex items-center justify-between gap-3 rounded border border-border bg-card p-4">
                        <div>
                            <div class="text-sm font-semibold text-foreground">Iznosi</div>
                            <div class="text-xs text-muted-foreground">Net, PDV i brutto.</div>
                        </div>

                        <label class="inline-flex items-center gap-2 text-xs text-muted-foreground">
                            <input
                                type="checkbox"
                                v-model="grossAuto"
                                class="h-4 w-4 rounded border border-input bg-background"
                            />
                            Auto izračun brutto
                        </label>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Net</label>
                            <input
                                v-model="form.price_net"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.price_net" class="text-xs text-rose-600">
                                {{ form.errors.price_net }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">PDV %</label>
                            <input
                                v-model="form.vat_rate"
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.vat_rate" class="text-xs text-rose-600">
                                {{ form.errors.vat_rate }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Bruto</label>
                            <input
                                v-model="form.price_gross"
                                :readonly="grossAuto"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40 disabled:opacity-60"
                            />
                            <p v-if="form.errors.price_gross" class="text-xs text-rose-600">
                                {{ form.errors.price_gross }}
                            </p>
                            <p v-if="grossAuto" class="text-xs text-muted-foreground">
                                Brutto se računa: net × (1 + PDV/100)
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Meta -->
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Vrijednost</div>
                        <div class="text-xs text-muted-foreground">Valuta i period primjene.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Valuta</label>
                            <input
                                v-model="form.currency"
                                maxlength="3"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm uppercase
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.currency" class="text-xs text-rose-600">
                                {{ form.errors.currency }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Vrijedi od</label>
                            <input
                                v-model="form.valid_from"
                                type="date"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.valid_from" class="text-xs text-rose-600">
                                {{ form.errors.valid_from }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Vrijedi do</label>
                            <input
                                v-model="form.valid_to"
                                type="date"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.valid_to" class="text-xs text-rose-600">
                                {{ form.errors.valid_to }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded border border-border bg-card p-4">
                        <label class="inline-flex items-center gap-2 text-sm text-foreground">
                            <input
                                id="active"
                                type="checkbox"
                                v-model="form.is_active"
                                class="h-4 w-4 rounded border border-input bg-background"
                            />
                            Aktivno
                        </label>
                        <p v-if="form.errors.is_active" class="mt-1 text-xs text-rose-600">
                            {{ form.errors.is_active }}
                        </p>
                    </div>
                </section>

                <!-- Bottom actions -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground
                               hover:opacity-90 disabled:opacity-60"
                        :disabled="processing"
                    >
                        {{ processing ? 'Spremanje…' : 'Spremi promjene' }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
