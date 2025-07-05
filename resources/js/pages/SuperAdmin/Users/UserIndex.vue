<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PaginationLink } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Calendar, Clock, Pen, Trash2, UserPlus, Users } from 'lucide-vue-next';

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

function destroy(id: number | string) {
    if (confirm('Supprimer cet utilisateur ?')) {
        router.delete(route('users.destroy', id));
    }
}
</script>

<template>
    <Head title="Users list" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-border p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg p-3 text-primary">
                            <Users class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Total Stagiaires</p>
                            <p class="text-2xl font-bold">{{ totalUsers }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-border p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg p-3 text-green-600 dark:text-green-400">
                            <Calendar class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Présents</p>
                            <p class="text-2xl font-bold">{{ totalPresentUsers ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-border p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg p-3 text-red-600 dark:text-red-400">
                            <Users class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Absents</p>
                            <p class="text-2xl font-bold">{{ totalAbsentUsers ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-border p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg p-3 text-orange-600 dark:text-orange-400">
                            <Clock class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">En Retard</p>
                            <p class="text-2xl font-bold">{{ totalLateUsers ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium">Liste des utilisateurs</h2>
                <span class="flex gap-1 rounded-sm bg-primary px-2 pt-2 text-white">
                    <UserPlus />
                    <Link class="btn btn-primary mb-4" :href="route('users.create')" prefetch>Add Users</Link>
                </span>
                <span class="text-sm text-muted-foreground"> Total: {{ totalUsers }} utilisateur(s) </span>
            </div>

            <div class="overflow-x-auto rounded-lg border border-border shadow-sm">
                <table class="min-w-full divide-y divide-border">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">Nom</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">Statut</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <tr v-for="user in users.data" :key="user.id" class="transition-colors duration-150 hover:bg-muted/50">
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                {{ user.id }}
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                {{ user.name }}
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">
                                {{ user.email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex rounded-full px-2 text-xs leading-5 font-semibold"
                                    :class="{
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': user.status === 'active',
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': user.status === 'inactive',
                                        'bg-muted text-muted-foreground': !user.status,
                                    }"
                                >
                                    {{ user.status === 'active' ? 'Actif' : user.status === 'inactive' ? 'Inactif' : 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                <Link
                                    :href="route('users.edit', user.id)"
                                    class="inline-flex items-center gap-1 rounded-md bg-primary px-3 py-1 text-primary-foreground hover:bg-primary/90"
                                >
                                    <Pen class="inline h-4 w-4" /> Editer
                                </Link>
                                <button
                                    @click="destroy(user.id)"
                                    class="ml-2 inline-flex items-center gap-1 rounded-md bg-destructive px-3 py-1 text-destructive-foreground hover:bg-destructive/90"
                                >
                                    <Trash2 class="inline h-4 w-4" />
                                    Suppr.
                                </button>
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
                    class="inline-flex items-center rounded-md bg-muted px-4 py-2 text-foreground hover:bg-muted/80"
                >
                    Retour à la liste des présences
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
