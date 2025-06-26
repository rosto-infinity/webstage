<script setup lang="ts">
import { Head, useForm, usePage} from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import DeleteUser from '@/components/DeleteUser.vue';
import type { BreadcrumbItem, SharedData, User } from '@/types';

interface Props {
  mustVerifyEmail: boolean;
  status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Profile settings', href: '/settings/profile' }
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const avatarPreview = ref<string | null>(null);

const form = useForm({
  name: user.name,
  email: user.email,
  avatar: null as File | null
});

function handleAvatarChange(e: Event) {
  const input = e.target as HTMLInputElement;
  if (input.files?.[0]) {
    const file = input.files[0];
    form.avatar = file;
    const reader = new FileReader();
    reader.onload = () => {
      avatarPreview.value = reader.result as string;
    };
    reader.readAsDataURL(file);
  }
}

function submit() {
  form.transform(data => ({ ...data, _method: 'PATCH' }))
    .post(route('profile.update'), {
      forceFormData: true,
      preserveScroll: true,
      preserveState: false, // <-- force reload du composant
      onSuccess: () => {
        avatarPreview.value = null;
        form.reset('avatar');
      }
    });
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Profile settings" />

    <SettingsLayout>
      <div class="flex flex-col items-center mb-6">
        <div class="relative group w-32 h-32 rounded-full border-4 border-[#0a5d3b]
                    bg-gradient-to-br from-[#e0f7ef] to-[#f8fafc] shadow-lg overflow-hidden
                    transition-all duration-300">
          <template v-if="avatarPreview">
            <img :src="avatarPreview" alt="Preview avatar"
                 class="w-full h-full object-cover rounded-full
                        transition-transform duration-300 group-hover:scale-105" />
          </template>
          <template v-else-if="user.avatar">
            <img :src="`/storage/${user.avatar}`" alt="Current avatar"
                 class="w-full h-full object-cover rounded-full
                        transition-transform duration-300 group-hover:scale-105" />
          </template>
          <template v-else>
            <div class="w-full h-full flex items-center justify-center
                        bg-[#e0f7ef] text-[#0a5d3b] text-5xl font-bold rounded-full">
              {{ user.name.charAt(0).toUpperCase() }}
            </div>
          </template>
          <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100
                      flex items-center justify-center transition-opacity duration-300">
            <span class="text-white text-xs font-semibold">Changer l’avatar</span>
          </div>
        </div>
        <p v-if="avatarPreview" class="mt-2 text-sm text-muted-foreground">
          Prévisualisation du nouvel avatar
        </p>
      </div>

      <div class="flex flex-col space-y-6">
        <HeadingSmall
          title="Profile information"
          description="Update your name and email address"
        />

        <form @submit.prevent="submit" enctype="multipart/form-data" class="grid gap-4">
          <!-- Champ Nom -->
          <div class="grid gap-2">
            <Label for="name">Nom</Label>
            <Input id="name" type="text" v-model="form.name" class="w-full" />
            <InputError :message="form.errors.name" />
          </div>

          <!-- Champ Email -->
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input id="email" type="email" v-model="form.email" class="w-full" />
            <InputError :message="form.errors.email" />
          </div>

          <!-- Champ Avatar -->
          <div class="grid gap-2">
            <Label for="avatar">Avatar</Label>
            <Input id="avatar" type="file" accept="image/*" @change="handleAvatarChange" class="w-full" />
            <InputError :message="form.errors.avatar" />
          </div>

          <div class="flex items-center gap-4 pt-4">
            <Button type="submit" :disabled="form.processing">
              Enregistrer les modifications
            </Button>
            <transition
              enter-active-class="transition ease-in-out"
              enter-from-class="opacity-0"
              leave-active-class="transition ease-in-out"
              leave-to-class="opacity-0">
              <p v-if="form.recentlySuccessful" class="text-sm text-muted-foreground">Sauvegardé.</p>
            </transition>
          </div>
        </form>

        <DeleteUser />
      </div>
    </SettingsLayout>
  </AppLayout>
</template>
