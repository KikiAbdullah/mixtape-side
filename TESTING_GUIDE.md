# Panduan Pengujian (Testing Tutorial) - MixtapeSide

Dokumen ini berisi panduan langkah demi langkah untuk menguji seluruh fitur aplikasi MixtapeSide berdasarkan 4 peran (roles) utama. 

**Catatan:** Semua akun menggunakan password default: `password`

---

## 1. Akun Pengujian (Test Credentials)

| Role | Username | Kegunaan Utama |
| :--- | :--- | :--- |
| **SUPERADMIN** | `superadmin` | Mengelola Role/Permission, Log Sistem, & Debugging |
| **ADMIN** | `curator` | Moderasi Data Draft, Verifikasi Klaim, Manajemen Data Global |
| **VERIFIED_ENTITY** | `band_owner` | Kelola Aset Sendiri (Bypass Moderasi) |
| **REGISTERED_USER** | `contributor` | Kirim Usulan Data (Masuk Moderasi) |

---

## 2. Skenario Pengujian per Role

### A. Role: SUPERADMIN (`superadmin`)
*Tujuan: Memastikan kontrol penuh sistem.*

1. **Dashboard Overview**:
   - Pastikan kartu statistik menampilkan jumlah data terbaru.
   - Pastikan tabel "Log Aktivitas Terbaru" mencatat login Anda.
2. **User & Access**:
   - Buka menu **User Setup > Users**. Coba edit salah satu nama user.
   - Buka menu **User Setup > Roles**. Pastikan role `SUPERADMIN` memiliki status bypass (Gate::before).
3. **Debug Tools**:
   - Buka menu **Debug > Log Viewer**. Pastikan Anda bisa melihat log sistem Laravel.

### B. Role: ADMIN / KURATOR (`curator`)
*Tujuan: Memastikan alur moderasi dan manajemen data lintas user.*

1. **Moderation Queue (Data Drafts)**:
   - Buka menu **Management > Moderation Queue**.
   - Review data yang masuk. Coba klik **Approve** pada satu data.
   - **Cek**: Apakah data tersebut kini muncul di menu Management terkait (misal: Bands)?
2. **Global Edit**:
   - Buka menu **Management > Bands**.
   - Pilih band yang dibuat oleh user lain (misal: `contributor`).
   - Klik **Edit**. Pastikan Admin bisa mengubah data tersebut secara langsung.
3. **Claim Validation**:
   - Buka menu **Management > Ownership Claims** (jika sudah ada datanya).
   - Simulasikan proses persetujuan klaim profil.

### C. Role: VERIFIED_ENTITY (`band_owner`)
*Tujuan: Memastikan kepemilikan aset (Ownership) dan Bypass Moderasi.*

1. **Direct Update**:
   - Buka menu **Management > Bands**.
   - Cari band di mana Anda adalah owner-nya (biasanya di-assign saat klaim disetujui).
   - Klik **Edit**, ubah Bio atau Genre, klik **Save**.
   - **Cek**: Pastikan perubahan langsung LIVE (tidak masuk ke Moderation Queue).
2. **Manage Releases**:
   - Tambahkan Release baru untuk band Anda.
   - Masuk ke detail Release (**SHOW**), tambahkan beberapa **Tracks**.
   - Pastikan semua data tersimpan di Live Table.

### D. Role: REGISTERED_USER (`contributor`)
*Tujuan: Memastikan alur kontribusi aman (Draft System).*

1. **Auto-Slug UX**:
   - Buka menu **Management > Releases**.
   - Klik **Add New**. Ketik Judul: `Album Demo Pertama`.
   - **Cek**: Pastikan kolom Slug terisi `album-demo-pertama` secara otomatis.
2. **Contribution Workflow (Draft)**:
   - Buat satu data Band baru atau Release baru.
   - Klik **Save**.
   - **Cek**: Data Anda seharusnya **TIDAK** langsung muncul di tabel utama (jika sistem draft sudah terhubung ke UI) atau muncul di menu **My Drafts**.
3. **Claiming Profile**:
   - Buka halaman band publik yang belum memiliki owner.
   - Cari tombol "Claim this Band". Isi form klaim dan upload dokumen bukti.
   - Tunggu verifikasi dari Admin.

---

## 3. Fitur Teknis Khusus yang Wajib Diuji

### 1. Tracklist & Duration (`duration`)
- **Langkah**: Buka **Release Show**, klik **Add Track**.
- **Input**: Isi durasi dengan format string `04:20`.
- **Hasil**: Data harus tersimpan tanpa error `Data truncated`.

### 2. Soft Deletes
- **Langkah**: Hapus salah satu data Band sebagai Admin.
- **Hasil**: Data tidak hilang dari database (hanya kolom `deleted_at` terisi). Data tidak akan muncul lagi di tabel list publik.

### 3. Log Audit (Audit Trail)
- **Langkah**: Lakukan aksi Approve/Reject Draft sebagai Admin.
- **Hasil**: Buka tabel `user_logs` atau menu Logs. Pastikan tersimpan data `actor_id`, `ip`, `user_agent`, dan `new_values` (JSON).

### 4. Search & Remote Select (Select2)
- **Langkah**: Buka form Add Release. Ketik minimal 1 huruf pada pilihan Band.
- **Hasil**: Dropdown harus muncul dengan hasil pencarian dari server secara responsif (delay 500ms).

---

## 4. Troubleshooting
Jika menemukan error saat pengujian:
1. Tekan **F12** untuk melihat Console Log.
2. Cek file `.log` di folder `storage/logs/`.
3. Jalankan `php artisan cache:clear` jika permission tidak sinkron.
