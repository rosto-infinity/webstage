<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Card } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import InputError from '@/components/InputError.vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ArrowLeft,LoaderCircle } from 'lucide-vue-next';
// Configuration des breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Utilisateurs Sup_Admin', href: '/users' },
  { title: 'Nouvel utilisateur : Sup_Admin', href: '' }
];

// Typage du formulaire avec intersection de types
type UserFormData = {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
};

// Initialisation du formulaire avec typage explicite
const form = useForm<UserFormData>({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

// Soumission du formulaire avec gestion améliorée des erreurs
const submit = () => {
  form.post(route('users.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      // Ajouter une notification de succès si nécessaire
    },
    onError: (errors) => {
      console.error('Erreur lors de la création:', errors);
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
      }
    }
  });
};
</script>

<template>

  <Head title="Création utilisateur" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6 bg-white dark:bg-gray-800">
      <header class="space-y-1">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          Créer un nouvel utilisateur
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Remplissez les champs obligatoires pour ajouter un utilisateur
        </p>
      </header>

      <form @submit.prevent="submit" class="space-y-6 max-w-2xl">
        <Card class="rounded-xl">
          <!-- Boutons d'action -->
          <div class="grid gap-6 px-4 rounded-md shadow-emerald-950">
            <div class="flex justify-between items-center  gap-3 pt-1">
             
             <span>
              <Link
        :href="route('users.index')" prefetch
        class="inline-flex items-center  gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition-colors mb-6"
      >
          <ArrowLeft class="w-4 h-4" />
      Retour à la liste
      </Link>
             </span>
              <!-- <button type="button" @click="form.reset()" class="btn btn-secondary" :disabled="form.processing">
                Réinitialiser
              </button> -->

              <button
              type="button"
              @click="form.reset()"
              class="px-4 py-2 text-sm font-medium rounded-md shadow-sm bg-secondary text-secondary-foreground hover:bg-secondary/80"
              :disabled="form.processing"
            >
              Réinitialiser
            </button>

            </div>

            <div class="grid gap-2">
              <Label for="name">Name</Label>
              <Input id="name" type="text" autofocus :tabindex="1" autocomplete="name" v-model="form.name"
                placeholder="Full name" />
              <InputError :message="form.errors.name" />
            </div>

            <div class="grid gap-2">
              <Label for="email">Email address</Label>
              <Input id="email" type="email" :tabindex="2" autocomplete="email" v-model="form.email"
                placeholder="email@example.com" />
              <InputError :message="form.errors.email" />
            </div>

            <div class="grid gap-2">
              <Label for="password">Password</Label>
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

            <Button type="submit" class="" tabindex="5"
              :disabled="form.processing">
              <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
              Create account
            </Button>
          </div>

        </Card>
      </form>
    </div>



  </AppLayout>
</template>
