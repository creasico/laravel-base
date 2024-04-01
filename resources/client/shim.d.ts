import { Alpine } from "alpinejs"

export {}

declare global {
  type AppLocale = 'id' | 'en'

  interface Window {
    Alpine: Alpine
  }
}
