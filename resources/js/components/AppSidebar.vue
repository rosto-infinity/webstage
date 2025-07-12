<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, CalendarCheck, Folder, Home, LayoutGrid, List, Settings, UserPlus } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

interface PageProps {
    auth: {
        user?: {
            role: string;
            // Ajoutez ici d'autres propriétés utilisateur si nécessaire
        };
    };
}

const { props } = usePage<PageProps>();
const userRole = props.auth?.user?.role || 'user';

const mainNavItems: NavItem[] = [
    {
        title: 'Home',
        href: '/',
        icon: Home, // Icône appropriée pour un tableau de bord
    },

    {
        title: 'Dashboard',
        href: 'dashboard',
        icon: LayoutGrid,
    },

];

const adminNavItems: NavItem[] = [
    {
        title: 'Dashboard Users',
        href: '/admin/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Dashboard Admin',
        href: '/gestions/users',
        icon: Settings,
    },
];

const superAdminNavItems: NavItem[] = [
    {
        title: 'Dashboard Sup_Admin',
        href: '/superadmin/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Presences users',
        href: '/presences/users',
        icon: CalendarCheck, // Mieux adapté pour la gestion des présences
    },
    {
        title: 'Add Presences',
        href: '/presences/add',
        icon: UserPlus, // Représente mieux l'ajout d'utilisateurs/présences
    },
    {
        title: 'Users',
        href: '/gestions/users',
        icon: List, // Plus adapté pour une liste que l'icône Users
    },
];

let roleBasedNavItems = [...mainNavItems];

if (userRole === 'admin') {
    roleBasedNavItems = [...adminNavItems];
}
if (userRole === 'superadmin') {
    roleBasedNavItems = [...roleBasedNavItems, ...superAdminNavItems];
}

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: '#',
        icon: Folder,
    },

    {
        title: 'Documentation',
        href: '#',
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
            <NavMain :items="roleBasedNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" className="mt-auto" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
