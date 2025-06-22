<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/types';
import InputError from '@/components/InputError.vue';


import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card } from '@/components/ui/card';

// Configuration des breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Utilisateurs', href: '/users' },
  { title: 'Modifier utilisateur', href: '' }
];

// Typage des props
interface User {
  id: number | string;
  name: string;
  email: string;
}

const props = defineProps<{
  user: User;
}>();

// Initialisation du formulaire avec validation
const form = useForm({
  name: props.user.name,
  email: props.user.email,
  password: '',
  password_confirmation: ''
});

// Soumission du formulaire avec gestion d'erreur
const submit = () => {
  form.put(route('users.update', props.user.id), {
    preserveScroll: true,
    onSuccess: () => form.reset('password', 'password_confirmation'),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
      }
    }
  });
};
</script>

<template>
  <Head title="Modifier utilisateur" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
      <header class="space-y-2">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          Modifier l'utilisateur
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          ID: {{ user.id }}
        </p>
      </header>
      
      <div class="grid gap-6  p-5 rounded-md shadow-emerald-950">
        
        <form @submit.prevent="submit" class="space-y-6 max-w-2xl " >
            <Card class="rounded-xl p-5 py-7">
            
            
            <div class="grid gap-2">
                  <Label for="name">Nom complet</Label>
                  <Input id="name" type="name" :tabindex="2" autocomplete="name" v-model="form.name"
                    placeholder="name@example.com" />
                  <InputError :message="form.errors.name" />
            </div>
            
            <!-- <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Nom complet
              </label>
              <input
                v-model="form.name"
                
                class="input w-full"
                :class="{ 'input-error': form.errors.name }"
              />
              <p v-if="form.errors.name" class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.name }}
              </p>
            </div> -->


              <div class="grid gap-2">
                  <Label for="email">Adresse email</Label>
                  <Input id="email" type="email" :tabindex="2" autocomplete="email" v-model="form.email"
                    placeholder="email@example.com" />
                  <InputError :message="form.errors.email" />
                </div>

            <!-- <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Adresse email
              </label>
              <input
                v-model="form.email"
                type="email"
                
                class="input w-full"
                :class="{ 'input-error': form.errors.email }"
              />
              <p v-if="form.errors.email" class="text-sm text-red-600 dark:text-red-400">
                {{ form.errors.email }}
              </p>
            </div> -->

            <div class="space-y-4 border-t border-green-600 dark:border-gray-700 pt-4">
              
             
              
              
              <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                Changer le mot de passe
              </h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">
                Laissez vide pour conserver le mot de passe actuel
              </p>

               <div class="grid gap-2">
                  <Label for="password">Nouveau mot de passe</Label>
                  <Input id="password" type="password" :tabindex="3" autocomplete="new-password" v-model="form.password"
                    placeholder="Password" />
                  <InputError :message="form.errors.password" />
               </div>

                <div class="grid gap-2">
                  <Label for="password_confirmation">Confirm password</Label>
                  <Input id="password_confirmation" type="password" :tabindex="4" autocomplete="new-password"
                    v-model="form.password_confirmation" placeholder="Confirm password" />
                  <InputError :message="form.errors.password_confirmation" />
                </div>
              
                <!-- <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Nouveau mot de passe
                </label>
                <input
                  v-model="form.password"
                  type="password"
                  class="input w-full"
                  :class="{ 'input-error': form.errors.password }"
                />
                <p v-if="form.errors.password" class="text-sm text-red-600 dark:text-red-400">
                  {{ form.errors.password }}
                </p>
              </div> -->

              <!-- <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Confirmation
                </label>
                <input
                  v-model="form.password_confirmation"
                  type="password"
                  class="input w-full"
                />
              </div> -->
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
              <button
                type="button"
                @click="form.reset()"
                class="btn btn-secondary bg-green-400 px-5 py-2 rounded-sm mr-2"
                :disabled="form.processing"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="btn btn-primary"
                :disabled="form.processing"
              >
                <span v-if="form.processing"
                class="bg-green-500 px-5 py-2 rounded-sm mr-2" 
                
                >Enregistrement...</span>
                <span v-else class="bg-green-500 px-5 py-2 rounded-sm mr-2"> Mettre à jour</span>
              </button>
            </div>
          </Card>
          </form>
        
        </div>
      
      
      </div>
  </AppLayout>
</template>
