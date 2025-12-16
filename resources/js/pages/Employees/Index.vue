<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'

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
const successMessage = ref<string>('')
const editingId = ref<number | null>(null)

const edit = (employee: Employee): void => {
    editingId.value = employee.id

    form.value = {
        internal_id: employee.internal_id,
        first_name: employee.first_name,
        last_name: employee.last_name,
        department_id: employee.department.id,
        has_access: employee.has_access
    }
}

const resetForm = (): void => {
    form.value = {
        internal_id: '',
        first_name: '',
        last_name: '',
        department_id: null,
        has_access: false
    }
    editingId.value = null
}

const storeOrUpdate = async (): Promise<void> => {
    if (editingId.value) {
        await axios.put(`/api/employees/${editingId.value}`, form.value)
        successMessage.value = 'Employee updated successfully.'
    } else {
        await axios.post('/api/employees', form.value)
        successMessage.value = 'Employee created successfully.'
    }

    resetForm()
    await load()
}

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

const destroy = async (id: number): Promise<void> => {
    await axios.delete(`/api/employees/${id}`)
    successMessage.value = 'Employee deleted successfully.'
    await load()
}

onMounted(load)
</script>

<template>
    <AdminLayout>
        <div class="p-6 w-full">

            <h1 class="text-xl font-bold mb-4">Employees</h1>

            <!-- Success message -->
            <div
                v-if="successMessage"
                class="mb-6 bg-green-50 border border-green-200
                       text-green-700 rounded-lg p-4 text-sm font-medium"
            >
                {{ successMessage }}
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl shadow p-8 w-full mb-8">

                <form class="grid grid-cols-2 gap-6">

                    <input
                        v-model="form.internal_id"
                        placeholder="Internal ID"
                        class="col-span-2 w-full px-4 py-3 border border-gray-300 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-blue-600"
                    />

                    <input
                        v-model="form.first_name"
                        placeholder="First name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-blue-600"
                    />

                    <input
                        v-model="form.last_name"
                        placeholder="Last name"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-blue-600"
                    />

                    <select
                        v-model="form.department_id"
                        class="col-span-2 w-full px-4 py-3 border border-gray-300 rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-blue-600"
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

                    <label class="col-span-2 flex items-center gap-3 text-sm font-medium">
                        <input
                            type="checkbox"
                            v-model="form.has_access"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"
                        />
                        Has access to ROOM 911
                    </label>

                    <div class="col-span-2 flex justify-end gap-3 pt-4">

                        <button
                            v-if="editingId"
                            type="button"
                            @click="resetForm"
                            class="px-6 py-3 rounded-lg border border-gray-300
               text-gray-700 font-medium hover:bg-gray-100 transition"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            @click="storeOrUpdate"
                            class="bg-blue-600 text-white px-8 py-3 rounded-lg
               font-semibold hover:bg-blue-700 transition"
                        >
                            {{ editingId ? 'Update Employee' : 'Add Employee' }}
                        </button>

                    </div>

                </form>
            </div>

            <!-- List Card -->
            <div class="bg-white rounded-xl shadow p-6 w-full">

                <table class="w-full text-sm">
                    <thead class="border-b text-gray-600">
                    <tr>
                        <th class="py-2 text-left">Internal ID</th>
                        <th class="py-2 text-left">Name</th>
                        <th class="py-2 text-left">Department</th>
                        <th class="py-2 text-left">Access</th>
                        <th class="py-2"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="e in employees"
                        :key="e.id"
                        class="border-b last:border-b-0"
                    >
                        <td class="py-3">{{ e.internal_id }}</td>
                        <td class="py-3">
                            {{ e.first_name }} {{ e.last_name }}
                        </td>
                        <td class="py-3">{{ e.department.name }}</td>
                        <td class="py-3">
                                <span
                                    :class="e.has_access
                                        ? 'text-green-600 font-semibold'
                                        : 'text-red-600 font-semibold'"
                                >
                                    {{ e.has_access ? 'Granted' : 'Denied' }}
                                </span>
                        </td>

                        <td class="py-3 text-right flex justify-end gap-4">

                            <button
                                @click="edit(e)"
                                class="text-blue-600 hover:underline font-medium"
                            >
                                Edit
                            </button>

                            <button
                                @click="destroy(e.id)"
                                class="text-red-500 hover:underline font-medium"
                            >
                                Delete
                            </button>

                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>

        </div>
    </AdminLayout>
</template>

