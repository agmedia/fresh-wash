<script setup lang="ts">
import AdminLayout from '@/layouts/admin/AdminLayout.vue';

interface User {
    id: number;
    first_name: string | null;
    last_name: string | null;
    email: string;
    address_line1: string | null;
    address_line2: string | null;
    postal_code: string | null;
    city: string | null;
    country_code: string | null;
    phone: string | null;
    whatsapp_opt_in: boolean;
}

const { user } = defineProps<{ user: User }>();

const fullName = `${user.first_name ?? ''} ${user.last_name ?? ''}`.trim();

const editHref = `/admin/users/${user.id}/edit`;
const listHref = `/admin/users`;
</script>

<template>
    <AdminLayout :title="`Korisnik #${user.id}`">
        <div class="max-w-3xl space-y-6">
            <!-- Sticky header (isti UX kao order/payment) -->
            <div class="sticky top-3 z-20">
                <div
                    class="flex flex-wrap items-center justify-between gap-3 rounded border border-border bg-card p-3 card-elev"
                >
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-foreground truncate">
                            {{ fullName || user.email }}
                        </div>
                        <div class="text-xs text-muted-foreground">
                            {{ user.email }}
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a
                            :href="editHref"
                            class="rounded border border-border bg-primary px-4 py-2 text-sm font-medium text-primary-foreground
                                   hover:opacity-90"
                        >
                            Uredi
                        </a>

                        <a
                            :href="listHref"
                            class="text-sm text-muted-foreground hover:text-foreground"
                        >
                            Natrag
                        </a>
                    </div>
                </div>
            </div>

            <!-- Osnovno -->
            <div class="rounded border border-border bg-card p-5 card-elev">
                <div class="mb-3">
                    <div class="text-sm font-semibold text-foreground">Osnovno</div>
                    <div class="text-xs text-muted-foreground">Podaci o korisniku.</div>
                </div>

                <div class="grid gap-2 text-sm">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-muted-foreground">Ime</span>
                        <span class="text-foreground">{{ user.first_name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-muted-foreground">Prezime</span>
                        <span class="text-foreground">{{ user.last_name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-muted-foreground">Email</span>
                        <span class="text-foreground">{{ user.email }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-muted-foreground">Telefon</span>
                        <span class="text-foreground">{{ user.phone ?? '-' }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-muted-foreground">WhatsApp opt-in</span>
                        <span class="text-foreground">{{ user.whatsapp_opt_in ? 'da' : 'ne' }}</span>
                    </div>
                </div>
            </div>

            <!-- Adresa -->
            <div class="rounded border border-border bg-card p-5 card-elev">
                <div class="mb-3">
                    <div class="text-sm font-semibold text-foreground">Adresa</div>
                    <div class="text-xs text-muted-foreground">Podaci o adresi.</div>
                </div>

                <div class="grid gap-2 text-sm">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-muted-foreground">Adresa 1</span>
                        <span class="text-foreground">{{ user.address_line1 ?? '-' }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-muted-foreground">Adresa 2</span>
                        <span class="text-foreground">{{ user.address_line2 ?? '-' }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-muted-foreground">Poštanski broj</span>
                        <span class="text-foreground">{{ user.postal_code ?? '-' }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-muted-foreground">Grad</span>
                        <span class="text-foreground">{{ user.city ?? '-' }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-medium text-muted-foreground">Država</span>
                        <span class="text-foreground">{{ user.country_code ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
