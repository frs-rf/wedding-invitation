import type { Role } from './role'

export interface User {
    id: number
    name: string
    email: string
    email_verified_at?: string | null
    role: Role
    vendor_id?: number | null
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User
        is_impersonating?: boolean
    }
    flash?: {
        success?: string | null
        error?: string | null
    }
}
