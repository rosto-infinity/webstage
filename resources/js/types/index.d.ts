import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}
export interface PageProps {
    
  arrival_time: string | null;
  departure_time: string | null;
  late_minutes: number;
  absent: boolean;
  late: boolean;
}
export interface Presence {  
   id: number;
  date: string;
  arrival_time: string | null;
  departure_time: string | null;
  late_minutes: number;
  absent: boolean;
  late: boolean;
  user: { name: string; email: string };
}

export interface PaginationLink {
  url: string | null
  label: string
  active: boolean
}
export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
