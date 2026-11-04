# PRD — ParkirKu

**Produk:** Sistem Parkir Kendaraan Sederhana
**Nama kerja:** ParkirKu
**Jenis:** Final Project UAS Pemrograman Framework
**Versi:** 0.1 (Draft)
**Tanggal:** 4 November 2026

---

## 1. Ringkasan

ParkirKu adalah aplikasi web untuk mencatat kendaraan masuk, menghitung biaya parkir otomatis saat kendaraan keluar, mencatat pembayaran tunai, dan menyajikan laporan harian. Aplikasi dipakai petugas parkir di gerbang dan admin di kantor.

Aplikasi dibangun sebagai aplikasi Laravel 12 server-rendered dengan Blade, Livewire untuk interaksi dinamis, Tailwind CSS 4 untuk tampilan, dan PostgreSQL sebagai database.

---

## 2. Latar Belakang

Pencatatan parkir manual rawan:
- tiket hilang atau tertukar,
- salah hitung durasi dan tarif,
- uang masuk tidak tercatat rapi,
- laporan harian harus dihitung ulang.

ParkirKu menggantikan proses itu dengan alur digital yang singkat: cari kendaraan, catat masuk, hitung keluar, bayar, selesai.

---

## 3. Tujuan

1. Mempercepat proses check-in dan checkout.
2. Menghitung tarif parkir secara otomatis dan konsisten.
3. Mencatat setiap pembayaran tanpa duplikasi.
4. Menyediakan dashboard dan laporan harian.
5. Menyediakan REST API untuk data kendaraan, sesi parkir, dan statistik.

### Non-goals

Tidak termasuk dalam MVP:
- pembayaran online / payment gateway,
- RFID, barcode scanner, atau integrasi gerbang otomatis,
- multi-lokasi parkir,
- reservasi slot,
- member / langganan parkir,
- aplikasi mobile native,
- notifikasi WhatsApp/SMS,
- multi-role kompleks di luar `admin` dan `petugas`.

---

## 4. Pengguna dan Hak Akses

| Peran | Kebutuhan utama | Hak akses |
|---|---|---|
| Admin | Mengelola tarif, kendaraan, laporan | Semua fitur |
| Petugas | Melayani check-in/checkout | Dashboard, kendaraan, check-in, sesi aktif, checkout |

Aturan:
- Hanya admin yang boleh mengubah tarif kendaraan.
- Hanya admin yang boleh membuka halaman laporan.
- Petugas tetap boleh melihat dashboard dan mendaftarkan kendaraan baru.
- Semua route selain login wajib melewati middleware `auth`.

---

## 5. Alur Bisnis Utama

### 5.1 Check-in

1. Petugas login.
2. Petugas mencari nomor polisi.
3. Jika kendaraan belum terdaftar, petugas membuat data kendaraan.
4. Petugas memilih kendaraan dan menekan **Catat Masuk**.
5. Sistem membuat sesi parkir dengan status `parked`, kode tiket unik, waktu masuk, dan snapshot tarif per jam.
6. Sistem menolak check-in jika kendaraan masih punya sesi aktif.

### 5.2 Checkout

1. Petugas mencari kode tiket atau nomor polisi.
2. Sistem menampilkan durasi dan total biaya.
3. Petugas memasukkan uang diterima.
4. Sistem menghitung kembalian.
5. Petugas menekan **Konfirmasi Bayar**.
6. Dalam satu database transaction:
   - sesi diubah menjadi `completed`,
   - waktu keluar, durasi, dan total biaya disimpan,
   - pembayaran dibuat satu kali.
7. Sistem menolak checkout ulang untuk sesi yang sudah selesai.

### 5.3 Laporan

1. Admin memilih rentang tanggal.
2. Sistem menampilkan sesi selesai, jumlah transaksi, total pendapatan, dan rincian pembayaran.

---

## 6. Ruang Lingkup MVP

### 6.1 Autentikasi
- Login dan logout berbasis session.
- Validasi kredensial salah.
- Redirect ke dashboard setelah login.
- Nama dan role user tampil di navbar.
- Middleware `auth` dan `CheckAdminRole`.

### 6.2 Master Tarif
- CRUD jenis kendaraan.
- Field: nama jenis, tarif per jam.
- Contoh: Motor, Mobil, Bus.
- Tidak boleh menghapus jenis kendaraan yang sudah dipakai.

### 6.3 Master Kendaraan
- CRUD kendaraan.
- Field: nomor polisi, jenis kendaraan, nama pemilik (opsional), merek (opsional), warna (opsional).
- Nomor polisi disimpan uppercase dan unik.
- Pencarian berdasarkan nomor polisi atau nama pemilik.

### 6.4 Transaksi Parkir
- Check-in kendaraan.
- Daftar sesi aktif.
- Checkout dan pembayaran tunai.
- Kode tiket unik.
- Satu kendaraan hanya boleh punya satu sesi aktif.

### 6.5 Dashboard
- Jumlah kendaraan sedang parkir.
- Jumlah transaksi selesai hari ini.
- Pendapatan hari ini.
- Daftar 5 sesi aktif terbaru.
- Tombol cepat menuju check-in.

### 6.6 Laporan
- Filter tanggal mulai dan tanggal akhir.
- Ringkasan jumlah transaksi dan total pendapatan.
- Tabel sesi selesai: tiket, nomor polisi, jenis, masuk, keluar, durasi, tarif, total.
- Halaman ramah cetak (opsional, bukan syarat MVP).

### 6.7 REST API
- API Resource untuk kendaraan, sesi parkir, dan pembayaran.
- Filter dan pagination.
- Endpoint statistik.
- Diuji lewat Postman.

### 6.8 Data Demo
- Seeder untuk user, jenis kendaraan, kendaraan, dan beberapa sesi.
- Factory untuk kendaraan dan sesi.
- Data bergaya Indonesia: plat `B 1234 XYZ`, nama orang Indonesia, tarif rupiah.

---

## 7. Model Data

Database: PostgreSQL. Total 5 tabel.

### 7.1 `users`

| Kolom | Tipe | Constraint |
|---|---|---|
| id | bigint | primary key |
| name | varchar(100) | not null |
| email | varchar(150) | unique, not null |
| password | varchar | not null |
| role | varchar(20) | `admin` atau `petugas`, default `petugas` |
| timestamps | timestamp | |

### 7.2 `vehicle_types`

| Kolom | Tipe | Constraint |
|---|---|---|
| id | bigint | primary key |
| name | varchar(50) | unique, not null |
| hourly_rate | integer | not null, >= 0 |
| timestamps | timestamp | |

### 7.3 `vehicles`

| Kolom | Tipe | Constraint |
|---|---|---|
| id | bigint | primary key |
| vehicle_type_id | bigint | FK ke `vehicle_types`, restrict delete |
| plate_number | varchar(20) | unique, not null, uppercase |
| owner_name | varchar(100) | nullable |
| brand | varchar(50) | nullable |
| color | varchar(30) | nullable |
| timestamps | timestamp | |

Index: `vehicle_type_id`, `plate_number`.

### 7.4 `parking_sessions`

| Kolom | Tipe | Constraint |
|---|---|---|
| id | bigint | primary key |
| ticket_code | varchar(30) | unique, not null |
| vehicle_id | bigint | FK ke `vehicles`, restrict delete |
| check_in_by | bigint | FK ke `users` |
| check_out_by | bigint | nullable, FK ke `users` |
| hourly_rate | integer | snapshot tarif saat check-in |
| check_in_at | timestamp | not null |
| check_out_at | timestamp | nullable |
| duration_minutes | integer | nullable |
| total_fee | integer | nullable |
| status | varchar(20) | `parked` atau `completed`, default `parked` |
| timestamps | timestamp | |

Index: `status`, `check_in_at`, `vehicle_id`.

Catatan: PostgreSQL tidak punya partial unique index yang bisa langsung dibuat lewat Blueprint Laravel untuk aturan “satu sesi aktif per kendaraan”. Aturan ini dijaga di application layer di dalam database transaction, bukan lewat unique index biasa.

### 7.5 `payments`

| Kolom | Tipe | Constraint |
|---|---|---|
| id | bigint | primary key |
| parking_session_id | bigint | unique, FK ke `parking_sessions` |
| received_by | bigint | FK ke `users` |
| method | varchar(20) | default `cash` |
| amount | integer | total tagihan |
| paid_amount | integer | uang diterima |
| change_amount | integer | kembalian |
| paid_at | timestamp | not null |
| timestamps | timestamp | |

Unique `parking_session_id` mencegah satu sesi dibayar dua kali.

---

## 8. Aturan Bisnis

1. Nomor polisi dinormalisasi: trim, uppercase, spasi berlebih dirapikan. Contoh `b 1234 xyz` menjadi `B 1234 XYZ`.
2. Satu kendaraan hanya boleh punya satu sesi `parked`. Cek dilakukan dengan `lockForUpdate()` di dalam transaction.
3. Tarif disimpan sebagai snapshot `hourly_rate` di sesi saat check-in. Perubahan tarif tidak mengubah transaksi lama.
4. Durasi dihitung dari `check_out_at - check_in_at` dalam menit.
5. Tarif dihitung per jam penuh, dibulatkan ke atas, minimum 1 jam:
   `total_fee = ceil(duration_minutes / 60) * hourly_rate`.
6. `duration_minutes` minimal 1 menit; biaya minimum 1 jam.
7. Checkout hanya boleh untuk sesi `parked`.
8. Pembayaran hanya boleh dibuat sekali per sesi.
9. `paid_amount >= amount`. `change_amount = paid_amount - amount`.
10. Metode pembayaran MVP hanya `cash`.
11. Timezone aplikasi: `Asia/Jakarta`.
12. Jenis kendaraan yang sudah dipakai tidak boleh dihapus.
13. Kendaraan yang sudah punya riwayat sesi tidak boleh dihapus.
14. Laporan hanya menampilkan sesi `completed` yang punya pembayaran.
15. API tidak memakai token/session auth sesuai cakupan modul P9. Aplikasi hanya untuk demo lokal, bukan untuk deploy publik.

---

## 9. Kebutuhan Fungsional

### FR-01 — Login dan Logout

**Acceptance criteria**
- User bisa login dengan email dan password.
- Kredensial salah menampilkan pesan error dan tidak membuat session.
- Logout menghapus session dan redirect ke halaman login.
- Route selain login redirect ke `/login` jika belum authenticate.

### FR-02 — Dashboard

**Acceptance criteria**
- Menampilkan jumlah kendaraan yang sedang parkir.
- Menampilkan jumlah transaksi selesai hari ini.
- Menampilkan total pendapatan hari ini.
- Menampilkan 5 sesi aktif terbaru.
- Data dashboard diambil dari `GET /api/stats` lewat Laravel HTTP Client.
- Jika API tidak bisa dihubungi, halaman tetap tampil dengan nilai fallback `0` dan pesan peringatan.

### FR-03 — CRUD Jenis Kendaraan

**Acceptance criteria**
- Admin bisa menambah, melihat, mengubah, dan menghapus jenis kendaraan.
- Nama jenis kendaraan unik.
- Tarif per jam berupa bilangan bulat >= 0.
- Petugas tidak bisa mengakses route ini.
- Delete ditolak jika jenis kendaraan sudah dipakai kendaraan.

### FR-04 — CRUD Kendaraan

**Acceptance criteria**
- Petugas dan admin bisa menambah, melihat, mengubah, dan menghapus kendaraan.
- Nomor polisi unik dan otomatis uppercase.
- Jenis kendaraan wajib dipilih.
- Pencarian berdasarkan nomor polisi atau nama pemilik.
- Pagination 10 data per halaman.
- Delete ditolak jika kendaraan punya riwayat sesi.

### FR-05 — Check-in

**Acceptance criteria**
- Petugas memilih kendaraan yang terdaftar.
- Sistem membuat `ticket_code` unik.
- Sistem menyimpan `hourly_rate` dari jenis kendaraan.
- Sesi baru berstatus `parked`.
- Check-in kedua untuk kendaraan yang sama ditolak dengan pesan jelas.
- Setelah sukses, petugas melihat kode tiket dan detail sesi.

### FR-06 — Daftar Sesi Aktif

**Acceptance criteria**
- Menampilkan semua sesi `parked`.
- Kolom: tiket, nomor polisi, jenis, waktu masuk, durasi berjalan, aksi checkout.
- Pencarian berdasarkan tiket, nomor polisi, atau nama pemilik.
- Durasi berjalan dihitung dari waktu sekarang.
- Data di-paginate.

### FR-07 — Checkout dan Pembayaran

**Acceptance criteria**
- Petugas bisa mencari sesi aktif lewat tiket atau nomor polisi.
- Sistem menampilkan durasi dan total biaya.
- Uang diterima wajib diisi dan >= total biaya.
- Kembalian dihitung otomatis.
- Checkout menyimpan pembayaran dan mengubah status sesi menjadi `completed` dalam satu transaction.
- Checkout ulang ditolak.
- Jika dua request checkout bersamaan, hanya satu yang berhasil.

### FR-08 — Laporan

**Acceptance criteria**
- Admin bisa memilih tanggal mulai dan tanggal akhir.
- Default filter: hari ini.
- Menampilkan jumlah transaksi dan total pendapatan.
- Menampilkan rincian sesi selesai.
- Data diambil dari `GET /api/parking-sessions?status=completed&date_from=...&date_to=...` lewat Laravel HTTP Client.
- Jika API gagal, halaman menampilkan pesan error dan tidak crash.

### FR-09 — REST API

**Acceptance criteria**
- Endpoint kendaraan mendukung GET list, GET detail, POST, PUT, DELETE.
- Endpoint sesi parkir mendukung GET list, GET detail, POST check-in, dan POST checkout.
- Endpoint stats mengembalikan ringkasan angka.
- Response memakai API Resource, bukan model mentah.
- Status code: 200, 201, 404, 422, 409 sesuai konteks.
- Filter dan pagination berfungsi.
- Diuji live di Postman saat demo.

### FR-10 — Seeder dan Factory

**Acceptance criteria**
- `php artisan migrate:fresh --seed` menghasilkan data demo lengkap.
- Ada minimal 2 user: admin dan petugas.
- Ada minimal 3 jenis kendaraan.
- Ada minimal 10 kendaraan.
- Ada sesi aktif dan sesi selesai dengan pembayaran.
- Data memakai nama dan plat Indonesia.

### FR-11 — Dokumentasi dan Demo

**Acceptance criteria**
- `README.md` berisi cara install, setup PostgreSQL, migrate, seed, dan menjalankan dua server.
- `DEMO.md` berisi skenario demo berurutan.
- Ada dokumentasi endpoint API.
- Repository punya branch `dev` dan `main`.
- Commit checkpoint `[P-1]` sampai `[P-10]`.

---

## 10. Spesifikasi REST API

Base URL: `/api`

| Method | Endpoint | Fungsi | Sukses |
|---|---|---|---|
| GET | `/vehicle-types` | Daftar jenis kendaraan | 200 |
| GET | `/vehicles` | Daftar kendaraan + search + pagination | 200 |
| POST | `/vehicles` | Tambah kendaraan | 201 |
| GET | `/vehicles/{id}` | Detail kendaraan | 200 |
| PUT | `/vehicles/{id}` | Ubah kendaraan | 200 |
| DELETE | `/vehicles/{id}` | Hapus kendaraan | 204 |
| GET | `/parking-sessions` | Daftar sesi + filter status/tanggal/search | 200 |
| POST | `/parking-sessions` | Check-in | 201 |
| GET | `/parking-sessions/{id}` | Detail sesi | 200 |
| POST | `/parking-sessions/{id}/checkout` | Checkout + pembayaran | 200 |
| GET | `/stats` | Statistik dashboard | 200 |

Resource:
- `VehicleTypeResource`
- `VehicleResource`
- `ParkingSessionResource`
- `PaymentResource`

Error:
- `404` resource tidak ditemukan.
- `422` validasi gagal.
- `409` kendaraan sudah parkir atau sesi sudah checkout.

Catatan keamanan: API ini tidak memakai autentikasi token sesuai cakupan modul. Jangan diekspos ke internet publik.

---

## 11. Kebutuhan Non-Fungsional

| Aspek | Ketentuan |
|---|---|
| Framework | Laravel 12 |
| Bahasa | PHP 8.2+ |
| Database | PostgreSQL 16+ |
| Frontend | Blade + Livewire 4 (manual install) + Tailwind CSS 4 |
| Build | Vite |
| JavaScript | Alpine.js bawaan Livewire, tanpa framework SPA |
| Auth | Session-based manual (AuthController), bukan starter kit |
| Validasi | Form Request untuk CRUD; validasi Livewire untuk form interaktif |
| Query | Eager loading untuk relasi yang ditampilkan berulang |
| Responsif | Mobile-first, nyaman di tablet gerbang dan desktop kantor |
| Aksesibilitas | Label form, focus visible, kontras memadai, target sentuh >= 44px |
| Testing | Feature test untuk check-in, checkout, fee, auth, dan API |
| Code style | Laravel Pint |
| Timezone | `Asia/Jakarta` |

### Persyaratan PostgreSQL

- Ekstensi PHP: `pdo_pgsql`, `pgsql`.
- `.env`:
  ```env
  DB_CONNECTION=pgsql
  DB_HOST=127.0.0.1
  DB_PORT=5432
  DB_DATABASE=parkirku
  DB_USERNAME=postgres
  DB_PASSWORD=
  ```
- Membuat database:
  ```bash
  createdb parkirku
  ```
- Menjalankan migrasi:
  ```bash
  php artisan migrate --seed
  ```
- Pencarian tidak boleh bergantung pada perilaku case-insensitive MySQL. Nomor polisi disimpan uppercase; pencarian nama pemilik memakai `LOWER(...) LIKE LOWER(...)` agar portabel.

---

## 12. Kesesuaian dengan Modul Laravel 12

| Pertemuan | Implementasi di ParkirKu |
|---|---|
| P1 | Setup Laravel 12, `.env` PostgreSQL, Git repo baru, branch `dev` |
| P2 | Resource route `vehicles`, `vehicle-types`, `parking-sessions`; route group middleware |
| P3 | Resource controller, Form Request, validasi, response redirect |
| P4 | Master layout Blade, partial navbar/alert, komponen Blade |
| P5 | Migration 5 tabel, Eloquent Model, CRUD master, pagination |
| P6 | UTS, tidak ada fitur baru |
| P7 | `VehicleType hasMany Vehicles`, `Vehicle hasMany ParkingSessions`, `ParkingSession hasOne Payment`; eager loading |
| P8 | `AuthController`, middleware `auth`, `CheckAdminRole`, info user di navbar |
| P9 | REST API + API Resource + pagination + filter + Postman |
| P10 | Dashboard dan laporan konsumsi API internal lewat Laravel HTTP Client, seeder/factory, dua server |
| P11 | Demo UAS dan tanya jawab |

Catatan: Livewire dipakai sebagai pelengkap interaksi, bukan pengganti seluruh Controller. CRUD utama tetap memakai resource controller dan Form Request agar materi P2/P3 tetap terpakai.

---

## 13. Rencana Implementasi

| Tahap | Fokus | Checkpoint commit |
|---|---|---|
| 1 | Setup project, PostgreSQL, Git, layout dasar | `[P-1]` |
| 2 | Routing dan halaman dummy | `[P-2]` |
| 3 | Controller, Form Request, validasi | `[P-3]` |
| 4 | Blade layout, partial, komponen | `[P-4]` |
| 5 | Migration, model, CRUD master | `[P-5]` |
| 6 | UTS | — |
| 7 | Relasi, sesi parkir, checkout | `[P-7]` |
| 8 | Auth dan middleware role | `[P-8]` |
| 9 | REST API dan API Resource | `[P-9]` |
| 10 | Dashboard/laporan via API, seeder/factory | `[P-10]` |
| 11 | Demo UAS | merge `dev` ke `main` |

---

## 14. Skenario Demo UAS

1. Login sebagai admin.
2. Tampilkan dashboard.
3. Tambah jenis kendaraan baru.
4. Tambah kendaraan baru.
5. Check-in kendaraan.
6. Tampilkan daftar parkir aktif.
7. Checkout kendaraan dan proses pembayaran.
8. Tampilkan laporan harian.
9. Demo API di Postman:
   - `GET /api/vehicles`
   - `GET /api/parking-sessions`
   - `POST /api/parking-sessions`
   - `POST /api/parking-sessions/{id}/checkout`
   - `GET /api/stats`
10. Jelaskan alasan dua server untuk dashboard/laporan.
11. Jawab pertanyaan konseptual.

---

## 15. Risiko dan Mitigasi

| Risiko | Mitigasi |
|---|---|
| Checkout ganda | Transaction + `lockForUpdate()` + unique `parking_session_id` di `payments` |
| Tarif berubah saat kendaraan masih parkir | Simpan snapshot `hourly_rate` di sesi |
| PostgreSQL tidak terpasang | Dokumentasikan `pdo_pgsql` dan `createdb` di README |
| Pencarian case-sensitive | Normalisasi plat uppercase, gunakan `LOWER()` untuk nama |
| Scope creep | Batasi ke 5 tabel dan fitur MVP |
| Dua server membingungkan saat demo | Dokumentasikan `INTERNAL_API_URL` dan perintah dua port |
| API terbuka | Hanya untuk demo lokal; beri catatan keamanan |

---

## 16. Pertanyaan Terbuka

1. Nama final aplikasi: tetap **ParkirKu** atau diganti?
2. Lokasi parkir: satu lokasi saja? (asumsi MVP: satu lokasi)
3. Pembayaran: hanya tunai? (asumsi MVP: tunai)
4. Apakah laporan perlu tombol cetak? (opsional)
5. Apakah petugas boleh menghapus kendaraan, atau hanya admin? (asumsi MVP: petugas boleh, dengan validasi riwayat)

---

## 17. Definisi Selesai

MVP dianggap selesai jika:
- Semua FR-01 sampai FR-11 terpenuhi.
- `php artisan test` lulus.
- `php artisan migrate:fresh --seed` berjalan di PostgreSQL.
- Demo check-in sampai checkout berhasil tanpa error.
- Dashboard dan laporan tampil dengan dua server berjalan.
- API berhasil diuji di Postman.
- README dan DEMO.md lengkap.
- Branch `main` sudah menerima merge dari `dev`.
