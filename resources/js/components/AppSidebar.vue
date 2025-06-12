<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import {
    LayoutGrid,
    Users,
    CalendarCheck,
    UserPlus,
    List,
    Folder,
    BookOpen,
    Home
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';


const mainNavItems: NavItem[] = [
    {
        title: 'Home',
        href: '/',
        icon: Home, // Icône appropriée pour un tableau de bord
    },
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid, // Icône appropriée pour un tableau de bord
    },
    {
        title: 'Presences',
        href: '/presences',
        icon: CalendarCheck, // Mieux adapté pour la gestion des présences
    },
    {
        title: 'Add Presences',
        href: '/presences/add',
        icon: UserPlus, // Représente mieux l'ajout d'utilisateurs/présences
    },
    {
        title: 'Users list',
        href: '/presences/users',
        icon: List, // Plus adapté pour une liste que l'icône Users
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
