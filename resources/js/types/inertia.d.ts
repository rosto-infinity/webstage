// types/inertia.d.ts
import '@inertiajs/vue3'

declare module '@inertiajs/vue3' {
  export interface PageProps {
    flash?: {
      success?: string
      error?: string
      warning?: string
      info?: string
    }
  }
}
