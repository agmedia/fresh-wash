<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface LocationOption {
    id: number;
    name: string;
}

const props = defineProps<{
    locations: LocationOption[];
}>();

const form = useForm({
    location_id: props.locations?.[0]?.id ?? null,
    code: '',
    name: '',
    slug: '',
    size: 'M' as 'S' | 'M' | 'L' | 'XL',
    hardware_uid: '',
    is_active: true,
    is_available: true,
    note: '',
});

const processing = computed(() => form.processing);

const submit = () => {
    form.post('/admin/lockers', { preserveScroll: true });
};
</script>

<template>
    <AdminLayout title="Novi ormarić">
        <div class="max-w-3xl space-y-6">
            <form
                @submit.prevent="submit"
                class="rounded border border-border bg-card p-5 card-elev space-y-6"
            >
                <!-- Basic -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-medium text-foreground">
                            Lokacija
                        </label>
                        <select
                            v-model="form.location_id"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option :value="null" disabled>
                                Odaberi lokaciju...
                            </option>
                            <option
                                v-for="loc in locations"
                                :key="loc.id"
                                :value="loc.id"
                            >
                                {{ loc.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.location_id" class="text-xs text-rose-600">
                            {{ form.errors.location_id }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Code
                        </label>
                        <input
                            v-model="form.code"
                            type="text"
                            placeholder="npr. LCK-001"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.code" class="text-xs text-rose-600">
                            {{ form.errors.code }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Naziv (opcionalno)
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="npr. Ormarić A1"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.name" class="text-xs text-rose-600">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-sm font-medium text-foreground">
                            Slug (opcionalno)
                        </label>
                        <input
                            v-model="form.slug"
                            type="text"
                            placeholder="npr. ormarić-a1"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.slug" class="text-xs text-rose-600">
                            {{ form.errors.slug }}
                        </p>
                    </div>
                </div>

                <!-- Meta -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Veličina
                        </label>
                        <select
                            v-model="form.size"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                        </select>
                        <p v-if="form.errors.size" class="text-xs text-rose-600">
                            {{ form.errors.size }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Hardware UID (opcionalno)
                        </label>
                        <input
                            v-model="form.hardware_uid"
                            type="text"
                            placeholder="npr. 9F2A-12BC-..."
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm font-mono
                                   placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                        />
                        <p v-if="form.errors.hardware_uid" class="text-xs text-rose-600">
                            {{ form.errors.hardware_uid }}
                        </p>
                    </div>
                </div>

                <!-- Status -->
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Status
                        </label>
                        <select
                            v-model="form.is_active"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option :value="true">Aktivan</option>
                            <option :value="false">Neaktivan</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-foreground">
                            Dostupnost
                        </label>
                        <select
                            v-model="form.is_available"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-ring/40"
                        >
                            <option :value="true">Dostupan</option>
                            <option :value="false">Nedostupan</option>
                        </select>
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
                        placeholder="Upiši napomenu..."
                        class="w-full rounded border border-input bg-background px-3 py-2 text-sm
                               placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/40"
                    />
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium
                               text-primary-foreground hover:opacity-90 disabled:opacity-60"
                        :disabled="processing"
                    >
                        Kreiraj ormarić
                    </button>

                    <a
                        href="/admin/lockers"
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
