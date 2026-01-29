<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

type Role = { id: number; name: string; title: string | null };
type Ability = { id: number; name: string; title: string | null; options?: any };

const props = defineProps<{
    roles: Role[];
    abilities: Ability[];
    allowed: Record<number, Record<number, boolean>>; // roleId -> abilityId -> true
    canManagePermissions: boolean;
}>();

const groupKeyFor = (a: Ability) => {
    const opt = a.options as any;
    if (opt && (opt.group || opt.group_name)) return (opt.group || opt.group_name) as string;
    // default grouping by prefix before '.' (e.g. reservations.view -> reservations)
    const name = a.name || '';
    const i = name.indexOf('.');
    return i > 0 ? name.slice(0, i) : 'other';
};

const groupLabelFor = (key: string) => {
    const map: Record<string, string> = {
        settings: 'Postavke',
        users: 'Korisnici',
        locations: 'Lokacije',
        lockers: 'Ormarići',
        reservations: 'Rezervacije',
        orders: 'Narudžbe',
        payments: 'Plaćanja',
        notifications: 'Notifikacije',
        pages: 'Stranice / Blog',
        faq: 'FAQ',
        other: 'Ostalo',
    };
    return map[key] ?? key;
};

const abilityGroups = (() => {
    const grouped: Record<string, Ability[]> = {};
    for (const a of props.abilities) {
        const key = groupKeyFor(a);
        (grouped[key] ||= []).push(a);
    }
    return Object.entries(grouped)
        .map(([key, abilities]) => ({
            key,
            label: groupLabelFor(key),
            abilities: abilities.sort((x, y) => x.name.localeCompare(y.name)),
        }))
        .sort((a, b) => a.label.localeCompare(b.label));
})();

// build matrix for form
const matrixInit: Record<number, Record<number, boolean>> = {};
for (const r of props.roles) {
    matrixInit[r.id] = {};
    for (const a of props.abilities) {
        matrixInit[r.id][a.id] = !!props.allowed?.[r.id]?.[a.id];
    }
}

const form = useForm({
    matrix: matrixInit,
});

const abilityForm = useForm({
    name: '',
    title: '',
});

const isSaving = ref(false);
const savedOk = ref(false);

const openGroups = ref<Record<string, boolean>>({});
for (const g of abilityGroups) {
    openGroups.value[g.key] = true;
}
const toggleGroup = (key: string) => {
    openGroups.value[key] = !openGroups.value[key];
};

let saveT: number | null = null;

const queueSave = () => {
    savedOk.value = false;
    if (saveT) window.clearTimeout(saveT);
    saveT = window.setTimeout(() => {
        isSaving.value = true;
        router.post(
            '/admin/settings/roles/matrix',
            { matrix: form.matrix },
            {
                preserveScroll: true,
                preserveState: true,
                onFinish: () => {
                    isSaving.value = false;
                    savedOk.value = true;
                },
            },
        );
    }, 300);
};

const createAbility = () => {
    abilityForm.post('/admin/settings/roles/abilities', {
        preserveScroll: true,
        onSuccess: () => {
            abilityForm.reset();
        },
    });
};
</script>

<template>
    <AdminLayout title="Postavke · Uloge i odobrenja">
        <div class="space-y-4">
            <div class="rounded border p-4" v-if="canManagePermissions">
                <div class="mb-3 text-sm font-medium">Dodaj novo odobrenje</div>

                <form @submit.prevent="createAbility" class="grid gap-3 md:grid-cols-3">
                    <div class="md:col-span-1">
                        <label class="block text-xs text-gray-500 mb-1">Name (slug)</label>
                        <input v-model="abilityForm.name" class="w-full rounded border px-3 py-2 text-sm" placeholder="npr. reservations.view" />
                        <div v-if="abilityForm.errors.name" class="mt-1 text-xs text-red-600">{{ abilityForm.errors.name }}</div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">Title</label>
                        <input v-model="abilityForm.title" class="w-full rounded border px-3 py-2 text-sm" placeholder="npr. View reservations" />
                        <div v-if="abilityForm.errors.title" class="mt-1 text-xs text-red-600">{{ abilityForm.errors.title }}</div>
                    </div>

                    <div class="md:col-span-3 flex items-center gap-3">
                        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm text-white" :disabled="abilityForm.processing">
                            Dodaj
                        </button>
                        <span v-if="abilityForm.recentlySuccessful" class="text-sm text-green-700">Dodano.</span>
                    </div>
                </form>
            </div>

            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span v-if="isSaving">Spremam...</span>
                    <span v-else-if="savedOk">Spremljeno.</span>
                </div>
            </div>

            <div class="overflow-auto rounded border">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-900 sticky top-0 z-10">
                        <tr>
                            <th class="px-3 py-2 text-left">Odobrenje</th>
                            <th v-for="r in roles" :key="r.id" class="px-3 py-2 text-center whitespace-nowrap">
                                {{ r.title || r.name }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <template v-for="g in abilityGroups" :key="g.key">
                            <tr class="bg-gray-900/30 border-t">
                                <td class="px-3 py-2" :colspan="roles.length + 1">
                                    <button
                                        type="button"
                                        class="flex w-full items-center justify-between text-left"
                                        @click="toggleGroup(g.key)"
                                    >
                                        <span class="font-medium text-gray-200">
                                            {{ g.label }}
                                            <span class="ml-2 text-xs text-gray-500">({{ g.abilities.length }})</span>
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ openGroups[g.key] ? 'Sakrij' : 'Prikaži' }}
                                        </span>
                                    </button>
                                </td>
                            </tr>

                            <template v-if="openGroups[g.key]">
                                <tr v-for="a in g.abilities" :key="a.id" class="border-t">
                                    <td class="px-3 py-2">
                                        <div class="font-medium">{{ a.title || a.name }}</div>
                                        <div class="text-xs text-gray-500">{{ a.name }}</div>
                                    </td>

                                    <td v-for="r in roles" :key="r.id" class="px-3 py-2 text-center">
                                        <input
                                            type="checkbox"
                                            v-model="form.matrix[r.id][a.id]"
                                            class="h-4 w-4"
                                            @change="queueSave"
                                        />
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>