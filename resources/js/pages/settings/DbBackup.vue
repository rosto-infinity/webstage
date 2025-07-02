<script setup lang="ts">
import { Head, usePage,router  } from '@inertiajs/vue3';
import { Download } from 'lucide-vue-next';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';
import { computed } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import Button from '@/components/ui/button/Button.vue';


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
            <div class="space-y-6 p-6 overflow-x-auto  rounded-lg shadow">
                <HeadingSmall 
                title="Sauvegardes de base de données" 
                    description="Liste des sauvegardes disponibles et gestion" 
                />
                 <!-- Correction ici avec l'opérateur optionnel -->
                <div v-if="flash.success" class="bg-green-100 text-green-600">
                    {{ flash.success }}
                </div>
               <Button @click="createBackup" class="mb-4">
                      Créer une nouvelle sauvegarde
               </Button>
                <div class=" bg-white dark:bg-gray-800 rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Taille</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="backup in backups" :key="backup.name">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ backup.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ formatSize(backup.size) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ formatDate(backup.last_modified) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <Button
                                        @click="downloadBackup(backup.path)"
                                        class=""
                                    >
                                        <Download class="w-5 h-5"/> Télécharger
                                    </Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
           
        </SettingsLayout>
    </AppLayout>
</template>
