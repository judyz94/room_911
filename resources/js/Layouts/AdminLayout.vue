<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()

const user = computed(() => page.props.auth?.user)
</script>

<template>
    <div class="min-h-screen bg-gray-100">

        <!-- Header -->
        <header class="bg-gray-900 text-white shadow">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

                <div class="flex items-center gap-3">
                    <div class="text-xl font-extrabold tracking-wider">
                        ROOM<span class="text-blue-500">911</span>
                    </div>
                    <span class="text-sm text-gray-400">
                        Admin Panel
                    </span>
                </div>

                <!-- User -->
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-300">
                        {{ user?.name }}
                    </span>

                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-semibold transition"
                    >
                        Logout
                    </Link>
                </div>

            </div>
        </header>

        <!-- Main -->
        <div class="max-w-7xl mx-auto flex">

            <!-- Sidebar -->
            <aside class="w-64 bg-white shadow-md min-h-[calc(100vh-64px)]">

                <nav class="p-6 space-y-2">

                    <Link
                        href="/departments"
                        class="block px-4 py-2 rounded-lg font-medium transition"
                        :class="$page.url.startsWith('/departments')
                            ? 'bg-blue-100 text-blue-700'
                            : 'text-gray-700 hover:bg-gray-100'"
                    >
                        Departments
                    </Link>

                    <Link
                        href="/employees"
                        class="block px-4 py-2 rounded-lg font-medium transition"
                        :class="$page.url.startsWith('/employees')
                            ? 'bg-blue-100 text-blue-700'
                            : 'text-gray-700 hover:bg-gray-100'"
                    >
                        Employees
                    </Link>

                    <Link
                        href="/user/register"
                        class="block px-4 py-2 rounded-lg font-medium transition"
                        :class="$page.url.startsWith('/admin/users')
                            ? 'bg-blue-100 text-blue-700'
                            : 'text-gray-700 hover:bg-gray-100'"
                    >
                        Create Admin
                    </Link>

                </nav>
            </aside>

            <!-- Content -->
            <main class="flex-1 p-6">
                <slot />
            </main>

        </div>

    </div>
</template>
