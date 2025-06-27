<script setup lang="ts">
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';


declare module '@inertiajs/vue3' {
  interface PageProps {
    flash?: {
      success?: string
      error?: string
      warning?: string
      info?: string
    }
  }
}

interface SocialMedia {
    id: number;
    platform: string;
    url: string;
    display_name: string | null;
}

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Paramètres média',
        href: '/settings/media',
    },
];

const { socialMedias } = defineProps<{
    socialMedias: SocialMedia[];
}>();

const page = usePage()
const flash = computed(() => page.props.flash)

const form = useForm({
    platform: 'github',
    url: '',
    display_name: '',
});

const editForm = useForm({
    id: null as number | null,
    platform: '',
    url: '',
    display_name: '',
});

const editingId = ref<number | null>(null);

const submit = () => {
    form.post(route('media.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const edit = (media: SocialMedia) => {
    editingId.value = media.id;
    editForm.id = media.id;
    editForm.platform = media.platform;
    editForm.url = media.url;
    editForm.display_name = media.display_name;
};

const update = () => {
    if (!editForm.id) return;
    
    editForm.put(route('media.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            editingId.value = null;
            editForm.reset();
        },
    });
};

const destroy = (id: number) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce média ?')) {
        router.delete(route('media.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Paramètres média" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall 
                    title="Liens des réseaux sociaux" 
                    description="Gérez vos profils et liens de réseaux sociaux" 
                />
                
                <!-- Messages flash -->
                <div v-if="flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ flash.success }}
                </div>

                <div v-if="form.errors.platform" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ form.errors.platform }}
                </div>

                <div v-if="form.errors.url" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ form.errors.url }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <!-- Formulaire d'ajout -->
                    <form @submit.prevent="submit" class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <select v-model="form.platform" class="w-full rounded-md border-gray-300">
                                    <option value="github">GitHub</option>
                                    <option value="twitter">Twitter</option>
                                    <option value="linkedin">LinkedIn</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="youtube">YouTube</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <input
                                    type="url"
                                    v-model="form.url"
                                    :placeholder="`URL ${form.platform} (ex: https://${form.platform}.com/votreprofil)`"
                                    
                                    class="w-full rounded-md border-gray-300"
                                />
                            </div>
                            <div>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-violet-600"
                                >
                                    Ajouter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Liste des médias -->
                    <div class="space-y-4">
                        <div
                            v-for="media in socialMedias"
                            :key="media.id"
                            class="border rounded-md p-4 w-full"
                        >
                            <div v-if="editingId !== media.id">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium capitalize">{{ media.platform }}</span>
                                        <span v-if="media.display_name"> - {{ media.display_name }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button
                                            @click="edit(media)"
                                            class="text-primary hover:text-violet-900"
                                        >
                                            Modifier
                                        </button>
                                        <button
                                            @click="destroy(media.id)"
                                            class="text-red-500 hover:text-red-700"
                                        >
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                                <a
                                    :href="media.url"
                                    target="_blank"
                                    class="text-gray-600 hover:text-gray-800 block mt-1 truncate"
                                >
                                    {{ media.url }}
                                </a>
                            </div>

                            <!-- Formulaire de modification -->
                            <form v-else @submit.prevent="update" class="grid grid-cols-1 md:grid-cols-4 gap-4 ">
                                <div>
                                    <select v-model="editForm.platform" class="w-full rounded-md border-gray-300">
                                        <option value="github">GitHub</option>
                                        <option value="twitter">Twitter</option>
                                        <option value="linkedin">LinkedIn</option>
                                        <option value="facebook">Facebook</option>
                                        <option value="instagram">Instagram</option>
                                        <option value="youtube">YouTube</option>
                                        <option value="other">Autre</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <input
                                        type="url"
                                        v-model="editForm.url"
                                        :placeholder="`URL ${editForm.platform} (ex: https://${editForm.platform}.com/votreprofil)`"
                                        
                                        class="w-full rounded-md border-gray-300"
                                    />
                                </div>
                                <div class="flex gap-2 space-x-2w-full ">
                                    <button
                                        type="submit"
                                        :disabled="editForm.processing"
                                        class="bg-primary text-white py-1 px-3 rounded-md hover:bg-primary"
                                    >
                                        Enregistrer
                                    </button>
                                    <button
                                        type="button"
                                        @click="editingId = null"
                                        class="bg-red-500 text-white py-1 px-3 rounded-md hover:bg-red-600"
                                    >
                                        Annuler
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
