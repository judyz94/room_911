<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue';

interface Department {
    id: number
    name: string
}

const departments = ref<Department[]>([])
const formName = ref<string>('')
const editingId = ref<number | null>(null)
const successMessage = ref<string>('')

const load = async (): Promise<void> => {
    const res = await axios.get('/api/departments')
    departments.value = res.data.data
}

const storeOrUpdate = async (): Promise<void> => {
    if (!formName.value) return

    if (editingId.value) {
        await axios.put(`/api/departments/${editingId.value}`, {
            name: formName.value
        })
        successMessage.value = 'Department updated successfully.'
    } else {
        await axios.post('/api/departments', {
            name: formName.value
        })
        successMessage.value = 'Department created successfully.'
    }

    resetForm()
    await load()
}

const edit = (department: Department): void => {
    editingId.value = department.id
    formName.value = department.name
}

const destroy = async (id: number): Promise<void> => {
    await axios.delete(`/api/departments/${id}`)
    successMessage.value = 'Department deleted successfully.'
    await load()
}

const resetForm = (): void => {
    formName.value = ''
    editingId.value = null
}

onMounted(load)
</script>

<template>
    <AdminLayout>
        <div class="p-6 w-full">

            <h1 class="text-xl font-bold mb-4">Departments</h1>

            <div
                v-if="successMessage"
                class="mb-6 bg-green-50 border border-green-200
                       text-green-700 rounded-lg p-4 text-sm font-medium"
            >
                {{ successMessage }}
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow p-8 w-full mb-8">

                <form @submit.prevent="storeOrUpdate" class="space-y-6">

                    <input
                        v-model="formName"
                        type="text"
                        placeholder="Department name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-blue-600"
                    />

                    <div class="flex justify-end gap-3 pt-4">
                        <button
                            v-if="editingId"
                            type="button"
                            @click="resetForm"
                            class="px-6 py-2 rounded-lg border border-gray-300
                                   text-gray-700 font-semibold hover:bg-gray-100 transition"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg
                                   font-semibold hover:bg-blue-700 transition"
                        >
                            {{ editingId ? 'Update Department' : 'Create Department' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- List -->
            <div class="bg-white rounded-xl shadow p-6 w-full">
                <ul>
                    <li
                        v-for="d in departments"
                        :key="d.id"
                        class="flex justify-between items-center border-b py-3"
                    >
                        <span class="font-medium">{{ d.name }}</span>

                        <div class="flex gap-4">
                            <button
                                @click="edit(d)"
                                class="text-blue-600 hover:underline font-medium"
                            >
                                Edit
                            </button>
                            <button
                                @click="destroy(d.id)"
                                class="text-red-500 hover:underline font-medium"
                            >
                                Delete
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </AdminLayout>
</template>

