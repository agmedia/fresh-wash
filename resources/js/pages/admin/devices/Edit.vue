<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

type LocationOption = { id: number; name: string };

type Device = {
    id: number;
    location_id: number | null;
    code: string | null;
    name: string | null;
    hardware_uid: string | null;
    model: string | null;
    firmware_version: string | null;
    ip_address: string | null;
    mac_address: string | null;
    status: 'online' | 'offline' | 'maintenance' | string;
    is_active: boolean;
    note: string | null;
};

const { device, locations } = defineProps<{
    device: Device;
    locations: LocationOption[];
}>();

const form = useForm({
    location_id: device.location_id ?? null,
    code: device.code ?? '',
    name: device.name ?? '',
    hardware_uid: device.hardware_uid ?? '',
    model: device.model ?? '',
    firmware_version: device.firmware_version ?? '',
    ip_address: device.ip_address ?? '',
    mac_address: device.mac_address ?? '',
    status: (device.status ?? 'offline') as Device['status'],
    is_active: !!device.is_active,
    note: device.note ?? '',
});

const processing = computed(() => form.processing);

const submit = () => form.put(`/admin/devices/${device.id}`, { preserveScroll: true });

const showHref = computed(() => `/admin/devices/${device.id}`);
const backHref = '/admin/devices';
</script>

<template>
    <AdminLayout :title="`Uredi uređaj #${device.id}`">
        <div class="max-w-3xl space-y-6">
            <!-- Sticky header -->
            <div class="sticky top-3 z-20">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev">
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-foreground truncate">
                            {{ device.code || `Uređaj #${device.id}` }}
                        </div>
                        <div class="text-xs text-muted-foreground">
                            {{ device.hardware_uid || '-' }} · {{ device.model || '—' }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
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
                <!-- Basic -->
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Osnovno</div>
                        <div class="text-xs text-muted-foreground">Lokacija, status i identifikatori.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Lokacija</label>
                            <select
                                v-model="form.location_id"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            >
                                <option :value="null">—</option>
                                <option v-for="l in locations" :key="l.id" :value="l.id">
                                    {{ l.name }}
                                </option>
                            </select>
                            <p v-if="form.errors.location_id" class="text-xs text-rose-600">
                                {{ form.errors.location_id }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Status</label>
                            <select
                                v-model="form.status"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            >
                                <option value="online">online</option>
                                <option value="offline">offline</option>
                                <option value="maintenance">maintenance</option>
                            </select>
                            <p v-if="form.errors.status" class="text-xs text-rose-600">
                                {{ form.errors.status }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Code *</label>
                            <input
                                v-model="form.code"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="npr. DEV-001"
                            />
                            <p v-if="form.errors.code" class="text-xs text-rose-600">
                                {{ form.errors.code }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Hardware UID *</label>
                            <input
                                v-model="form.hardware_uid"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm font-mono text-foreground
                                       placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="npr. 9F2A-12BC-..."
                            />
                            <p v-if="form.errors.hardware_uid" class="text-xs text-rose-600">
                                {{ form.errors.hardware_uid }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Naziv</label>
                            <input
                                v-model="form.name"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="npr. Gateway 1"
                            />
                            <p v-if="form.errors.name" class="text-xs text-rose-600">
                                {{ form.errors.name }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Network / meta -->
                <section class="space-y-4">
                    <div>
                        <div class="text-sm font-semibold text-foreground">Tehnički podaci</div>
                        <div class="text-xs text-muted-foreground">Model, firmware i mreža.</div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Model</label>
                            <input
                                v-model="form.model"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.model" class="text-xs text-rose-600">
                                {{ form.errors.model }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">Firmware</label>
                            <input
                                v-model="form.firmware_version"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                            />
                            <p v-if="form.errors.firmware_version" class="text-xs text-rose-600">
                                {{ form.errors.firmware_version }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">IP</label>
                            <input
                                v-model="form.ip_address"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="192.168.0.10"
                            />
                            <p v-if="form.errors.ip_address" class="text-xs text-rose-600">
                                {{ form.errors.ip_address }}
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-medium text-foreground">MAC</label>
                            <input
                                v-model="form.mac_address"
                                class="w-full rounded border border-input bg-background px-3 py-2 text-sm font-mono
                                       focus:outline-none focus:ring-2 focus:ring-ring/40"
                                placeholder="AA:BB:CC:DD:EE:FF"
                            />
                            <p v-if="form.errors.mac_address" class="text-xs text-rose-600">
                                {{ form.errors.mac_address }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Active + note -->
                <section class="space-y-4">
                    <div class="flex items-center justify-between gap-3 rounded border border-border bg-card p-4">
                        <div>
                            <div class="text-sm font-semibold text-foreground">Aktivnost</div>
                            <div class="text-xs text-muted-foreground">Uključi/isključi uređaj u sustavu.</div>
                        </div>

                        <label class="inline-flex items-center gap-2 text-sm text-foreground">
                            <input
                                type="checkbox"
                                v-model="form.is_active"
                                class="h-4 w-4 rounded border border-input bg-background"
                            />
                            Aktivan
                        </label>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">Napomena</label>
                        <textarea
                            v-model="form.note"
                            rows="3"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                            placeholder="Interna napomena..."
                        />
                        <p v-if="form.errors.note" class="text-xs text-rose-600">
                            {{ form.errors.note }}
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

                    <Link
                        :href="showHref"
                        class="text-sm text-muted-foreground hover:text-foreground"
                    >
                        Pregled
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
