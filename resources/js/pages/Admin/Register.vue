<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { computed } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const page = usePage()

const successMessage = computed(() => page.props.flash?.success)

const submit = () => {
    form.post('/user/register', {
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <AdminLayout>
        <div class="p-6 w-full">
            <h1 class="text-xl font-bold mb-4">Create Admin User</h1>

            <div
                v-if="successMessage"
                class="mb-6 bg-green-50 border border-green-200
                       text-green-700 rounded-lg p-4 text-sm font-medium"
            >
                {{ successMessage }}
            </div>

            <div class="bg-white rounded-xl shadow p-8 w-full">
                <form @submit.prevent="submit" class="space-y-6">

                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                       focus:outline-none focus:ring-2 focus:ring-blue-600"
                    />

                    <input
                        v-model="form.email"
                        type="email"
                        placeholder="Email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                       focus:outline-none focus:ring-2 focus:ring-blue-600"
                    />

                    <input
                        v-model="form.password"
                        type="password"
                        placeholder="Password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                       focus:outline-none focus:ring-2 focus:ring-blue-600"
                    />

                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        placeholder="Confirm Password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                       focus:outline-none focus:ring-2 focus:ring-blue-600"
                    />

                    <div class="flex justify-end pt-4">
                        <button
                            type="submit"
                            class="bg-blue-600 text-white px-8 py-3 rounded-lg
                           font-semibold hover:bg-blue-700 transition"
                            :disabled="form.processing"
                        >
                            Create Admin
                        </button>
                    </div>
                </form>

                <div
                    v-if="Object.keys(form.errors).length"
                    class="mt-6 bg-red-50 border border-red-200 text-red-700
                   rounded-lg p-4 text-sm space-y-1"
                >
                    <div v-for="(error, key) in form.errors" :key="key">
                        {{ error }}
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

