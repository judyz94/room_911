<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue';

interface Department {
    id: number
    name: string
}

const departments = ref<Department[]>([])
const name = ref<string>('')

const api = axios.create({
    headers: {
        'X-Inertia': false
    }
})

/*const load = async () => {
    const res = await api.get('/api/departments')
    departments.value = res.data.data
}*/

const load = async (): Promise<void> => {
    const res = await axios.get('/api/departments')
    departments.value = res.data.data
}

const store = async (): Promise<void> => {
    if (!name.value) return

    await axios.post('/api/departments', {
        name: name.value
    })

    name.value = ''
    await load()
}

const destroy = async (id: number): Promise<void> => {
    await axios.delete(`/api/departments/${id}`)
    await load()
}

onMounted(load)
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <h1 class="text-xl font-bold mb-4">Departments</h1>

            <div class="flex gap-2 mb-4">
                <input
                    v-model="name"
                    class="border rounded px-3 py-2 w-full"
                    placeholder="Department name"
                />
                <button
                    @click="store"
                    class="bg-blue-600 text-white px-4 py-2 rounded"
                >
                    Add
                </button>
            </div>

            <ul>
                <li
                    v-for="d in departments"
                    :key="d.id"
                    class="flex justify-between items-center border-b py-2"
                >
                    <span>{{ d.name }}</span>
                    <button
                        @click="destroy(d.id)"
                        class="text-red-500 hover:underline"
                    >
                        Delete
                    </button>
                </li>
            </ul>
        </div>
    </AdminLayout>
</template>
