// resources/js/types/usePage.d.ts
import type { Page } from '@inertiajs/core';

declare module '@inertiajs/vue3' {
  export function usePage<T>(): Page<T>;
}
