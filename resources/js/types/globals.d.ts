import { AppPageProps } from '@/types/index';
import type { PageProps as InertiaPageProps } from '@inertiajs/core';

// Extension de l'interface ImportMeta pour Vite
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

// Déclaration des données partagées
export interface SharedData {
    auth: {
        user?: {
            id: number;
            name: string;
            email: string;
            role: string;
        };
    };
    // Ajoutez ici d'autres données partagées si nécessaire
}

// Extension des propriétés de page Inertia avec les données partagées
export type PageProps<T = object> = InertiaPageProps & SharedData & T;

// Extension des modules pour Inertia et Vue
declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps, SharedData {}
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
    }
}
