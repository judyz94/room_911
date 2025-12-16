<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue';

interface Department {
    id: number
    name: string
}

interface Employee {
    id: number
    internal_id: string
    first_name: string
    last_name: string
    has_access: boolean
    department: Department
}

interface EmployeeForm {
    internal_id: string
    first_name: string
    last_name: string
    department_id: number | null
    has_access: boolean
}

const employees = ref<Employee[]>([])
const departments = ref<Department[]>([])

const form = ref<EmployeeForm>({
    internal_id: '',
    first_name: '',
    last_name: '',
    department_id: null,
    has_access: false
})

const load = async (): Promise<void> => {
    const [empRes, depRes] = await Promise.all([
        axios.get('/api/employees'),
        axios.get('/api/departments')
    ])

    employees.value = empRes.data.data
    departments.value = depRes.data.data
}

const store = async (): Promise<void> => {
    await axios.post('/api/employees', form.value)

    form.value = {
        internal_id: '',
        first_name: '',
        last_name: '',
        department_id: null,
        has_access: false
    }

    await load()
}

const destroy = async (id: number): Promise<void> => {
    await axios.delete(`/api/employees/${id}`)
    await load()
}

onMounted(load)
</script>

<template>
    <AdminLayout>
        <div class="p-6">
            <h1 class="text-xl font-bold mb-4">Employees</h1>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <input
                    v-model="form.internal_id"
                    placeholder="Internal ID"
                    class="border p-2 rounded"
                />
                <input
                    v-model="form.first_name"
                    placeholder="First name"
                    class="border p-2 rounded"
                />
                <input
                    v-model="form.last_name"
                    placeholder="Last name"
                    class="border p-2 rounded"
                />

                <select
                    v-model="form.department_id"
                    class="border p-2 rounded"
                >
                    <option :value="null">Select department</option>
                    <option
                        v-for="d in departments"
                        :key="d.id"
                        :value="d.id"
                    >
                        {{ d.name }}
                    </option>
                </select>

                <label class="flex items-center gap-2 col-span-2">
                    <input type="checkbox" v-model="form.has_access" />
                    Has access to ROOM 911
                </label>
            </div>

            <button
                @click="store"
                class="bg-blue-600 text-white px-4 py-2 rounded mb-6"
            >
                Add Employee
            </button>

            <table class="w-full border text-sm">
                <thead class="bg-gray-100">
                <tr>
                    <th class="border px-2 py-1">Internal ID</th>
                    <th class="border px-2 py-1">Name</th>
                    <th class="border px-2 py-1">Department</th>
                    <th class="border px-2 py-1">Access</th>
                    <th class="border px-2 py-1"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="e in employees" :key="e.id">
                    <td class="border px-2 py-1">{{ e.internal_id }}</td>
                    <td class="border px-2 py-1">
                        {{ e.first_name }} {{ e.last_name }}
                    </td>
                    <td class="border px-2 py-1">{{ e.department.name }}</td>
                    <td class="border px-2 py-1">
                <span
                    :class="e.has_access ? 'text-green-600' : 'text-red-600'"
                >
                  {{ e.has_access ? 'Granted' : 'Denied' }}
                </span>
                    </td>
                    <td class="border px-2 py-1 text-center">
                        <button
                            @click="destroy(e.id)"
                            class="text-red-500 hover:underline"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>

