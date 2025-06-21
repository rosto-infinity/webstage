<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';

// Configuration des breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Utilisateurs', href: '/users' },
  { title: 'Nouvel utilisateur', href: '' }
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
        <!-- Champ Nom complet -->
        <div class="space-y-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Nom complet <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.name"
            
            class="input w-full"
            :class="{ 'input-error': form.errors.name }"
            placeholder="Jean Dupont"
          />
          <p v-if="form.errors.name" class="text-sm text-red-600 dark:text-red-400">
            {{ form.errors.name }}
          </p>
        </div>

        <!-- Champ Email -->
        <div class="space-y-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Email <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.email"
            type="email"
            
            class="input w-full"
            :class="{ 'input-error': form.errors.email }"
            placeholder="jean.dupont@example.com"
          />
          <p v-if="form.errors.email" class="text-sm text-red-600 dark:text-red-400">
            {{ form.errors.email }}
          </p>
        </div>

        <!-- Champ Mot de passe -->
        <div class="space-y-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Mot de passe <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.password"
            type="password"
            
            minlength="8"
            class="input w-full"
            :class="{ 'input-error': form.errors.password }"
            placeholder="••••••••"
          />
          <p v-if="form.errors.password" class="text-sm text-red-600 dark:text-red-400">
            {{ form.errors.password }}
          </p>
        </div>

        <!-- Champ Confirmation mot de passe -->
        <div class="space-y-2">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            Confirmation mot de passe <span class="text-red-500">*</span>
          </label>
          <input
            v-model="form.password_confirmation"
            type="password"
            
            minlength="8"
            class="input w-full"
            :class="{ 'input-error': form.errors.password_confirmation }"
            placeholder="••••••••"
          />
        </div>

        <!-- Boutons d'action -->
        <div class="flex items-center justify-end gap-3 pt-4">
          <button
            type="button"
            @click="form.reset()"
            class="btn btn-secondary"
            :disabled="form.processing"
          >
            Réinitialiser
          </button>
          <button
            type="submit"
            class="btn btn-primary"
            :disabled="form.processing"
          >
            <span v-if="form.processing">Création en cours...</span>
            <span v-else>Créer l'utilisateur</span>
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
