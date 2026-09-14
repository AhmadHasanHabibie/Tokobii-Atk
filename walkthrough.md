# Walkthrough: Implementasi Role Superadmin & Penyesuaian Desain Admin

Role **Superadmin** telah berhasil dirancang dan diimplementasikan dengan standar keamanan siber tertinggi (**Zero Trust Architecture**, **Active Defense**, **Deep Forensics**, dan **DEFCON 1 Incident Response**). Role ini **100% terisolasi** dari urusan operasional bisnis/toko.

Desain visual seluruh panel Superadmin kini telah diselaraskan **100% dengan desain Panel Admin Tokobii**:
- **Warna & Palet**: Background light `var(--tokobii-bg)` (`#f0f4f8`), kartu putih bersih `tokobii-card` (`#ffffff`), border halus `#e2e8f0` / `#f1f5f9`, aksen royal blue `#2563eb`, dan status badges Tokobii.
- **Tipografi & Font**: Menggunakan font resmi Tokobii `'Plus Jakarta Sans'`, dengan hierarki teks, ukuran, dan perataan yang identik dengan Panel Admin.
- **Animasi & Transisi**: Mengadopsi animasi `animate-fade-in-up`, `tokobiiFadeInUp`, serta transisi collapse & drawer sidebar yang mulus (`cubic-bezier(0.16, 1, 0.3, 1)`).
- **Komponen**: Header card (`tokobii-header-card`), tombol aksi (`dashboard-action-card`), stat card (`tokobii-card p-4`), pill badge (`tokobii-badge`), tabel dengan header `#f8fafc`, dropdown user pill (`user-avatar-pill`), dan mini user card di sidebar.

---

## 1. Ringkasan 4 Pilar Arsitektur Keamanan

### Pilar 1: Otorisasi & Isolasi Akses (Zero Trust Architecture)
- **`SuperadminMiddleware`** (`app/Http/Middleware/SuperadminMiddleware.php`):
  - Memvalidasi role `superadmin` secara ketat pada seluruh rute `/superadmin/*`.
  - Akses non-superadmin langsung ditolak dengan status **403 Forbidden** dan dicatat ke `security_logs` dengan event `unauthorized_superadmin_access`.
- **`SuperadminReauthMiddleware`** (`app/Http/Middleware/SuperadminReauthMiddleware.php`):
  - Proteksi **Zero Trust Re-Authentication** (15 menit masa berlaku).
  - Superadmin wajib memasukkan ulang password sebelum mengeksekusi aksi berisiko tinggi (Panic Button, Cache Purge, Log Reset).
- **`PreventRequestsDuringMaintenance`** (`app/Http/Middleware/PreventRequestsDuringMaintenance.php`):
  - Mengecualikan `superadmin/*` dari maintenance lock sehingga Superadmin tetap memegang kendali penuh saat sistem sedang dalam keadaan darurat / down.

---

### Pilar 2: Active Defense & Forensics (Middleware Layer)
- **`HoneypotTrapMiddleware` & Decoy Routes** (`app/Http/Middleware/HoneypotTrapMiddleware.php` & `routes/honeypot.php`):
  - Rute jebakan: `/wp-admin`, `/wp-login.php`, `/admin.php`, `/xmlrpc.php`, `/.env`, `/phpmyadmin`, `/pma`, `/config.php`, `/actuator`.
  - Sekali diakses oleh bot/attacker/scanner, sistem otomatis:
    1. Mencatat IP ke tabel `ip_blacklists` secara **permanen**.
    2. Mencatat telemetri forensik ke `security_logs` dengan severity **CRITICAL**.
    3. Mengembalikan respons **403 Forbidden** dengan halaman pertahanan aktif (`errors/403_blacklisted.blade.php`).
- **`BlockBlacklistedIpMiddleware`** (`app/Http/Middleware/BlockBlacklistedIpMiddleware.php`):
  - Terdaftar di middleware stack global `web` dan `api`.
  - Menghalangi setiap permintaan jaringan dari IP yang terdaftar di `ip_blacklists` dengan respons **403 Forbidden** dan meng-increment hit counter secara otomatis.
- **`SecurityAuditMiddleware`** (`app/Http/Middleware/SecurityAuditMiddleware.php`):
  - Mencatat aktivitas sensitif, mutasi data, login/logout, dan error status ke tabel `security_logs`.
  - Dilengkapi fitur **rekursif sanitasi payload** (`SecurityLog::sanitizePayload`) yang secara otomatis menyamarkan data sensitif (`password`, `current_password`, `token`, `_token`, `otp`, `pin`, `credit_card`) menjadi `[REDACTED_SEC_PAYLOAD]`.

---

### Pilar 3: System Maintenance & Health (Controller & UI)
- **`SystemMaintenanceController`** (`app/Http/Controllers/Superadmin/SystemMaintenanceController.php`):
  - **Environment & Health Audit**: Mendeteksi status `APP_ENV`, `APP_DEBUG` (alert merah menyala jika aktif di production), izin tulis folder `storage/` & `bootstrap/cache/`, HTTPS/SSL enforcement, koneksi DB, dan penggunaan kapasitas disk.
  - **Cache Manager (Terminal-Free)**: Eksekusi `optimize:clear`, `config:clear`, `route:clear`, `view:clear`, dan `cache:clear` langsung dari UI web dengan audit trail.
- **`LogViewerController`** (`app/Http/Controllers/Superadmin/LogViewerController.php`):
  - Membaca, mem-parsing, dan memfilter berkas `storage/logs/laravel.log`.
  - Filter interaktif berbasis level (`CRITICAL`, `ERROR`, `WARNING`, `INFO`, `DEBUG`) dan pencarian teks.
  - Fitur Download Log & Clear Log.
- **`SecurityAuditController`** (`app/Http/Controllers/Superadmin/SecurityAuditController.php`):
  - Manajemen IP Blacklist (tambah IP manual, unblock IP, hit tracking).
  - Forensic payload inspector modal via JSON endpoint.

---

### Pilar 4: Incident Response (DEFCON 1 - Global Panic Button)
- **Emergency Panic Execution (`panic()`)**:
  - Memvalidasi password Superadmin.
  - Menghasilkan token bypass darurat (`panic_...`).
  - **Force Logout Total**: Memutuskan seluruh sesi pengguna aktif (database session / file session di `storage/framework/sessions/`), serta mencabut token Sanctum.
  - **Maintenance Mode**: Menjalankan `php artisan down --secret=... --render=errors.503`.
  - Mengarahkan Superadmin ke URL bypass darurat `/{secret}` untuk menjaga akses web Superadmin.
- **Stand-Down / Normal Restore (`standDown()`)**:
  - Menjalankan `php artisan up` untuk menonaktifkan maintenance mode dan memulihkan operasi normal.

---

## 2. Struktur Database & Migrasi

### Tabel `ip_blacklists`
```sql
CREATE TABLE ip_blacklists (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) UNIQUE,
    reason VARCHAR(255),
    user_agent TEXT NULL,
    hit_count INT UNSIGNED DEFAULT 1,
    is_permanent BOOLEAN DEFAULT TRUE,
    blocked_until TIMESTAMP NULL,
    blocked_by VARCHAR(255) DEFAULT 'honeypot_system',
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Tabel `security_logs`
```sql
CREATE TABLE security_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) INDEX,
    user_agent TEXT NULL,
    event_type VARCHAR(60) INDEX,
    severity ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium' INDEX,
    endpoint VARCHAR(255),
    method VARCHAR(10),
    raw_payload LONGTEXT NULL,
    response_status SMALLINT UNSIGNED NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);
```

---

## 3. Hasil Pengujian Otomatis

Seluruh suite unit & feature test (120 tes, 550 asersi) berhasil lulus 100%:

```bash
PASS  Tests\Feature\Superadmin\SuperadminSecurityTest
✓ guest is redirected from superadmin dashboard
✓ admin and customer cannot access superadmin dashboard
✓ superadmin can access superadmin dashboard
✓ honeypot trap blocks and blacklists ip permanently
✓ sensitive payload is sanitized in security logs
✓ reauth middleware redirects when not reauthenticated
✓ superadmin reauth fails with invalid password
✓ superadmin reauth succeeds with correct password
✓ cache manager clears application cache
✓ panic button requires valid password
✓ panic button triggers emergency protocol and stand down restores
✓ superadmin can add and remove blacklisted ip

Tests: 120 passed (550 assertions)
Duration: 16.09s
```

---

## 4. Kredensial Default Superadmin (Seeder)
- **URL Login**: `/login` &rarr; otomatis redirect ke `/superadmin/dashboard`
- **Email**: `superadmin@tokobii.test`
- **Password**: `Superadmin123!`
