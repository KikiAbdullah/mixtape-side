# BOILERPLATE-KUNCIKARYA

## Deskripsi
Boilerplate ini dibangun menggunakan Laravel 7.x dan telah dioptimalkan secara mendalam untuk menjadi pondasi aplikasi web skala Enterprise (ERP, CRM, Admin Panel). Fokus utama boilerplate ini adalah pada performa, keamanan (Standard Security), dan kemudahan pengembangan (High Developer Experience).

## Fitur Unggulan (Pro-Level)

- **Standard Security (Anti-MD5)**: Menggunakan `Bcrypt` otomatis untuk semua password melalui model setter.
- **JWT Ready**: Terintegrasi dengan `tymon/jwt-auth` untuk kebutuhan REST API & Mobile App (Guard: `api`).
- **Image Processing**: Helper `uploadImage()` bertenaga `Intervention Image` (Auto Resize & Compression).
- **Global AJAX Intelligence**: Handler error global (401, 403, 500) dengan notifikasi **SweetAlert2**.
- **User Activity Audit**: Pencatatan otomatis aktivitas user ke database dengan indexing untuk performa tinggi.
- **Smart UI Stack**: Kombinasi **Bootstrap 5**, **Materialize**, dan **Remix Icon** yang ringan & modern.
- **Spatie Permission**: Role & Permission yang sudah terkonfigurasi untuk 3 level dasar (Superadmin, Admin, Staff).
- **Single-Page CRUD Pattern**: Pengalaman pengguna yang mulus dengan kombinasi DataTables Server-side dan Dynamic Form.

## Instalasi

### 1. Clone & Install
```bash
git clone https://github.com/username/repo.git
cd repo
composer install
npm install && npm run dev
```

### 2. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### 3. Database Setup
```bash
# Untuk instalasi awal
php artisan migrate --seed
```

## Panduan Fitur Khusus

### 1. **Image Upload Helper**
Upload & Resize otomatis (lebar 800px, kualitas 80%):
```php
$filename = uploadImage($request->file('avatar'), 'users');
```

### 2. **WhatsApp Integration**
Kirim notifikasi via `.env` configuration:
```php
kirimWA('0812345xxxx', 'Subject', 'Pesan Utama');
```

### 3. **Global AJAX & Notification**
Cukup gunakan redirect standar di Controller, notifikasi akan muncul otomatis:
```php
return redirect()->back()->with('success', 'Data Berhasil Disimpan!');
```

### 4. **JWT Authentication (API)**
Autentikasi API stateless menggunakan Bearer Token.
**Endpoints:**
- `POST /api/auth/login` - Mendapatkan token.
- `POST /api/auth/me` - Informasi user (Header: `Authorization: Bearer <token>`).
- `POST /api/auth/logout` - Logout & Invalidate token.
- `POST /api/auth/refresh` - Refresh token.

## Akun Default (Hasil Seeding)
- **Superadmin**: `superadmin` / `superadmin` (Akses Full)
- **Admin**: `admin` / `admin` (Akses Manajemen)
- **Staff**: `staff` / `staff` (Akses View Only)

---
*Built with ❤️ for High-Performance Web Apps.*