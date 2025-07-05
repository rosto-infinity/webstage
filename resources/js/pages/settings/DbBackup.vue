<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Download } from 'lucide-vue-next';
import { computed } from 'vue';

import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'DB Backup settings',
        href: '/settings/dbbackup',
    },
];

interface FlashMessage {
    success?: string;
}

const props = defineProps<{
    backups: Array<{
        name: string;
        size: number;
        last_modified: number;
        path: string;
    }>;
    flash?: FlashMessage;
}>();

const page = usePage();
const flash = computed<FlashMessage>(() => (page.props.flash as FlashMessage) ?? props.flash ?? {});
const formatSize = (bytes: number) => {
    if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
    if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
    if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
    return bytes + ' bytes';
};

const formatDate = (timestamp: number) => {
    return new Date(timestamp).toLocaleString();
};

const downloadBackup = (path: string) => {
    window.location.href = route('dbbackup.download', { path });
};

const createBackup = () => {
    router.post(route('dbbackup.create'));
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Gestion des sauvegardes DB" />

        <SettingsLayout class="">
            <div class="space-y-6 overflow-x-auto rounded-lg p-6 shadow">
                <HeadingSmall title="Sauvegardes de base de données" description="Liste des sauvegardes disponibles et gestion" />
                <!-- Correction ici avec l'opérateur optionnel -->
                <div v-if="flash.success" class="bg-green-100 text-green-600">
                    {{ flash.success }}
                </div>
                <Button @click="createBackup" class="mb-4"> Créer une nouvelle sauvegarde </Button>
                <div class="rounded-lg bg-white shadow dark:bg-gray-800">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                    Taille
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-300">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            <tr v-for="backup in backups" :key="backup.name">
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap text-gray-900 dark:text-white">
                                    {{ backup.name }}
                                </td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-300">
                                    {{ formatSize(backup.size) }}
                                </td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-300">
                                    {{ formatDate(backup.last_modified) }}
                                </td>
                                <td class="px-6 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-300">
                                    <Button @click="downloadBackup(backup.path)" class=""> <Download class="h-5 w-5" /> Télécharger </Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
