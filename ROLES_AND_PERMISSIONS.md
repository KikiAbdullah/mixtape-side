# MixtapeSide

# Roles, Permissions, Ownership & Moderation Architecture Specification

Version: 1.0 Production Ready

---

# 1. Overview

Dokumen ini mendefinisikan arsitektur otorisasi, kepemilikan data, moderasi kontribusi, audit trail, dan keamanan aplikasi MixtapeSide.

Tujuan utama:

-   Menjaga integritas database utama (Live Data)
-   Memungkinkan kontribusi komunitas secara aman
-   Menyediakan ownership system untuk entitas terverifikasi
-   Menyediakan audit trail penuh untuk seluruh aktivitas penting
-   Mencegah race condition dan konflik data
-   Menyediakan fondasi yang scalable untuk pertumbuhan data

---

# 2. User Hierarchy

Aplikasi memiliki empat role utama.

## SUPERADMIN

Level tertinggi.

Hak akses:

-   Bypass seluruh permission
-   Mengakses debug tools
-   Mengakses system logs
-   Mengelola roles dan permissions
-   Mengelola seluruh data

Implementasi:

```php
Gate::before(function ($user, $ability) {
    return $user->hasRole('SUPERADMIN')
        ? true
        : null;
});
```

---

## ADMIN

Kurator dan moderator platform.

Hak akses:

-   Mengelola seluruh data
-   Approve Draft
-   Reject Draft
-   Approve Claim
-   Reject Claim
-   Mengelola user
-   Mengelola moderation queue

---

## VERIFIED_ENTITY

Pemilik entitas yang telah diverifikasi.

Hak akses:

-   Mengelola aset yang dimiliki
-   Membuat data baru
-   Mengedit data yang terkait dengan entitas miliknya
-   Bypass moderation untuk aset miliknya

---

## REGISTERED_USER

Kontributor publik.

Hak akses:

-   Melihat backend tertentu
-   Membuat kontribusi melalui Draft System
-   Tidak dapat mengubah Live Data secara langsung

---

# 3. Permission Naming Convention

Format:

```text
[module]_[action]
```

Contoh:

```text
bands_view
bands_create
bands_edit
bands_delete
```

---

# 4. Permission List

## User & Access

```text
users_view
users_create
users_edit
users_delete

roles_view
roles_create
roles_edit
roles_delete

permissions_view
```

---

## Bands

```text
bands_view
bands_create
bands_edit
bands_delete
bands_claim
```

---

## Releases

```text
releases_view
releases_create
releases_edit
releases_delete
```

---

## Labels

```text
labels_view
labels_create
labels_edit
labels_delete
```

---

## Gigs

```text
gigs_view
gigs_create
gigs_edit
gigs_delete
```

---

## Organizers

```text
organizers_view
organizers_create
organizers_edit
organizers_delete
```

---

## Zines

```text
zines_view
zines_create
zines_edit
zines_delete
```

---

## Moderation

```text
moderation_view
moderation_approve
moderation_reject

claims_view
claims_approve
claims_reject
```

---

## Utilities

```text
dashboard_view
audit_logs_view
debug_view
```

---

# 5. Permission Matrix

| Permission      | SUPERADMIN | ADMIN | VERIFIED | REGISTERED |
| --------------- | ---------- | ----- | -------- | ---------- |
| View Data       | ✓          | ✓     | ✓        | ✓          |
| Create Data     | ✓          | ✓     | ✓        | Draft      |
| Edit Data       | ✓          | ✓     | Owner    | Draft      |
| Delete Data     | ✓          | ✓     | Owner    | ✗          |
| Approve Draft   | ✓          | ✓     | ✗        | ✗          |
| Reject Draft    | ✓          | ✓     | ✗        | ✗          |
| Claim Ownership | ✓          | ✓     | ✓        | ✓          |
| Manage Users    | ✓          | ✓     | ✗        | ✗          |

---

# 6. Ownership Model

Ownership bersifat hierarkis.

Contoh:

```text
Band
 ├─ Release
 ├─ Release
 └─ Release
```

Hanya parent entity yang menyimpan owner.

Contoh:

```sql
bands
```

```sql
owner_id
```

Sedangkan release hanya menyimpan:

```sql
band_id
```

Policy:

```php
return $release->band->owner_id === $user->id;
```

---

# 7. Ownership Claims

Tabel:

```sql
ownership_claims
```

## Fields

| Column         | Type      |
| -------------- | --------- |
| id             | bigint    |
| user_id        | bigint    |
| entity_type    | string    |
| entity_id      | bigint    |
| proof_document | string    |
| notes          | text      |
| status         | enum      |
| reviewed_by    | bigint    |
| created_at     | timestamp |

Status:

```text
Pending
Approved
Rejected
Expired
```

---

# 8. Data Draft System

Semua kontribusi user masuk ke Draft System.

Live Data tidak boleh dimodifikasi langsung oleh Registered User.

---

## data_drafts

| Column            | Type            |
| ----------------- | --------------- |
| id                | bigint          |
| user_id           | bigint          |
| target_table      | string          |
| target_id         | bigint nullable |
| version           | integer         |
| original_snapshot | json            |
| proposed_data     | json            |
| change_summary    | string          |
| status            | enum            |
| reviewed_by       | bigint nullable |
| created_at        | timestamp       |

---

## Draft Status

```text
Pending
Under Review
Applied
Rejected
Expired
Cancelled
```

---

# 9. Optimistic Locking

Saat draft dibuat:

```json
{
    "name": "Mocca",
    "city": "Bandung"
}
```

Data tersebut disimpan pada:

```sql
original_snapshot
```

Saat approval:

```php
if ($draft->original_snapshot !== $currentData) {
    throw new ConflictException();
}
```

Tujuan:

Mencegah overwrite perubahan terbaru.

---

# 10. Duplicate Draft Prevention

Validasi:

```php
DataDraft::where(
    'user_id',
    auth()->id()
)
```

dan:

```php
target_table
target_id
status = Pending
```

Tidak boleh lebih dari satu draft aktif.

---

# 11. Approval Lock

Approval wajib menggunakan transaction.

```php
DB::transaction(function () {

    $draft = DataDraft::lockForUpdate()
        ->findOrFail($id);

});
```

Tujuan:

Mencegah double approval.

---

# 12. Asset Lifecycle

Draft file:

```text
storage/app/public/drafts
```

Claim file:

```text
storage/app/public/claims
```

---

## Approval

File dipindahkan:

```php
Storage::move(
    'drafts/file.jpg',
    'bands/file.jpg'
);
```

---

## Rejection

Tetap berada di draft folder.

---

## Cleanup

Draft asset dihapus:

```text
Rejected + 7 hari
Expired + 7 hari
Cancelled + 7 hari
```

Claim asset dihapus:

```text
Rejected + 30 hari
Expired + 30 hari
```

---

# 13. Moderation Comments

Tabel:

```sql
draft_comments
```

| Column     | Type      |
| ---------- | --------- |
| id         | bigint    |
| draft_id   | bigint    |
| user_id    | bigint    |
| comment    | text      |
| created_at | timestamp |

Tujuan:

Diskusi reviewer dan kontributor.

---

# 14. Live Data Status

Seluruh entitas memiliki:

```sql
status
```

Nilai:

```text
Draft
Published
Archived
Hidden
```

---

# 15. Soft Delete Policy

Seluruh entitas utama wajib menggunakan:

```php
use SoftDeletes;
```

Meliputi:

```text
Bands
Releases
Labels
Gigs
Organizers
Zines
```

---

# 16. Audit Trail

Tabel:

```sql
user_logs
```

## Fields

| Column      | Type      |
| ----------- | --------- |
| id          | bigint    |
| actor_id    | bigint    |
| action      | string    |
| entity_type | string    |
| entity_id   | bigint    |
| old_values  | json      |
| new_values  | json      |
| ip          | string    |
| user_agent  | text      |
| created_at  | timestamp |

---

## Logged Events

```text
LOGIN_SUCCESS
LOGIN_FAILED

APPROVE_DRAFT
REJECT_DRAFT

APPROVE_CLAIM
REJECT_CLAIM

ROLE_CHANGED
PERMISSION_CHANGED

CREATE_RECORD
UPDATE_RECORD
DELETE_RECORD
```

---

# 17. Activity Feed

Terpisah dari audit log.

Contoh:

```text
User mengajukan Draft
Admin menyetujui Band
Claim disetujui
Release baru diterbitkan
```

Tujuan:

Dashboard Activity.

---

# 18. Database Constraints

Gunakan:

```sql
FOREIGN KEY
```

dan:

```sql
ON DELETE RESTRICT
```

Untuk relasi penting.

---

# 19. Required Indexes

## data_drafts

```sql
INDEX(target_table)
INDEX(target_id)
INDEX(status)
INDEX(user_id)
INDEX(created_at)
```

---

## ownership_claims

```sql
INDEX(user_id)
INDEX(entity_type)
INDEX(entity_id)
INDEX(status)
```

---

## user_logs

```sql
INDEX(actor_id)
INDEX(action)
INDEX(created_at)
```

---

# 20. Unique Constraints

## Bands

```sql
UNIQUE(slug)
```

## Labels

```sql
UNIQUE(slug)
```

## Releases

```sql
UNIQUE(slug)
```

## Ownership Claims

Cegah claim ganda.

```sql
UNIQUE(
 user_id,
 entity_type,
 entity_id,
 status
)
```

---

# 21. Slug Strategy

Contoh:

```text
mocca
mocca-2
mocca-3
```

Slug wajib unik.

---

# 22. Search Architecture

Gunakan Fulltext Index.

Target:

```text
bands.name
releases.title
labels.name
zines.title
```

Persiapan migrasi ke Elasticsearch/OpenSearch jika diperlukan.

---

# 23. Route Protection

Contoh:

```php
Route::middleware('can:releases_view')
```

Untuk akses baca.

```php
Route::middleware('can:releases_create')
```

Untuk akses tulis.

---

# 24. Blade Directives

```blade
@can('releases_create')
```

Menampilkan tombol Create.

```blade
@if(auth()->user()->hasRole('ADMIN'))
```

Menampilkan badge moderator.

```blade
@if($band->owner_id === auth()->id())
```

Menampilkan tombol Edit.

---

# 25. Permission Cache

Saat Role atau Permission berubah:

```php
app()
[
Spatie\Permission\PermissionRegistrar::class
]
->forgetCachedPermissions();
```

Wajib dijalankan.

---

# 26. API Security

Authentication:

```text
Laravel Sanctum
```

Rate Limiting:

```php
throttle:60,1
```

Endpoint sensitif:

```text
Login
Claim
Draft Submit
Moderation
```

---

# 27. Scheduled Jobs

## Cleanup Drafts

Jalankan harian.

Menghapus:

```text
Rejected > 7 hari
Expired > 7 hari
Cancelled > 7 hari
```

---

## Cleanup Claims

Menghapus file claim lama.

---

## Expire Claims

```text
Pending > 30 hari
```

Menjadi:

```text
Expired
```

---

## Rebuild Search Index

Jalankan berkala.

---

## Purge Old Logs

Opsional:

```text
Lebih dari 2 tahun
```

---

# 28. Conflict Handling

Sebelum approval:

```php
$model = $targetModel::find($draft->target_id);
```

Jika tidak ditemukan:

```text
Expired
```

Catatan:

```text
Source data no longer exists.
```

---

# 29. Security Principles

-   Least Privilege
-   Ownership Based Access
-   Audit Everything
-   Draft First Workflow
-   Soft Delete First
-   Explicit Moderation
-   Immutable Audit Trail

---

# 30. Production Readiness Checklist

-   Permission System
-   Ownership System
-   Draft Moderation
-   Claim Moderation
-   Audit Logs
-   Activity Feed
-   Search Index
-   Slug Management
-   API Security
-   Scheduled Jobs
-   Optimistic Locking
-   Race Condition Protection
-   Soft Deletes
-   Asset Lifecycle Management

Status:

READY FOR IMPLEMENTATION
