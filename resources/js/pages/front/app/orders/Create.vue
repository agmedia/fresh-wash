<script setup lang="ts">
import FrontLayout from '@/layouts/front/FrontLayout.vue';
</script>

<template>
    <FrontLayout title="Nova narudžba">
        <div class="rounded border border-gray-800 bg-gray-900 p-4">
            <div class="text-sm font-semibold">
                Wizard (lokacija → veš mašina → servis)
            </div>
            <div class="mt-2 text-sm text-gray-400">
                Sljedeći korak: iz controllera poslati lokacije, pa dinamički
                dohvat mašina i servisa.
            </div>
        </div>
    </FrontLayout>
</template>
