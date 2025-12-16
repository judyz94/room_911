<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'

type AccessStatus = 'granted' | 'denied' | 'not_found'

const internalId = ref('')
const message = ref('')
const status = ref<AccessStatus | null>(null)
const loading = ref(false)

const submit = async () => {
    if (!internalId.value) return

    loading.value = true
    message.value = ''
    status.value = null

    try {
        const res = await axios.post('/room-911/access', {
            internal_id: internalId.value,
        })

        status.value = res.data.status
        message.value = res.data.message
        internalId.value = ''
    } catch {
        status.value = 'denied'
        message.value = 'System error. Please try again.'
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800">

        <div class="w-full max-w-md bg-white/95 backdrop-blur shadow-2xl rounded-2xl p-8">

            <h1 class="text-2xl font-extrabold text-center mb-1 tracking-wide">
                ROOM 911
            </h1>

            <p class="text-center text-sm text-gray-500 mb-6">
                Employee Access Control
            </p>

            <form @submit.prevent="submit" class="space-y-5">

                <input
                    v-model="internalId"
                    type="text"
                    placeholder="Internal ID"
                    required
                    autofocus
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-blue-600
                           focus:border-blue-600 transition"
                />

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl
                           font-semibold tracking-wide
                           hover:bg-blue-700 active:bg-blue-800
                           transition-all duration-200
                           disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    {{ loading ? 'Checking access...' : 'Authorize Access' }}
                </button>
            </form>

            <div
                v-if="message"
                :class="[
        'mt-6 text-center text-sm font-semibold p-4 rounded-xl border whitespace-pre-line',
        status === 'granted'
            ? 'bg-green-50 text-green-700 border-green-200'
            : status === 'not_found'
                ? 'bg-yellow-50 text-yellow-700 border-yellow-200'
                : 'bg-red-50 text-red-700 border-red-200'
    ]"
            >
                {{ message }}
            </div>
        </div>
    </div>
</template>


