<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    device: any;
    locations: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    location_id: props.device.location_id ?? null,
    code: props.device.code ?? '',
    name: props.device.name ?? '',
    hardware_uid: props.device.hardware_uid ?? '',
    model: props.device.model ?? '',
    firmware_version: props.device.firmware_version ?? '',
    ip_address: props.device.ip_address ?? '',
    mac_address: props.device.mac_address ?? '',
    status: props.device.status ?? 'offline',
    is_active: !!props.device.is_active,
    note: props.device.note ?? '',
});

const submit = () => form.put(`/admin/devices/${props.device.id}`);
</script>

<template>
    <AdminLayout :title="`Uredi uređaj #${device.id}`">
        <form @submit.prevent="submit" class="max-w-3xl space-y-4">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm text-gray-400"
                        >Lokacija</label
                    >
                    <select
                        v-model="form.location_id"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    >
                        <option :value="null">—</option>
                        <option
                            v-for="l in props.locations"
                            :key="l.id"
                            :value="l.id"
                        >
                            {{ l.name }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400"
                        >Status</label
                    >
                    <select
                        v-model="form.status"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    >
                        <option value="online">online</option>
                        <option value="offline">offline</option>
                        <option value="maintenance">maintenance</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400"
                        >Code *</label
                    >
                    <input
                        v-model="form.code"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                    <div
                        v-if="form.errors.code"
                        class="mt-1 text-xs text-red-400"
                    >
                        {{ form.errors.code }}
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400"
                        >Hardware UID *</label
                    >
                    <input
                        v-model="form.hardware_uid"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                    <div
                        v-if="form.errors.hardware_uid"
                        class="mt-1 text-xs text-red-400"
                    >
                        {{ form.errors.hardware_uid }}
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400"
                        >Naziv</label
                    >
                    <input
                        v-model="form.name"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400"
                        >Model</label
                    >
                    <input
                        v-model="form.model"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400"
                        >Firmware</label
                    >
                    <input
                        v-model="form.firmware_version"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400">IP</label>
                    <input
                        v-model="form.ip_address"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                </div>

                <div>
                    <label class="mb-1 block text-sm text-gray-400">MAC</label>
                    <input
                        v-model="form.mac_address"
                        class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                    />
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-200">
                <input type="checkbox" v-model="form.is_active" />
                Aktivan
            </label>

            <div>
                <label class="mb-1 block text-sm text-gray-400">Napomena</label>
                <textarea
                    v-model="form.note"
                    rows="3"
                    class="w-full rounded border border-gray-800 bg-gray-900 px-3 py-2 text-sm text-gray-200"
                />
            </div>

            <div class="flex items-center gap-3">
                <button
                    class="rounded bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-500"
                    :disabled="form.processing"
                >
                    Spremi
                </button>
                <Link
                    :href="`/admin/devices/${device.id}`"
                    class="text-sm text-gray-300 hover:underline"
                    >Show</Link
                >
                <Link
                    href="/admin/devices"
                    class="text-sm text-gray-300 hover:underline"
                    >Nazad</Link
                >
            </div>
        </form>
    </AdminLayout>
</template>
