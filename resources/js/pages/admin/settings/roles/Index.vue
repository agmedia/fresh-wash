<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';
import { useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

type Role = { id: number; name: string; title: string | null };
type Ability = {
    id: number;
    name: string;
    title: string | null;
    options?: any;
};

const props = defineProps<{
    roles: Role[];
    abilities: Ability[];
    allowed: Record<number, Record<number, boolean>>;
    canManagePermissions: boolean;
}>();

const groupKeyFor = (a: Ability) => {
    const opt = a.options as any;
    if (opt && (opt.group || opt.group_name))
        return (opt.group || opt.group_name) as string;
    const name = a.name || '';
    const i = name.indexOf('.');
    return i > 0 ? name.slice(0, i) : 'other';
};

const GROUP_LABELS: Record<string, string> = {
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

const groupLabelFor = (key: string) => GROUP_LABELS[key] ?? key;

const groupOptions = (() => {
    const keys = new Set(Object.keys(GROUP_LABELS));

    for (const a of props.abilities) {
        keys.add(groupKeyFor(a));
    }

    return Array.from(keys)
        .map((key) => ({ key, label: groupLabelFor(key) }))
        .sort((a, b) => a.label.localeCompare(b.label));
})();

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

// init matrix
const matrixInit: Record<number, Record<number, boolean>> = {};
for (const r of props.roles) {
    matrixInit[r.id] = {};
    for (const a of props.abilities) {
        matrixInit[r.id][a.id] = !!props.allowed?.[r.id]?.[a.id];
    }
}

const form = useForm({ matrix: matrixInit });
const abilityForm = useForm({
    name: '',
    title: '',
    group: 'other' as string, // key grupe
});

const isSaving = ref(false);
const savedOk = ref(false);

const openGroups = ref<Record<string, boolean>>({});
for (const g of abilityGroups) openGroups.value[g.key] = false;
abilityGroups.forEach((g, i) => {
    openGroups.value[g.key] = i === 0;
});

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
        onSuccess: () => abilityForm.reset(),
    });
};

const roleLabel = (r: Role) => r.title || r.name;
</script>

<template>
    <AdminLayout title="Postavke · Uloge i odobrenja">
        <div class="space-y-4">
            <!-- Add ability -->
            <section
                v-if="canManagePermissions"
                class="card-elev rounded border border-border bg-card p-4"
            >
                <div class="mb-3">
                    <div class="text-sm font-semibold text-foreground">
                        Dodaj novo odobrenje
                    </div>
                    <div class="text-xs text-muted-foreground">
                        Npr. <span class="font-mono">reservations.view</span>
                    </div>
                </div>

                <form
                    @submit.prevent="createAbility"
                    class="grid gap-3 md:grid-cols-3"
                >
                    <div>
                        <label
                            class="mb-1 block text-xs font-medium text-muted-foreground"
                        >
                            Name (slug)
                        </label>
                        <input
                            v-model="abilityForm.name"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground focus:ring-2 focus:ring-ring/40 focus:outline-none"
                        />
                        <div
                            v-if="abilityForm.errors.name"
                            class="mt-1 text-xs text-rose-600"
                        >
                            {{ abilityForm.errors.name }}
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <label
                            class="mb-1 block text-xs font-medium text-muted-foreground"
                        >
                            Title
                        </label>
                        <input
                            v-model="abilityForm.title"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground focus:ring-2 focus:ring-ring/40 focus:outline-none"
                        />
                        <div
                            v-if="abilityForm.errors.title"
                            class="mt-1 text-xs text-rose-600"
                        >
                            {{ abilityForm.errors.title }}
                        </div>
                    </div>

                    <div class="md:col-span-1">
                        <label
                            class="mb-1 block text-xs font-medium text-muted-foreground"
                        >
                            Grupa
                        </label>
                        <select
                            v-model="abilityForm.group"
                            class="w-full rounded border border-input bg-background px-3 py-2 text-sm text-foreground focus:ring-2 focus:ring-ring/40 focus:outline-none"
                        >
                            <option
                                v-for="g in groupOptions"
                                :key="g.key"
                                :value="g.key"
                            >
                                {{ g.label }}
                            </option>
                        </select>
                        <div class="mt-1 text-xs text-muted-foreground">
                            Odaberi gdje se prikazuje u tablici.
                        </div>
                    </div>

                    <div class="flex items-center gap-3 md:col-span-3">
                        <button
                            type="submit"
                            class="rounded border border-border bg-card px-4 py-2 text-sm font-medium hover:bg-muted"
                            :disabled="abilityForm.processing"
                        >
                            Dodaj
                        </button>
                        <span
                            v-if="abilityForm.recentlySuccessful"
                            class="text-sm text-emerald-600"
                        >
                            Dodano.
                        </span>
                    </div>
                </form>
            </section>

            <!-- Save status -->
            <div class="text-sm">
                <span v-if="isSaving" class="text-muted-foreground"
                    >Spremam…</span
                >
                <span v-else-if="savedOk" class="text-emerald-600"
                    >Spremljeno</span
                >
                <span v-else class="text-muted-foreground">
                    Promjene se spremaju automatski
                </span>
            </div>

            <!-- Matrix -->
            <div
                class="card-elev overflow-auto rounded border border-border bg-card"
            >
                <table class="min-w-full text-sm text-foreground">
                    <thead class="sticky top-0 bg-muted/70 backdrop-blur">
                        <tr>
                            <th
                                class="px-3 py-2 text-left text-xs font-semibold tracking-wide text-muted-foreground uppercase"
                            >
                                Odobrenje
                            </th>
                            <th
                                v-for="r in roles"
                                :key="r.id"
                                class="px-3 py-2 text-center text-xs font-semibold tracking-wide text-muted-foreground uppercase"
                            >
                                {{ roleLabel(r) }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <template v-for="g in abilityGroups" :key="g.key">
                            <tr class="border-t border-border/60 bg-muted/20">
                                <td
                                    :colspan="roles.length + 1"
                                    class="px-3 py-2"
                                >
                                    <button
                                        type="button"
                                        class="flex w-full items-center justify-between"
                                        @click="toggleGroup(g.key)"
                                    >
                                        <span class="font-semibold">
                                            {{ g.label }}
                                        </span>
                                        <span
                                            class="text-xs text-muted-foreground"
                                        >
                                            {{
                                                openGroups[g.key]
                                                    ? 'Sakrij'
                                                    : 'Prikaži'
                                            }}
                                        </span>
                                    </button>
                                </td>
                            </tr>

                            <template v-if="openGroups[g.key]">
                                <tr
                                    v-for="a in g.abilities"
                                    :key="a.id"
                                    class="border-t border-border/60 even:bg-muted/30 hover:bg-muted/50"
                                >
                                    <td class="px-3 py-2">
                                        <div class="font-medium">
                                            {{ a.title || a.name }}
                                        </div>
                                        <div
                                            class="font-mono text-xs text-muted-foreground"
                                        >
                                            {{ a.name }}
                                        </div>
                                    </td>

                                    <td
                                        v-for="r in roles"
                                        :key="r.id"
                                        class="px-3 py-2 text-center"
                                    >
                                        <label
                                            class="mx-auto inline-flex h-9 w-9 items-center justify-center rounded-md border border-border bg-background hover:bg-muted"
                                        >
                                            <input
                                                type="checkbox"
                                                v-model="
                                                    form.matrix[r.id][a.id]
                                                "
                                                class="h-4 w-4 accent-[var(--foreground)]"
                                                @change="queueSave"
                                            />
                                        </label>
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
