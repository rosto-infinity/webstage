<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Media settings',
        href: '/settings/media',
    },
];

const props = defineProps({
    socialMedias: Array,
});

const form = useForm({
    platform: 'github',
    url: '',
    display_name: '',
});

const editForm = useForm({
    id: null,
    platform: '',
    url: '',
    display_name: '',
});

const editingId = ref(null);

const submit = () => {
    form.post(route('media.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const edit = (media) => {
    editingId.value = media.id;
    editForm.id = media.id;
    editForm.platform = media.platform;
    editForm.url = media.url;
    editForm.display_name = media.display_name;
};

const update = () => {
    editForm.put(route('media.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => {
            editingId.value = null;
            editForm.reset();
        },
    });
};

const destroy = (id) => {
    if (confirm('Are you sure you want to delete this media?')) {
        router.delete(route('media.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Media settings" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall 
                    title="Social Media Links" 
                    description="Manage your social media profiles and links" 
                />
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <!-- Add Form -->
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
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <input
                                    type="url"
                                    v-model="form.url"
                                    placeholder="URL"
                                    
                                    class="w-full rounded-md border-gray-300"
                                />
                            </div>
                            <div>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                                >
                                    Add
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- List -->
                    <div class="space-y-4">
                        <div
                            v-for="media in socialMedias"
                            :key="media.id"
                            class="border rounded-md p-4"
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
                                            class="text-blue-500 hover:text-blue-700"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="destroy(media.id)"
                                            class="text-red-500 hover:text-red-700"
                                        >
                                            Delete
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

                            <!-- Edit Form -->
                            <form v-else @submit.prevent="update" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <select v-model="editForm.platform" class="w-full rounded-md border-gray-300">
                                        <option value="github">GitHub</option>
                                        <option value="twitter">Twitter</option>
                                        <option value="linkedin">LinkedIn</option>
                                        <option value="facebook">Facebook</option>
                                        <option value="instagram">Instagram</option>
                                        <option value="youtube">YouTube</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <input
                                        type="url"
                                        v-model="editForm.url"
                                        required
                                        class="w-full rounded-md border-gray-300"
                                    />
                                </div>
                                <div class="flex space-x-2">
                                    <button
                                        type="submit"
                                        :disabled="editForm.processing"
                                        class="bg-green-500 text-white py-1 px-3 rounded-md hover:bg-green-600"
                                    >
                                        Save
                                    </button>
                                    <button
                                        type="button"
                                        @click="editingId = null"
                                        class="bg-gray-500 text-white py-1 px-3 rounded-md hover:bg-gray-600"
                                    >
                                        Cancel
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
