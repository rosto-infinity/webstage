<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PaginationLink } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Clock, UserPlus, Users } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users List : Sup_Admin',
        href: '/presences/users',
    },
];

defineProps<{
    users: {
        data: Array<{
            id: number | string;
            name: string;
            email: string;
            status?: 'active' | 'inactive';
        }>;
        links: PaginationLink[];
    };
    totalUsers: number;
    totalPresentUsers?: number;
    totalAbsentUsers?: number;
    totalLateUsers?: number;
}>();
</script>

<template>
    <Head title="Users list" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-blue-50 p-3 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                            <Users class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Stagiaires</p>
                            <p class="text-2xl font-bold dark:text-white">{{ totalUsers }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-green-50 p-3 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                            <Calendar class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Présents</p>
                            <p class="text-2xl font-bold dark:text-white">{{ totalPresentUsers ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-red-50 p-3 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                            <Users class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Absents</p>
                            <p class="text-2xl font-bold dark:text-white">{{ totalAbsentUsers ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-orange-50 p-3 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400">
                            <Clock class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">En Retard</p>
                            <p class="text-2xl font-bold dark:text-white">{{ totalLateUsers ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Liste des utilisateurs</h2>
                <span class="flex gap-1 rounded-sm bg-green-900 px-2 pt-2 text-white">
                    <UserPlus />
                    <Link class="btn btn-primary mb-4" :href="route('users.create')">Add Users</Link>
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400"> Total: {{ totalUsers }} utilisateur(s) </span>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                Nom
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                        <tr v-for="user in users.data" :key="user.id" class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-gray-900 dark:text-white">
                                {{ user.id }}
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-300">
                                {{ user.name }}
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-300">
                                {{ user.email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex rounded-full px-2 text-xs leading-5 font-semibold"
                                    :class="{
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': user.status === 'active',
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': user.status === 'inactive',
                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200': !user.status,
                                    }"
                                >
                                    {{ user.status === 'active' ? 'Actif' : user.status === 'inactive' ? 'Inactif' : 'N/A' }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :links="users.links" class="mt-4" />

            <div class="mt-4">
                <Link
                    :href="route('presences')"
                    prefetch
                    class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-white hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600"
                >
                    Retour à la liste des présences
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
