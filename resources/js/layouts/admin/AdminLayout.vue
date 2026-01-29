<script setup lang="ts">
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';

const props = defineProps<{
    title?: string;
}>();
</script>

<template>
    <AppSidebarLayout>
        <template #header>
            <AppHeaderLayout :title="props.title ?? 'Admin panel'" />
        </template>

        <main class="p-6">
            <slot />
        </main>
    </AppSidebarLayout>
</template>
