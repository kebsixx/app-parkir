# Skenario Demo UAS — ParkirKu

Panduan urutan demonstrasi live di hadapan dosen penguji (Minggu 12–15).

---

## 1. Persiapan Sebelum Demo

Buka **dua terminal** dan jalankan dua server agar fitur Dashboard dan Laporan dapat memanggil internal REST API tanpa deadlock (sesuai modul P10):

```bash
# Terminal 1 — Server Web Utama
php artisan serve --port=8000

# Terminal 2 — Server API Internal
php artisan serve --port=8011
```

Kredensial login demo:
- **Admin**: `admin@parkirku.test` / `password`
- **Petugas**: `petugas@parkirku.test` / `password`

---

## 2. Urutan Demo

### Langkah 1 — Login sebagai Admin
1. Buka browser: `http://127.0.0.1:8000/login`
2. Masukkan email `admin@parkirku.test` dan password `password`.
3. Tunjukkan bahwa sistem berhasil login, membuat session, dan redirect ke `/dashboard`.
4. Tunjukkan identitas nama dan role `Admin` pada sidebar bawah.

### Langkah 2 — Dashboard Statistik (P10)
1. Tunjukkan 3 kartu statistik: **Kendaraan parkir**, **Transaksi hari ini**, dan **Pendapatan hari ini**.
2. Jelaskan ke dosen: angka statistik ini diambil dari internal REST API `GET /api/stats` lewat Laravel HTTP Client (`Http::get()`) menggunakan server kedua di port 8011.

### Langkah 3 — Master Tarif Kendaraan (P5 & P8 Otorisasi)
1. Klik menu **Tarif** (`/vehicle-types`).
2. Tunjukkan daftar jenis kendaraan (`Motor`, `Mobil`, `Bus`) beserta tarif per jam.
3. Tambah jenis baru, misalnya:
   - Nama: `Sepeda Listrik`
   - Tarif: `1000`
4. Simpan, tunjukkan alert sukses flash message.

### Langkah 4 — Master Kendaraan (P3 & P5 Validasi + CRUD)
1. Klik menu **Kendaraan** (`/vehicles`).
2. Klik tombol **+ Tambah kendaraan**.
3. Daftarkan kendaraan baru:
   - Plat: `B 9876 DEMO` (tunjukkan bahwa plat otomatis di-uppercase).
   - Jenis: `Mobil`
   - Pemilik: `Dosen Penguji`
4. Simpan. Gunakan kotak pencarian untuk mencari `DEMO` secara live.

### Langkah 5 — Transaksi Check-in (P2, P3, P7 Transaksi)
1. Klik menu **Check-in** (`/parking/check-in`).
2. Ketik `DEMO` pada kotak pencarian kendaraan (Livewire reaktif).
3. Pilih kendaraan `B 9876 DEMO`.
4. Tekan tombol **Catat Masuk**.
5. Tunjukkan halaman sukses: kode tiket unik (contoh: `PKR-20261104-XXXX`), jam masuk, dan tarif per jam.

### Langkah 6 — Parkir Aktif & Checkout (P7 & Database Transaction)
1. Klik menu **Parkir Aktif** (`/parking/active`).
2. Tunjukkan kendaraan `B 9876 DEMO` berstatus aktif dengan durasi berjalan.
3. Klik tombol **Checkout** pada baris kendaraan tersebut.
4. Tunjukkan perhitungan biaya otomatis:
   - Masukkan nominal uang diterima (misal tagihan Rp 3.000, bayar Rp 5.000).
   - Tunjukkan kembalian dihitung real-time (Rp 2.000) oleh Livewire.
5. Klik **Konfirmasi Bayar**.
6. Tunjukkan bukti pembayaran lunas: status sesi berubah jadi `completed` dan tercatat di tabel `payments`.

### Langkah 7 — Laporan Transaksi (P9 & P10)
1. Klik menu **Laporan** (`/reports`).
2. Tunjukkan tabel transaksi selesai dan total pendapatan hari ini yang diambil via API `GET /api/parking-sessions?status=completed`.

### Langkah 8 — Demo REST API di Postman (P9 & P11)
Buka Postman dan uji endpoint berikut secara live:

1. `GET http://127.0.0.1:8000/api/vehicles`
   - Tunjukkan response JSON terstruktur via `VehicleResource`.
2. `GET http://127.0.0.1:8000/api/parking-sessions?status=parked`
   - Tunjukkan daftar sesi yang sedang aktif.
3. `POST http://127.0.0.1:8000/api/parking-sessions`
   - Body JSON: `{"vehicle_id": 1}`
   - Status code `201 Created`.
4. `GET http://127.0.0.1:8000/api/stats`
   - Tunjukkan status code `200 OK` dengan angka ringkasan.

### Langkah 9 — Verifikasi Otorisasi Petugas (P8)
1. Logout dari akun admin.
2. Login sebagai `petugas@parkirku.test` / `password`.
3. Tunjukkan bahwa menu **Tarif** dan **Laporan** tidak muncul di sidebar.
4. Coba akses manual URL `http://127.0.0.1:8000/vehicle-types` di browser.
5. Tunjukkan bahwa sistem menampilkan error `403 Forbidden` via middleware `CheckAdminRole`.

---

## 3. Catatan Konseptual untuk Tanya Jawab

1. **Kenapa butuh 2 server?**
   `php artisan serve` bersifat single-threaded di local development. Saat Controller memanggil API ke port yang sama, terjadi *deadlock* karena server menunggu respons dari dirinya sendiri yang sedang terkunci.
2. **Kenapa ada API Resource?**
   Mencegah kebocoran data sensitif Model (seperti password hash) dan menjaga format JSON tetap konsisten meskipun nama kolom database berubah di masa depan.
3. **Bagaimana relasi database-nya?**
   `VehicleType hasMany Vehicle`, `Vehicle hasMany ParkingSession`, `ParkingSession hasOne Payment`, dan relasi `belongsTo` User untuk audit log petugas.
