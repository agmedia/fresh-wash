<script setup lang="ts">
import UserInfo from '@/components/UserInfo.vue';
import {
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { logout } from '@/routes';
import type { User } from '@/types';
import { Globe, LogOut, Settings } from 'lucide-vue-next';

interface Props {
    user: User;
}

defineProps<Props>();

const handleLogout = (e: Event) => {
    e.preventDefault();

    const csrf = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = logout();

    const token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = csrf ?? '';
    form.appendChild(token);

    document.body.appendChild(form);
    form.submit();
};
</script>

<template>
    <DropdownMenuLabel class="p-0 font-normal">
        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
            <UserInfo :user="user" :show-email="true" />
        </div>
    </DropdownMenuLabel>

    <DropdownMenuSeparator />

    <DropdownMenuGroup>
        <!-- SETTINGS -->
        <DropdownMenuItem as-child>
            <a href="/admin/settings" class="block w-full">
                <Settings class="mr-2 h-4 w-4" />
                Postavke
            </a>
        </DropdownMenuItem>

        <!-- FRONT / INFO WEB -->
        <DropdownMenuItem as-child>
            <a href="/" target="_blank" class="block w-full">
                <Globe class="mr-2 h-4 w-4" />
                Javni web
            </a>
        </DropdownMenuItem>
    </DropdownMenuGroup>

    <DropdownMenuSeparator />

    <!-- LOGOUT (native, bez flash/popup) -->
    <DropdownMenuItem as-child>
        <button
            type="button"
            class="block w-full"
            @click="handleLogout"
            data-test="logout-button"
        >
            <span class="flex items-center">
                <LogOut class="mr-2 h-4 w-4" />
                Log out
            </span>
        </button>
    </DropdownMenuItem>
</template>
