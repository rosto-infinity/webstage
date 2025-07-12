<script setup lang="ts">
import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';


defineProps<{
    totalUsers: number;
      users: {
        id: number;
        name: string;
        email: string;
        avatar: string;
        socialMedias: {
            id: number;
            platform: string;
            url: string;
            display_name: string;
        }[];
    }[];
}>();

// Animation au scroll
const animatedElements = ref<HTMLElement[]>([]);
onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        },
        { threshold: 0.1 },
    );

    animatedElements.value.forEach((el) => observer.observe(el));
});
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    </Head>

    <div class="min-h-screen bg-gradient-to-b from-background/10 to-background">
        <!-- Header -->
        <header class="mx-auto mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4 py-6">
                <AppearanceTabs />

                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    prefetch
                    class="inline-block rounded-lg border border-primary/20 px-5 py-2 text-sm leading-normal text-primary transition-colors hover:bg-primary/10"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        prefetch
                        class="inline-block rounded-lg px-5 py-2 text-sm leading-normal text-primary transition-colors hover:bg-primary/5 hover:text-primary/90"
                    >
                        Connexion
                    </Link>
                    <Link
                        :href="route('register')"
                        prefetch
                        class="inline-block rounded-lg bg-primary px-5 py-2 text-sm leading-normal text-primary-foreground shadow-sm transition-colors hover:bg-primary/90"
                    >
                        Inscription
                    </Link>
                </template>
            </nav>
        </header>

        <!-- Section Hero -->
        <section class="welcome-section relative overflow-hidden">
            <div class="absolute inset-0 z-0 bg-background/20 backdrop-blur-sm"></div>

            <div class="relative z-10 mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:px-8">
                <div class="parallax-layer" data-depth="0.2">
                    <h1 class="mb-6 text-5xl font-bold tracking-tight text-foreground md:text-6xl lg:text-7xl">
                        Bienvenue chez <span class="text-primary">Web Stage</span>
                    </h1>
                </div>

                <div class="animate__animated animate__fadeIn animate__delay-1s">
                    <p class="max-w-3xl text-lg leading-relaxed text-muted-foreground md:text-xl">
                        Votre partenaire en solutions digitales innovantes depuis 2025. Découvrez une expérience utilisateur redéfinie et des outils
                        performants.
                    </p>
                </div>

                <div class="mt-12 flex flex-col gap-4 sm:flex-row">
                    <Link
                        :href="route('dashboard')"
                        prefetch
                        class="btn rounded-lg bg-primary px-8 py-3 text-base font-medium text-white transition-all duration-300 hover:shadow-lg"
                    >
                        Accéder au dashboard
                        <svg class="ml-2 inline h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </Link>

                    <Link
                        href="#"
                        class="cta-secondary rounded-lg border-2 border-primary/20 px-8 py-3 text-base font-medium transition-all duration-300 hover:border-primary/40"
                    >
                        Nos Disponibilités
                    </Link>
                </div>

                <div class="absolute bottom-10 left-1/2 -translate-x-1/2 transform">
                    <div class="mouse-scroll animate-bounce">
                        <div class="mouse-wheel bg-primary"></div>
                    </div>
                </div>
            </div>
        </section>

         <!-- Section Utilisateurs -->
      <section class="users-section bg-background/80 py-20 backdrop-blur-sm">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-foreground mb-8">Liste des Utilisateurs</h2>
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="user in users"
                :key="user.id"
                class="user-card rounded-xl border border-border bg-background p-8 shadow-sm hover:shadow-md"
            >
                <div class="flex items-center gap-4">
                    <img
                        :src="user.avatar"
                        :alt="user.name"
                        class="h-12 w-12 rounded-full object-cover"
                    />
                    <div>
                        <h3 class="text-xl font-semibold text-foreground">{{ user.name }}</h3>
                        <p class="text-muted-foreground">{{ user.email }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <h4 class="font-semibold text-lg text-primary">Médias Sociaux</h4>
                    <div v-if="user.socialMedias && user.socialMedias.length > 0" class="space-y-2">
                        <div
                            v-for="social in user.socialMedias"
                            :key="social.id"
                            class="social-media-item"
                        >
                            <a :href="social.url" target="_blank" class="text-muted-foreground hover:text-primary">
                                {{ social.platform }}: {{ social.display_name }}
                            </a>
                        </div>
                    </div>
                    <p v-else class="text-muted-foreground">Aucun média social associé.</p>
                </div>
            </div>
        </div>
    </div>
</section>
 
        <!-- Section Statistiques -->
        <section class="stats-section bg-background/80 py-20 backdrop-blur-sm">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <div class="stat-card rounded-xl border border-border bg-background p-8 shadow-sm hover:shadow-md">
                        <div v-if="totalUsers < 10" class="mb-2 text-5xl font-bold text-primary" ref="counter1">0{{ totalUsers }}</div>
                        <div v-else class="mb-2 text-5xl font-bold text-primary" ref="counter1">{{ totalUsers }}</div>
                        <div class="text-muted-foreground">Etudiants BTS encadrés</div>
                    </div>

                    <div class="stat-card rounded-xl border border-border bg-background p-8 shadow-sm hover:shadow-md">
                        <div v-if="totalUsers < 10" class="mb-2 text-5xl font-bold text-primary" ref="counter1">0{{ totalUsers }}</div>
                        <div v-else class="mb-2 text-5xl font-bold text-primary" ref="counter1">{{ totalUsers }}</div>
                        <div class="text-muted-foreground">Apprenants DQP encadrés</div>
                    </div>

                    <div class="stat-card rounded-xl border border-border bg-background p-8 shadow-sm hover:shadow-md">
                        <div class="mb-2 text-5xl font-bold text-primary" ref="counter3">6J/7J</div>
                        <div class="text-muted-foreground">8h-17h/Jours</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
/* Effets de particules adaptés au thème */
.particles {
    position: absolute;
    width: 100%;
    height: 100%;
    background-image: radial-gradient(circle at 20% 50%, rgba(var(--primary), 0.1) 1px, transparent 1px);
    background-size: 40px 40px;
    animation: particleMove 150s linear infinite;
}

@keyframes particleMove {
    100% {
        background-position: 1200px 600px;
    }
}

/* Typographie */
h1 {
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    line-height: 1.2;
}

/* Boutons */
.cta-primary {
    background: linear-gradient(45deg, var(--primary) 0%, var(--primary/90) 100%);
    color: var(--primary-foreground);
    box-shadow: 0 4px 15px rgba(var(--primary), 0.3);
}

.cta-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(var(--primary), 0.4);
    background: linear-gradient(45deg, var(--primary/90) 0%, var(--primary) 100%);
}

.cta-secondary {
    color: var(--primary);
    background: rgba(var(--primary), 0.05);
}

.cta-secondary:hover {
    background: rgba(var(--primary), 0.1);
}

/* Indicateur de scroll */
.mouse-scroll {
    width: 24px;
    height: 40px;
    border: 2px solid rgba(var(--primary), 0.5);
    border-radius: 12px;
}

.mouse-wheel {
    width: 4px;
    height: 8px;
    border-radius: 2px;
    margin: 8px auto;
}
.user-card {
    transition: all 0.3s ease;
}

.user-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary/30);
}

.social-media-item a {
    transition: color 0.3s ease;
}

.social-media-item a:hover {
    color: var(--primary);
}
/* Cartes de statistiques */
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: var(--primary/30);
}
</style>
