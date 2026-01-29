<script setup lang="ts">
import FrontLayout from '@/layouts/front/FrontLayout.vue';

defineProps<{ orderId: number | string }>();
</script>

<template>
    <FrontLayout :title="`Narudžba #${orderId}`">
        <div class="rounded border border-gray-800 bg-gray-900 p-4">
            <div class="text-sm text-gray-400">
                Ovdje ide prikaz: status, stavke, plaćanja, QR token,
                obavijesti.
            </div>
        </div>
    </FrontLayout>
</template>
