<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import TopBar from '@/pages/front/components/TopBar.vue'

type Reservation = {
    id: number
    status: string
    locker?: {
        id: number
        code: string
        name?: string | null
        location?: { id: number; name: string } | null
    } | null
} | null

const props = defineProps<{ reservation: Reservation }>()

const busy = ref(false)
const message = ref<string | null>(null)
const commandStatus = ref<string | null>(null)

const canOpen = computed(() => {
    if (!props.reservation) return false
    return ['paid', 'active'].includes(props.reservation.status)
})

const pollCommand = async (commandId: number) => {
    // polling svake 1s max 25s
    for (let i = 0; i < 25; i++) {
        const res = await fetch(`/app/access/command/${commandId}`, { headers: { 'Accept': 'application/json' } })
        const data = await res.json()

        commandStatus.value = data.status

        if (data.status === 'acked') {
            message.value = 'Otključano ✅'
            return
        }
        if (data.status === 'failed') {
            message.value = `Neuspjelo ❌ ${data.ack_message ?? ''}`.trim()
            return
        }

        await new Promise(r => setTimeout(r, 1000))
    }

    message.value = 'Čekanje potvrde je isteklo. Pokušaj ponovo.'
}

const openLocker = async () => {
    if (!props.reservation) return
    busy.value = true
    message.value = null
    commandStatus.value = null

    try {
        const res = await fetch('/app/access/open', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content ?? '',
            },
            body: JSON.stringify({ reservation_id: props.reservation.id }),
        })

        const data = await res.json()
        if (!res.ok) {
            message.value = data?.message ?? 'Greška kod slanja komande.'
            busy.value = false
            return
        }

        await pollCommand(data.command_id)
    } catch (e) {
        message.value = 'Greška mreže.'
    } finally {
        busy.value = false
    }
}
</script>

<template>
    <div id="page" class="theme-light" data-highlight="highlight-red">
        <TopBar title="Otključavanje" />

        <div class="page-content footer-bar-clear">
            <div class="card card-style">
                <div class="content">
                    <p class="color-highlight font-600 mb-n1">Pristup</p>
                    <h2 class="font-18 font-700 mb-2">Otključaj mašinu</h2>

                    <div v-if="!reservation" class="opacity-70">
                        Nema aktivne rezervacije.
                        <div class="mt-3">
                            <Link href="/app" class="btn btn-m rounded-s font-13 font-700 bg-highlight">Natrag</Link>
                        </div>
                    </div>

                    <div v-else>
                        <div class="font-14">
                            <div><b>Rezervacija:</b> #{{ reservation.id }}</div>
                            <div><b>Status:</b> {{ reservation.status }}</div>
                            <div v-if="reservation.locker?.location"><b>Lokacija:</b> {{ reservation.locker.location.name }}</div>
                            <div v-if="reservation.locker"><b>Mašina:</b> {{ reservation.locker.code }} <span v-if="reservation.locker.name">- {{ reservation.locker.name }}</span></div>
                        </div>

                        <div v-if="message" class="mt-3 rounded-s px-3 py-2 font-13"
                             :class="message.includes('✅') ? 'bg-green-dark color-white' : 'bg-red-dark color-white'">
                            {{ message }}
                        </div>

                        <div v-if="commandStatus" class="mt-2 font-12 opacity-70">
                            Status komande: {{ commandStatus }}
                        </div>

                        <div class="mt-3">
                            <button
                                type="button"
                                class="btn btn-m rounded-s font-13 font-700 bg-highlight w-100"
                                :disabled="busy || !canOpen"
                                @click="openLocker"
                            >
                                {{ busy ? 'Šaljem...' : 'Otključaj' }}
                            </button>

                            <div v-if="!canOpen" class="mt-2 font-12 color-red-light">
                                Rezervacija nije plaćena/aktivna.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- footer bar možeš ostaviti kao u drugim front view-ovima -->
        </div>
    </div>
</template>