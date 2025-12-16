<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: '',
})

const submit = () => {
    form.post('/login')
}
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800">

        <div class="w-full max-w-md bg-white/95 backdrop-blur shadow-2xl rounded-2xl p-8">

            <h1 class="text-2xl font-extrabold text-center mb-2 tracking-wide">
                ROOM 911
            </h1>

            <p class="text-center text-sm text-gray-500 mb-6">
                Administrative Access
            </p>

            <form @submit.prevent="submit" class="space-y-5">

                <div class="relative">
                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="Email"
                        required
                        class="w-full pl-5 pr-4 py-3 border border-gray-300 rounded-xl
                   focus:outline-none focus:ring-2 focus:ring-blue-600
                   focus:border-blue-600 transition"
                    />
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
          </span>
                </div>

                <div class="relative">
                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="Password"
                        required
                        class="w-full pl-5  py-3 border border-gray-300 rounded-xl
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
                    :disabled="form.processing"
                >
                    Sign In
                </button>
            </form>

            <div v-if="form.errors.email" class="mt-6 text-center text-sm font-semibold p-3 rounded-xl
                                           bg-red-50 text-red-700 border border-red-200">
                {{ form.errors.email }}
            </div>

        </div>

    </div>
</template>

