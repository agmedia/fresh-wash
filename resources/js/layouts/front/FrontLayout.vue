<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    title?: string;
}>();

const page = usePage();
const user = (page.props as any)?.auth?.user;
</script>

<template>
    <div class="min-h-screen bg-gray-950 text-gray-100">
        <header class="border-b border-gray-800">
            <div
                class="mx-auto flex max-w-6xl items-center justify-between px-4 py-4"
            >
                <Link href="/" class="text-sm font-semibold tracking-wide">
                    Locker • PWA
                </Link>

                <nav class="flex items-center gap-2 text-sm">
                    <Link
                        href="/app"
                        class="rounded px-3 py-2 hover:bg-gray-900"
                        >Dashboard</Link
                    >
                    <Link
                        href="/app/nova-narudzba"
                        class="rounded px-3 py-2 hover:bg-gray-900"
                        >Nova narudžba</Link
                    >
                    <Link
                        href="/app/narudzbe"
                        class="rounded px-3 py-2 hover:bg-gray-900"
                        >Narudžbe</Link
                    >
                    <Link
                        href="/app/profil"
                        class="rounded px-3 py-2 hover:bg-gray-900"
                        >Profil</Link
                    >

                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="ml-2 rounded bg-gray-800 px-3 py-2 hover:bg-gray-700"
                    >
                        Odjava
                    </Link>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8">
            <div v-if="title" class="mb-6 text-xl font-semibold">
                {{ title }}
            </div>
            <slot />
        </main>

        <footer class="border-t border-gray-800">
            <div class="mx-auto max-w-6xl px-4 py-6 text-xs text-gray-500">
                Prijavljen:
                <span class="text-gray-300">{{ user?.email ?? '-' }}</span>
            </div>
        </footer>
    </div>
</template>
