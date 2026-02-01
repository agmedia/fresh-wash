<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue';

type ToastType = 'success' | 'error' | 'warning' | 'info';

type Toast = {
    id: string;
    type: ToastType;
    message: string;
    timeoutMs: number;
    createdAt: number;
};

const toasts = reactive<Toast[]>([]);
const hovered = ref(false);

const MAX_TOASTS = 3;
const DEFAULT_TIMEOUT = 3200;

const uid = () => `${Date.now()}-${Math.random().toString(16).slice(2)}`;

const push = (type: ToastType, message: string, timeoutMs = DEFAULT_TIMEOUT) => {
    if (!message) return;

    noteActivity(); // UX: reset autohide when new toast comes

    const toast: Toast = {
        id: uid(),
        type,
        message,
        timeoutMs,
        createdAt: Date.now(),
    };

    // newest on top
    toasts.unshift(toast);

    // cap stack
    if (toasts.length > MAX_TOASTS) toasts.splice(MAX_TOASTS);

    scheduleRemove(toast.id, timeoutMs);
};

const remove = (id: string) => {
    const idx = toasts.findIndex((t) => t.id === id);
    if (idx !== -1) toasts.splice(idx, 1);
};

// pause/resume timers on hover
const timers = new Map<string, { startedAt: number; remaining: number; t: number }>();

const scheduleRemove = (id: string, ms: number) => {
    timers.get(id)?.t && clearTimeout(timers.get(id)!.t);

    const startedAt = Date.now();
    const t = window.setTimeout(() => {
        timers.delete(id);
        remove(id);
    }, ms);

    timers.set(id, { startedAt, remaining: ms, t });
};

const pauseAll = () => {
    hovered.value = true;
    for (const [id, meta] of timers.entries()) {
        clearTimeout(meta.t);
        const elapsed = Date.now() - meta.startedAt;
        const remaining = Math.max(0, meta.remaining - elapsed);
        timers.set(id, { ...meta, remaining, t: 0 });
    }
};

const resumeAll = () => {
    hovered.value = false;
    for (const [id, meta] of timers.entries()) {
        if (meta.remaining <= 0) {
            timers.delete(id);
            remove(id);
            continue;
        }
        scheduleRemove(id, meta.remaining);
    }
};

// UX: kad se doda novi toast, ako user hovera, ne briši
const noteActivity = () => {
    if (hovered.value) {
        pauseAll();
        resumeAll();
    }
};

const styleFor = (type: ToastType) => {
    switch (type) {
        case 'success':
            return {
                border: 'border-emerald-500/30',
                bg: 'bg-emerald-500/10',
                text: 'text-emerald-300',
                icon: '✓',
            };
        case 'error':
            return {
                border: 'border-rose-500/30',
                bg: 'bg-rose-500/10',
                text: 'text-rose-300',
                icon: '✕',
            };
        case 'warning':
            return {
                border: 'border-amber-500/30',
                bg: 'bg-amber-500/10',
                text: 'text-amber-300',
                icon: '!',
            };
        default:
            return {
                border: 'border-border',
                bg: 'bg-card',
                text: 'text-foreground',
                icon: 'i',
            };
    }
};

const progressWidth = (toast: Toast) => {
    const meta = timers.get(toast.id);
    if (!meta) return '0%';

    const remaining = hovered.value ? meta.remaining : meta.remaining; // when hovered, remaining stays
    const total = toast.timeoutMs;
    const pct = Math.max(0, Math.min(100, (remaining / total) * 100));
    return `${pct}%`;
};

let offSuccess: (() => void) | null = null;

onMounted(() => {
    offSuccess = router.on('success', (event: any) => {
        const flash = event?.detail?.page?.props?.flash;
        if (flash?.success) push('success', String(flash.success));
        else if (flash?.error) push('error', String(flash.error), 4500);
        else if (flash?.warning) push('warning', String(flash.warning), 4500);
    });
});

onBeforeUnmount(() => {
    offSuccess?.();
    offSuccess = null;
    for (const meta of timers.values()) clearTimeout(meta.t);
    timers.clear();
});

const hasToasts = computed(() => toasts.length > 0);
</script>

<template>
    <div
        v-if="hasToasts"
        class="pointer-events-none fixed right-4 top-4 z-50 w-[360px] max-w-[calc(100vw-2rem)]"
        aria-live="polite"
        aria-relevant="additions"
    >
        <div class="pointer-events-auto space-y-2" @mouseenter="pauseAll" @mouseleave="resumeAll">
            <transition-group name="toast" tag="div" class="space-y-2">
                <div
                    v-for="t in toasts"
                    :key="t.id"
                    class="overflow-hidden rounded-xl border bg-card shadow-sm card-elev"
                    :class="[styleFor(t.type).border]"
                    role="status"
                >
                    <div class="flex gap-3 p-3">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg text-sm font-semibold"
                            :class="[styleFor(t.type).bg, styleFor(t.type).text]"
                            aria-hidden="true"
                        >
                            {{ styleFor(t.type).icon }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-medium text-foreground">
                                {{ t.message }}
                            </div>
                            <div class="mt-0.5 text-xs text-muted-foreground">
                                {{ t.type === 'success' ? 'Spremljeno' : t.type === 'error' ? 'Greška' : 'Upozorenje' }}
                            </div>
                        </div>

                        <button
                            type="button"
                            class="rounded-md px-2 py-1 text-xs text-muted-foreground hover:text-foreground"
                            @click="remove(t.id)"
                        >
                            ✕
                        </button>
                    </div>

                    <!-- progress bar -->
                    <div class="h-1 w-full bg-border/40">
                        <div
                            class="h-1"
                            :class="t.type === 'success' ? 'bg-emerald-500/60' : t.type === 'error' ? 'bg-rose-500/60' : 'bg-amber-500/60'"
                            :style="{ width: progressWidth(t) }"
                        />
                    </div>
                </div>
            </transition-group>
        </div>
    </div>
</template>

<style scoped>
/* smooth slide+fade */
.toast-enter-active,
.toast-leave-active {
    transition: all 180ms ease;
}
.toast-enter-from {
    opacity: 0;
    transform: translateY(-8px);
}
.toast-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}
</style>
