# AI Technical Testing & Implementation Guide - MixtapeSide

Dokumen ini adalah panduan teknis mendalam untuk AI Agent atau Developer untuk memverifikasi fungsionalitas aplikasi MixtapeSide secara *end-to-end* (UI -> Route -> Controller -> Database).

---

## 1. Modul: Releases (Arsip Rilis Musik)

### A. Fitur: Create New Release
- **UI Element:** Form di `#dynamic-form` (di dalam `management.release.index`).
- **Input Fields:**
    - `band_id`: Select2 (Remote Data dari `/select/bands`).
    - `title`: String (Source untuk Auto-Slug).
    - `slug`: String (Auto-generated via JS `InitAutoSlug`).
    - `release_type`: Select (EP, Full-length, dll).
    - `original_release_year`: Number.
    - `cover_file`: File (Image).
    - `banner_file`: File (Image).
    - `description`: Textarea.
- **Workflow:**
    1. Klik Submit pada `#dform`.
    2. Request dikirim ke `POST /management/release` (Route: `management.release.store`).
    3. Controller: `ReleaseController@store` (via `CrudTrait`).
    4. Logika `store` di `CrudTrait`:
        - Validasi via `getRequest()`.
        - Assign `created_by` jika bukan Admin.
        - Memanggil `customStore()` -> `handleUploads()`.
        - File disimpan di `storage/app/public/releases/covers/` dan `banners/`.
- **Database Verification:**
    - Cek tabel `releases`: record baru harus muncul dengan `deleted_at = NULL` dan `status = 'Published'`.
    - Cek tabel `user_logs`: `action = add`, `menu = Releases Management`.

### B. Fitur: Show Detail & Track Management
- **UI Element:** Halaman `management/release/{id}` (View: `management.release.show`).
- **Logic:**
    - Menampilkan data dari model `Release` dengan relasi `band` dan `tracks`.
- **Action: Add Track**
    - Modal: `#modalTrack`.
    - Fields: `track_number` (int), `title` (string), `duration` (string format `04:20`), `lyrics` (text), `lyrics_translation` (text).
    - Submit ke: `POST /management/release/add-track`.
    - Controller: `ReleaseController@addTrack`.
    - Verification: Tabel `tracks` harus menyimpan `duration` sebagai string tanpa terpotong (truncated). Kolom `duration` bertipe `VARCHAR(20)`.

---

## 2. Modul: Bands (Manajemen Band)

### A. Fitur: Create / Edit Band
- **UI Element:** Form di `management.band.form`.
- **Input Fields:**
    - `name`, `slug`, `city`, `formed_year`, `genre` (comma separated string), `biography`.
- **Workflow:**
    1. Submit ke `management.band.store` atau `management.band.update`.
    2. Controller: `BandController`.
    3. `getRequest()` di `BandController` akan memecah string `genre` dan `alternative_names` menjadi array sebelum disimpan karena `$casts` pada Model Band mengaturnya sebagai `array` (JSON).
- **Database Verification:**
    - Kolom `genre` di tabel `bands` harus berisi data JSON (misal: `["Indie", "Rock"]`).
    - Cek `banner_url` di tabel `bands` (terintegrasi dalam migrasi utama).

---

## 3. Modul: Moderation Queue (Data Drafts)

### A. Fitur: Approval System
- **UI Element:** Tabel di `management/data-draft` (View: `management.data-draft.index`).
- **Logic:**
    - Admin klik tombol **Approve**.
    - Request ke `POST /management/data-draft/approve/{id}`.
    - Controller: `DataDraftController@approve`.
- **Technical Steps in Controller (Deep Dive):**
    1. `lockForUpdate()`: Record draft di-lock pada level database untuk mencegah *double approval* dari dua admin berbeda.
    2. **Optimistic Locking**: Bandingkan `original_snapshot` (snapshot data saat draft dibuat) dengan data live saat ini. Jika berbeda, return conflict error.
    3. `handleAssetMovement()`: Jika `proposed_data` berisi path `drafts/`, sistem memindahkan file ke folder permanen menggunakan `Storage::move()`.
    4. **Model Sync**: Menggunakan `Model::update()` atau `Model::create()` untuk mengaplikasikan data yang disetujui.
    5. **Audit Trail**: Entry baru di `user_logs` dengan `old_values` dan `new_values`.

---

## 4. Mekanisme Keamanan (Security Verification)

### A. Permission Check (Spatie)
- **Implementasi:** Middleware `can:permission_name` pada Route Group di `web.php`.
- **Test:** Login sebagai `contributor`.
- **Expected:** Menu **User Setup** tidak terlihat. Akses manual ke `/user-setup/user` menghasilkan 403 Forbidden.

### B. Ownership Check (Laravel Policy)
- **Implementasi:** Pengecekan `$this->authorize()` di `CrudTrait@update` dan `CrudTrait@destroy`.
- **Logic (ReleasePolicy):** `return $release->band->owner_id === $user->id;` (Hierarchical Ownership).
- **Langkah Testing:**
    1. Login sebagai `band_owner`.
    2. Cek tombol Edit pada Release milik band lain.
    3. Tombol harus tersembunyi (via Blade `@can`) dan request API harus ditolak jika dipaksa.

---

## 5. Global Technical Components (System Logic)

### A. Auto-Slug & JS Lifecycle
- **Script:** `swal.js` -> `InitAutoSlug`.
- **Initialization:** Dipanggil saat `document.ready` dan setiap kali konten form dimuat via AJAX (saat klik Edit).
- **Verification:** Ubah Nama Band, pastikan Slug berubah secara real-time.

### B. Remote Select (Ajax Select2)
- **Script:** `swal.js` -> `SelectRemoteData`.
- **Backend:** `SelectController` menggunakan `LIKE %...%` dengan limit 20 dan fitur pagination (`more`).
- **Optimasi:** Delay 500ms dan `cache: true` di sisi frontend untuk mengurangi beban server.

### C. Soft Deletes
- **Verification:** Jalankan delete pada modul manapun. Cek database: record tidak boleh hilang, melainkan kolom `deleted_at` terisi timestamp.

---

## 6. Database Integrity & Structure

### A. Constraints
1. **Foreign Keys:**
    - `releases.band_id` -> `bands.id` (`ON DELETE CASCADE`).
    - `tracks.release_id` -> `releases.id` (`ON DELETE CASCADE`).
    - `data_drafts.user_id` -> `users.id` (`ON DELETE RESTRICT`).
2. **Indexing:**
    - Index pada `slug` (Unique) di tabel `bands`, `releases`, `labels`.
    - Index pada `target_table`, `target_id`, dan `status` di tabel `data_drafts`.
    - Index pada `actor_id` dan `created_at` di tabel `user_logs`.

### B. Audit Log Structure
- Tabel `user_logs` wajib menyimpan:
    - `actor_id`: ID user yang melakukan aksi.
    - `old_values`: JSON data sebelum perubahan (untuk update/approve).
    - `new_values`: JSON data setelah perubahan.
    - `ip`: IP Address pelaku.
    - `user_agent`: Browser/Device pelaku.

---

## 7. Ownership Claims Flow
1. **Submit:** User mengirim `ownership_claims` (status: `Pending`).
2. **Review:** Admin meninjau dokumen bukti.
3. **Approve:**
    - `ownership_claims.status` -> `Approved`.
    - `user.role` -> `VERIFIED_ENTITY`.
    - `entity.owner_id` -> `user_id`.
4. **Log:** Catat aksi `APPROVE_CLAIM` di audit log.
