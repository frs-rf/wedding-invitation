# Wedding Invitation SaaS — Backend & Frontend

Platform manajemen undangan pernikahan digital (multi-tenant).
Lihat [`../PRD.md`](../PRD.md) untuk spesifikasi produk lengkap.

---

## 🧰 Tech Stack

| Layer | Teknologi |
| :--- | :--- |
| Backend | Laravel 11 (PHP 8.3) |
| Frontend | Vue 3 + Inertia.js + TypeScript + TailwindCSS |
| Database | PostgreSQL 16 |
| Cache/Queue | Redis (production) / database (development) |
| Excel | maatwebsite/excel |
| QR Code | endroid/qr-code + bacon/bacon-qr-code |
| QR Scanner | html5-qrcode (frontend) |
| Auth | Laravel Breeze + Sanctum (token API untuk resepsionis) |

---

## 🚀 Cara Menjalankan (Development)

### Prasyarat

- PHP 8.3+, Composer
- Node.js 20+, npm
- PostgreSQL 14+
- (Opsional, untuk production) Redis

### Langkah

```bash
# 1. Install dependency
composer install
npm install

# 2. Konfigurasi environment
cp .env.example .env       # jika belum ada
php artisan key:generate

# Edit .env — sesuaikan bagian database:
#   DB_CONNECTION=pgsql
#   DB_HOST=127.0.0.1
#   DB_PORT=5432
#   DB_DATABASE=wedding_invitation
#   DB_USERNAME=<user pg Anda>
#   DB_PASSWORD=<password>

# 3. Buat database & jalankan migration
createdb wedding_invitation      # atau via psql
php artisan migrate:fresh

# 4. (Opsional) Jalankan seeder
php artisan db:seed

# 5. Build frontend & jalankan server
npm run dev                      # terminal 1 — Vite HMR
php artisan serve                # terminal 2 — Laravel (http://localhost:8000)

# 6. (Production only) Queue worker untuk bulk import Excel
php artisan queue:work --queue=imports
```

Buka `http://localhost:8000`.

---

## 🏗️ Arsitektur Multi-Tenant

Menggunakan **Single Database + tenant isolation via foreign key**:

```
users.vendor_id  ──>  users.id          (Pengantin/Resepsionis milik Vendor)
weddings.user_id ──>  users.id          (Undangan milik Pengantin)
guests.wedding_id ──>  weddings.id      (Tamu milik undangan)
```

Isolasi data antar tenant dijamin lewat **query scope** + **policy**
di setiap model, bukan database terpisah. Sesuai desain PRD Section 4.

---

## 👥 Role & Hak Akses

| Role | String value | Akses |
| :--- | :--- | :--- |
| Super Admin | `super_admin` | Dashboard global, manajemen vendor |
| Vendor (WO) | `vendor` | Dashboard vendor, manajemen pengantin |
| Pengantin | `pengantin` | Dashboard klien, undangan, tamu |
| Resepsionis | `receptionist` | Halaman scanner via magic link (tanpa dashboard) |
| Tamu | — *(bukan user)* | URL undangan via `secure_token` |

Lihat `app/Enums/Role.php`. Middleware proteksi route:

```php
// routes/web.php
Route::middleware(['auth', 'role:super_admin'])->group(...);
Route::middleware(['auth', 'role:vendor,pengantin'])->group(...);
```

---

## 📁 Struktur Folder Penting

```
app/
├── Enums/
│   └── Role.php                      # Enum role + helper
├── Http/
│   ├── Controllers/
│   │   ├── SuperAdmin/               # Controller khusus super admin
│   │   ├── Vendor/                   # Controller khusus vendor
│   │   ├── Pengantin/                # Controller khusus pengantin
│   │   ├── Receptionist/             # Controller khusus resepsionis
│   │   ├── Auth/                     # Bawaan Breeze
│   │   └── ProfileController.php
│   └── Middleware/
│       ├── EnsureUserHasRole.php     # Middleware proteksi role
│       └── HandleInertiaRequests.php # Share auth.user ke Vue
└── Models/
    └── User.php                      # Role cast + helper isVendor() dll.

database/migrations/
├── 0001_01_01_000000_create_users_table.php  # + kolom role & vendor_id
├── 0001_01_01_000001_create_cache_table.php
└── 0001_01_01_000002_create_jobs_table.php   # Queue tables

resources/js/
├── Layouts/
│   ├── AppLayout.vue                 # Wrapper untuk dashboard berbasis role
│   ├── AuthenticatedLayout.vue       # Bawaan Breeze
│   └── GuestLayout.vue
├── Pages/
│   ├── SuperAdmin/Dashboard.vue
│   ├── Vendor/Dashboard.vue
│   ├── Pengantin/Dashboard.vue
│   ├── Receptionist/CheckIn.vue      # Placeholder scanner QR
│   ├── Public/                       # Halaman undangan publik
│   ├── Auth/                         # Login/Register bawaan
│   └── Profile/
└── types/
    ├── index.d.ts                    # Tipe User (termasuk role)
    └── role.ts                       # Tipe Role + label untuk UI
```

---

## 🗺️ Roadmap Selanjutnya

Status implementasi modul PRD:

| Fase | Modul | Status |
| :--- | :--- | :--- |
| 1 | Foundation (auth multi-role, skema DB, multi-tenant) | ✅ Selesai |
| 2 | Vendor & Manajemen Tenant (CRUD klien, impersonate) | ⏳ Berikutnya |
| 3 | Theme Engine & Dashboard Pengantin (JSONB editor) | ⏳ |
| 4 | Guest Management (CRUD + import Excel via queue) | ⏳ |
| 5 | Invitation Page + WhatsApp Broadcast | ⏳ |
| 6 | Check-in System (magic link + QR scanner + API) | ⏳ |

---

## 🔒 Catatan Keamanan (NFR PRD)

- **Jangan ekspos `guests.id` / `weddings.id` inkremental** di URL frontend.
  Gunakan `secure_token` / `slug`.
- **Validasi `wedding_id`** di setiap query guest — pastikan tamu
  benar-benar milik pengantin yang sedang login.
- **Magic link resepsionis**: kedaluwarsa otomatis setelah acara selesai
  (gunakan signed URL + expiry check).

---

## 📝 Catatan Development

- `audit.ignore` di `composer.json` mengabaikan 3 advisory dari
  laravel/framework 11.x (tidak kritis untuk development).
- Untuk production: pindah `QUEUE_CONNECTION=redis`, `CACHE_STORE=redis`,
  `SESSION_DRIVER=redis` agar performa optimal.
