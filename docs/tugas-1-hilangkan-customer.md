# Tugas 1 — Hilangkan Role Customer & Bersihkan Logic

**Tujuan:**
Sistem Pharmadesk dijadikan tools internal saja (Admin, Team Expert, Tech). Role `customer` dan semua logic khusus customer tidak dipakai lagi.

## Ruang Lingkup Perubahan

1. **Role & User**
   - Hapus role `customer` dari seeder `RoleSeeder`.
   - Hapus user `customer@pharmadesk.test` dari `UserSeeder`.
   - Pastikan tidak ada middleware / pengecekan yang mengandalkan `role = customer`.

2. **Struktur Data Tiket**
   - Kolom `customer_id` di tabel `tickets` **tidak dipakai lagi** (biarkan nullable untuk sekarang).
   - Field `source`:
     - Hilangkan opsi `customer`.
     - Sisa: `team_expert`, `internal`.

3. **Controller & Logic Tiket** (`TicketController`)
   - `index()`:
     - Hapus filter `if ($roleSlug === 'customer') { where customer_id = user->id }`.
     - Role:
       - `team_expert` → lihat semua tiket (dengan opsi filter "my_expert").
       - `tech` → tiket dengan `tech_id = user->id`.
       - `admin` → semua tiket.
   - `create()`:
     - `sourceOptions` tidak punya lagi `customer`.
   - `store()`:
     - Hapus aturan allowed source untuk `customer`.
     - Tidak lagi mengisi `customer_id` dari user login.
     - Tetap: jika role `team_expert` → isi `expert_id = user->id`.

4. **Tampilan (Blade Views)**
   - `tickets/create.blade.php`:
     - Dropdown "Sumber Tiket" hanya tampilkan `Team Expert` / `Internal`.
   - `tickets/show.blade.php` (Penanggung Jawab):
     - Opsi 1: sembunyikan baris "Customer".
     - Opsi 2: gunakan teks biasa (mis. nama customer dalam deskripsi), bukan relasi user.
   - `auth/login.blade.php`:
     - Hapus contoh akun `customer@pharmadesk.test` dari kotak "Akun uji coba".

5. **Status & Flow**
   - Tidak ada aksi langsung dari customer di sistem (tidak perlu pengaturan status oleh customer).
   - Status "Menunggu respon customer" tetap dipakai, tapi hanya bisa di-set oleh Tech/Expert/Admin.

## Catatan Implementasi

- Langkah penghapusan kolom `customer_id` secara fisik dari database bisa dilakukan nanti dengan migration terpisah jika sudah yakin tidak pernah dipakai lagi.
- Setelah perubahan, sistem resmi hanya mengenal 3 role aktif:
  - `admin`
  - `team_expert`
  - `tech`
