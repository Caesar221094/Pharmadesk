# Pharmadesk Helpdesk Apotek - Arsitektur & ERD

## ERD Tingkat Tinggi (Deskriptif)

Entitas utama:
- users: akun semua pengguna (admin, team expert, tim tech, customer apotek)
- roles: daftar role (admin, team_expert, tech, customer)
- pharmacies: data apotek yang memakai sistem kasir
- ticket_modules: modul sistem kasir (Penjualan, Stok, Resep, Laporan, dll.)
- ticket_categories: kategori kendala (Bug Sistem, Improvement Fitur, Error Data, Operasional Apotek, Lainnya)
- tickets: tiket kendala apotek
- ticket_activities: timeline aktivitas tiket (status, catatan, komentar)
- ticket_attachments: file lampiran (screenshot, log error)

Relasi kunci:
- users.role_id -> roles.id (many-to-one)
- tickets.pharmacy_id -> pharmacies.id (many-to-one)
- tickets.category_id -> ticket_categories.id (many-to-one)
- tickets.module_id -> ticket_modules.id (many-to-one)
- tickets.customer_id -> users.id (many-to-one, nullable)
- tickets.expert_id -> users.id (many-to-one, nullable)
- tickets.tech_id -> users.id (many-to-one, nullable)
- ticket_activities.ticket_id -> tickets.id (many-to-one)
- ticket_activities.user_id -> users.id (many-to-one)
- ticket_attachments.ticket_id -> tickets.id (many-to-one)
- ticket_attachments.user_id -> users.id (many-to-one)

## Status, Prioritas, dan Sumber Tiket

Status tiket (enum/string):
- open
- in_review
- in_progress
- waiting_customer
- solved
- closed

Prioritas tiket:
- low
- medium
- high
- urgent

Sumber tiket:
- customer
- team_expert
- internal

## Alur Tinggi Sistem

1. Customer Apotek membuat tiket (sumber = customer) dan mengisi:
   - apotek, PIC, kontak, versi aplikasi, modul, judul, deskripsi, kategori, prioritas.
2. Team Expert melakukan review, dapat mengubah kategori/prioritas/modul, dan menjadi penanggung jawab business.
3. Team Expert meneruskan ke Tim Tech dengan update status (in_progress) dan menambahkan catatan teknis awal.
4. Tim Tech mengerjakan, update progres melalui ticket_activities dan mengupload bukti (screenshot/log).
5. Jika butuh klarifikasi, status menjadi waiting_customer.
6. Setelah solusi diterapkan dan diverifikasi, status menjadi solved lalu closed.
7. Semua perubahan status dan catatan tercatat di ticket_activities sebagai timeline.

## Modul Dashboard

Dashboard menampilkan:
- total tiket
- tiket per status
- tiket per kategori
- tiket per modul
- tiket per prioritas
- daftar tiket urgent dan belum selesai

Data ini akan diambil dari tabel tickets dan ditampilkan via Chart.js di halaman Blade.

## API Endpoint (Ringkas)

Prefix API: `/api` (dengan middleware `auth:sanctum`)

- `GET /api/tickets` – list tiket (filter by status)
- `POST /api/tickets` – buat tiket baru
- `GET /api/tickets/{id}` – detail tiket
- `PUT/PATCH /api/tickets/{id}` – update tiket (termasuk status)
- `DELETE /api/tickets/{id}` – hapus tiket

Master data:

- `GET /api/ticket-categories` – list kategori
- `GET /api/ticket-modules` – list modul sistem
- `GET /api/pharmacies` – list apotek
- `GET /api/users` – list user

## Flow Sistem per Role

- Customer Apotek:
   - Login ke Pharmadesk
   - Membuat tiket kendala (form tiket)
   - Mengunggah bukti (di fase lanjut)
   - Memantau progres via halaman daftar & detail tiket

- Team Expert:
   - Melihat tiket baru (status `open`)
   - Review dan mengubah kategori, prioritas, modul
   - Menjadi penanggung jawab bisnis (set `expert_id`)
   - Mengkomunikasikan kebutuhan ke Tim Tech
   - Update progres dan status ke customer

- Tim Tech:
   - Melihat tiket yang di-assign (status `in_progress`)
   - Input catatan teknis pada timeline
   - Upload screenshot/log (via attachments)
   - Mengubah status menjadi `waiting_customer` jika butuh konfirmasi
   - Menandai `solved` dan `closed` setelah selesai

- Admin:
   - Kelola user dan role
   - Kelola master data (kategori, modul, apotek)
   - Mengakses laporan agregat dari dashboard
