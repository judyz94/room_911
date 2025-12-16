<script setup lang="ts">
import { ref } from 'vue'
import axios from 'axios'

const internalId = ref('')
const message = ref('')
const granted = ref(false)

const submit = async () => {
    try {
        const res = await axios.post('/login', {
            internal_id: internalId.value
        })

        granted.value = res.data.granted
        message.value = res.data.message
        internalId.value = ''
    } catch {
        granted.value = false
        message.value = 'Unexpected error'
    }
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800">

        <div class="w-full max-w-md bg-white/95 backdrop-blur shadow-2xl rounded-2xl p-8">

            <h1 class="text-2xl font-extrabold text-center mb-2 tracking-wide">
                ROOM 911
            </h1>

            <form @submit.prevent="submit" class="space-y-5">

                <div class="relative">
                    <input
                        v-model="internalId"
                        type="text"
                        placeholder="Internal ID"
                        required
                        class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl
                   focus:outline-none focus:ring-2 focus:ring-blue-600
                   focus:border-blue-600 transition"
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl
                 font-semibold tracking-wide
                 hover:bg-blue-700 active:bg-blue-800
                 transition-all duration-200"
                >
                    Authorize Access
                </button>
            </form>

            <div
                v-if="message"
                :class="[
          'mt-6 text-center text-sm font-semibold p-3 rounded-xl border',
          granted
            ? 'bg-green-50 text-green-700 border-green-200'
            : 'bg-red-50 text-red-700 border-red-200'
        ]"
            >
                {{ message }}
            </div>

        </div>

    </div>
</template>

<style scoped>

</style>
