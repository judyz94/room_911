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
    access_logs_count: string
    department: Department
}

interface EmployeeForm {
    internal_id: string
    first_name: string
    last_name: string
    department_id: number | null
    has_access: boolean
}

interface AccessLog {
    id: number
    access_granted: boolean
    attempted_at: string
}

const employees = ref<Employee[]>([])
const departments = ref<Department[]>([])
const successMessage = ref<string>('')
const editingId = ref<number | null>(null)
const search = ref<string>('')
const departmentFilter = ref<number | null>(null)

const accessLogs = ref<AccessLog[]>([])
const selectedEmployee = ref<Employee | null>(null)
const fromDate = ref<string>('')
const toDate = ref<string>('')

const importDepartmentId = ref<number | null>(null)
const csvFile = ref<File | null>(null)

const importMessage = ref<string | null>(null)
const importStatus = ref<'success' | 'error' | null>(null)
const loading = ref(false)

const onFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement
    csvFile.value = target.files?.[0] ?? null
}

const importCsv = async () => {
    importMessage.value = null

    if (!importDepartmentId.value || !csvFile.value) {
        importStatus.value = 'error'
        importMessage.value = 'Please select a department and a CSV file.'
        return
    }

    const formData = new FormData()
    formData.append('department_id', String(importDepartmentId.value))
    formData.append('file', csvFile.value)

    loading.value = true

    try {
        const { data } = await axios.post('/api/employees/import-csv', formData)

        importStatus.value = 'success'
        importMessage.value =
            data.message ?? 'Employees imported successfully.'

        csvFile.value = null
        await load()
    } catch (error: any) {
        importStatus.value = 'error'
        importMessage.value =
            error.response?.data?.message ?? 'Error importing employees.'
    } finally {
        loading.value = false
    }
}

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

const loadAccessLogs = async (employee: Employee): Promise<void> => {
    selectedEmployee.value = employee

    const res = await axios.get(
        `/employees/${employee.id}/access-logs`,
        {
            params: {
                from: fromDate.value,
                to: toDate.value
            }
        }
    )

    accessLogs.value = res.data.data
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
        axios.get('/api/employees', {
            params: {
                search: search.value,
                department_id: departmentFilter.value
            }
        }),
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
                            class="px-6 py-2 rounded-lg border border-gray-300
               text-gray-700 font-medium hover:bg-gray-100 transition"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            @click="storeOrUpdate"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg
             font-semibold hover:bg-blue-700 transition"
                        >
                            {{ editingId ? 'Update Employee' : 'Add Employee' }}
                        </button>
                    </div>

                </form>
            </div>

            <!-- CSV Import -->
            <div class="bg-white rounded-xl shadow p-8 w-full mb-8">
                <h2 class="font-semibold mb-4">Import Employees (CSV)</h2>

                <div class="flex gap-4 items-center">
                    <select
                        v-model="importDepartmentId"
                        class="px-4 py-2 border rounded-lg"
                    >
                        <option :value="null">Select department</option>
                        <option v-for="d in departments" :key="d.id" :value="d.id">
                            {{ d.name }}
                        </option>
                    </select>

                    <input
                        type="file"
                        accept=".csv"
                        @change="onFileChange"
                        class="text-sm"
                    />

                    <button
                        @click="importCsv"
                        :disabled="loading"
                        class="bg-blue-600 disabled:bg-gray-400 text-white px-6 py-2 rounded-lg font-semibold ml-auto"
                    >
                        {{ loading ? 'Importing...' : 'Import CSV' }}
                    </button>
                </div>

                <!-- Confirmation message -->
                <div
                    v-if="importMessage"
                    :class="[
            'mt-6 text-sm font-semibold p-4 rounded-xl border',
            importStatus === 'success'
                ? 'bg-green-50 text-green-700 border-green-200'
                : 'bg-red-50 text-red-700 border-red-200'
        ]"
                >
                    {{ importMessage }}
                </div>
            </div>


            <!-- Search Bar -->
            <div class="bg-white rounded-xl shadow p-6 w-full mb-6">
                <div class="grid grid-cols-3 gap-4">

                    <input
                        v-model="search"
                        @input="load"
                        placeholder="Search by ID, name or last name"
                        class="col-span-2 px-4 py-3 border border-gray-300 rounded-lg
                   focus:outline-none focus:ring-2 focus:ring-blue-600"
                    />

                    <select
                        v-model="departmentFilter"
                        @change="load"
                        class="px-4 py-3 border border-gray-300 rounded-lg
                   focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                        <option :value="null">All departments</option>
                        <option
                            v-for="d in departments"
                            :key="d.id"
                            :value="d.id"
                        >
                            {{ d.name }}
                        </option>
                    </select>

                </div>
            </div>

            <!-- List Card -->
            <div class="bg-white rounded-xl shadow p-6 w-full">

                <table class="w-full text-sm">
                    <thead class="border-b text-gray-600">
                    <tr>
                        <th class="py-2 text-left">Internal ID</th>
                        <th class="py-2 text-left">Name</th>
                        <th class="py-2 text-left">Department</th>
                        <th class="py-2 text-left">Access Attempts</th>
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
                                class="inline-flex items-center justify-center px-3 py-1
                                       rounded-full text-xs font-semibold
                                       bg-gray-100 text-gray-700"
                            >
                                {{ e.access_logs_count }}
                            </span>
                        </td>
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
                                @click="loadAccessLogs(e)"
                                class="text-gray-600 hover:underline font-medium"
                            >
                                History
                            </button>

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

            <div
                v-if="selectedEmployee"
                class="bg-white rounded-xl shadow p-6 w-full mt-8"
            >
                <h2 class="text-lg font-bold mb-4">
                    Access history – {{ selectedEmployee.first_name }} {{ selectedEmployee.last_name }}
                </h2>

                <div class="flex gap-4 mb-4">
                    <input
                        type="date"
                        v-model="fromDate"
                        @change="loadAccessLogs(selectedEmployee)"
                        class="px-3 py-2 border rounded-lg"
                    />
                    <input
                        type="date"
                        v-model="toDate"
                        @change="loadAccessLogs(selectedEmployee)"
                        class="px-3 py-2 border rounded-lg"
                    />
                </div>

                <table class="w-full text-sm">
                    <thead class="border-b">
                    <tr>
                        <th class="py-2 text-left">#</th>
                        <th class="py-2 text-left">Date</th>
                        <th class="py-2 text-left">Result</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="(log, index) in accessLogs"
                        :key="log.id"
                        class="border-b"
                    >
                        <td class="py-2 text-gray-500 font-mono">
                            {{ index + 1 }}
                        </td>
                        <td class="py-2">{{ log.attempted_at }}</td>
                        <td class="py-2">
                <span
                    :class="log.access_granted
                        ? 'text-green-600 font-semibold'
                        : 'text-red-600 font-semibold'"
                >
                    {{ log.access_granted ? 'Granted' : 'Denied' }}
                </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

