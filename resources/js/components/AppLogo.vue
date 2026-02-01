<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div class="flex items-center gap-3">
        <div
            class="flex aspect-square size-9 items-center justify-center
                   rounded-lg border border-border bg-card text-foreground"
        >
            <AppLogoIcon class="size-5" />
        </div>

        <div class="grid flex-1 text-left text-sm">
            <span class="mb-0.5 truncate font-semibold leading-tight text-foreground">
                Fresh Wash
            </span>
        </div>
    </div>
</template>
