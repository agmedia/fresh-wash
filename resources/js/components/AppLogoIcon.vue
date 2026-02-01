<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        :class="className"
        v-bind="$attrs"
        fill="none"
        stroke="currentColor"
        stroke-width="1.6"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <rect x="3" y="2" width="18" height="20" rx="2" />
        <circle cx="12" cy="13" r="5" />
        <path d="M9 13c1-1 2-1 3 0s2 1 3 0" />
    </svg>



</template>



