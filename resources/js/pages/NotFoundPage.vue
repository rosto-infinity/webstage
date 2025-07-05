<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="error-page flex min-h-screen flex-col bg-gradient-to-b from-background/10 to-background">
        <!-- Header -->
        <header class="px-6 py-4">
            <Link href="/" class="inline-flex items-center gap-2">
                <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="text-xl font-bold text-primary">WebStage</span>
            </Link>
        </header>

        <!-- Main Content -->
        <main class="flex flex-grow items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl text-center">
                <div class="relative mb-8 inline-block">
                    <span class="text-9xl font-bold text-primary/20">404</span>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="h-32 w-32 animate-pulse text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                </div>

                <h1 class="mb-4 text-4xl font-bold text-foreground">Page introuvable</h1>

                <p class="mb-8 text-lg text-muted-foreground">
                    Oups ! La page que vous cherchez semble avoir disparu dans la nature.
                    <br />
                    Peut-être a-t-elle été déplacée ou n'existe-t-elle plus.
                </p>

                <div class="flex flex-col justify-center gap-4 sm:flex-row">
                    <Link
                        href="/"
                        class="rounded-lg bg-primary px-6 py-3 font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary/90 hover:shadow-md"
                    >
                        Retour à l'accueil
                    </Link>
                </div>

                <div class="mt-12 text-sm text-primary">
                    <p>Code d'erreur : 404 | Date : 12/06/2025 21:44</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-6 text-center text-sm text-muted-foreground">
            <p>© 2025 WebStage. Tous droits réservés.</p>
        </footer>
    </div>
</template>

<style scoped>
.error-page {
    background-image:
        radial-gradient(circle at 10% 20%, rgba(var(--primary), 0.05) 0%, transparent 20%),
        radial-gradient(circle at 90% 80%, rgba(var(--primary), 0.05) 0%, transparent 20%);
    background-size: 300px 300px;
    animation: backgroundMove 30s linear infinite alternate;
}

@keyframes backgroundMove {
    0% {
        background-position: 0% 0%;
    }
    100% {
        background-position: 100% 100%;
    }
}
</style>
