<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    BookOpen,
    Folder,
    LayoutGrid,
    MapPin,
    Box,
    CalendarFold,
    Users,
    UserCheck,
    Shield,
    Sparkles,
    BadgeEuro,
    ShoppingCart,
    CreditCard,
    HeartPulse, MessageCircleMore, FileText, Tags, FolderOpen
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const platformNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/admin/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Lokacije',
        href: '/admin/locations',
        icon: MapPin,
    },
    {
        title: 'Ormarići - Lockers',
        href: '/admin/lockers',
        icon: Box,
    },
    {
        title: 'Rezervacije',
        href: '/admin/reservations',
        icon: CalendarFold,
        children: [
            {
                title: 'Pregled',
                href: '/admin/reservations',
                icon: CalendarFold,
            },
            { title: 'Narudžbe', href: '/admin/orders', icon: ShoppingCart },
            { title: 'Plaćanja', href: '/admin/payments', icon: CreditCard },
        ],
    },
    {
        title: 'Korisnici',
        href: '/admin/users',
        icon: Users,
    },
    {
        title: 'Notifikacije',
        href: '/admin/notifications',
        icon: MessageCircleMore,
    },
        {
        title: 'Stranice',
        href: '/admin/pages',
        icon: FileText,
        children: [
            {
                title: 'Sadržaj',
                href: '/admin/pages',
                icon: FileText,
            },
            {
                title: 'Kategorije',
                href: '/admin/page-categories',
                icon: FolderOpen,
            },
            {
                title: 'Tagovi',
                href: '/admin/page-tags',
                icon: Tags,
            },
        ],
    },
];

const catalogNavItems: NavItem[] = [
    {
        title: 'Servisi',
        href: '/admin/catalog/services',
        icon: Sparkles,
    },
    {
        title: 'Tarife',
        href: '/admin/catalog/tariffs',
        icon: BadgeEuro,
    },
    {
        title: 'Uređaji',
        href: '/admin/devices',
        icon: HeartPulse,
    },
];

const settingsNavItems: NavItem[] = [
    {
        title: 'Moj profil',
        href: '/admin/settings/profile',
        icon: UserCheck, // ili neki drugi icon
    },
    {
        title: 'Uloge i Odobrenja',
        href: '/admin/settings/roles',
        icon: Shield,
    },
];

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link href="/admin/dashboard">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="platformNavItems" label="Navigacija" />

            <div class="my-1 px-4">
                <div class="h-px w-full bg-border" />
            </div>

            <NavMain :items="catalogNavItems" label="Katalog" />

            <div class="my-1 px-4">
                <div class="h-px w-full bg-border" />
            </div>

            <NavMain :items="settingsNavItems" label="Postavke" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
