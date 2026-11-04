# ParkirKu — Sistem Parkir Kendaraan

Aplikasi manajemen operasional parkir kendaraan digital berbasis **Laravel 12**, **Blade**, **Livewire 4**, **Tailwind CSS 4**, dan **PostgreSQL**. Dibangun sebagai Final Project UAS mata kuliah Pemrograman Framework dengan pemenuhan seluruh capaian praktikum Pertemuan 1 s.d. 10.

---

## Fitur Utama

- **Autentikasi & Otorisasi Manual (P8)**: Login berbasis session tanpa starter kit, proteksi middleware `auth`, dan pembagian role `admin` vs `petugas` dengan custom middleware `CheckAdminRole`.
- **Master Data (P3 & P5)**: CRUD Jenis Kendaraan & Tarif per jam (khusus Admin) serta CRUD Data Kendaraan dengan nomor polisi otomatis uppercase dan pencarian.
- **Transaksi Parkir Nyata (P7)**: Check-in kendaraan dengan kode tiket unik, perhitungan tarif durasi per jam dibulatkan ke atas, dan checkout pembayaran tunai dengan `DB::transaction` anti-race condition.
- **Livewire Interaktif**: Pencarian kendaraan instan saat check-in dan kalkulasi uang kembalian real-time saat checkout.
- **REST API (P9)**: Endpoint lengkap JSON dengan transformasi `API Resource` untuk kendaraan, sesi parkir, dan ringkasan statistik.
- **Internal API Consumer (P10)**: Halaman Dashboard dan Laporan mengonsumsi endpoint internal REST API via Laravel HTTP Client (`Http::get()`).

---

## Software & Kebutuhan Lingkungan

- **PHP** >= 8.2 (dengan ekstensi `pdo_pgsql` aktif)
- **Composer** >= 2.x
- **PostgreSQL** >= 16.x
- **Node.js** >= 20.x & **NPM**

---

## Panduan Instalasi Lokal

### 1. Clone & Masuk Folder Project

```bash
git clone <url-repository-anda>
cd app-parkir
```

### 2. Pasang Dependency Backend & Frontend

```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Environment Database

Salin `.env.example` ke `.env`:

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi PostgreSQL di file `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=parkirku
DB_USERNAME=postgres
DB_PASSWORD=123456

INTERNAL_API_URL=http://127.0.0.1:8011
```

> **Catatan:** Buat database `parkirku` terlebih dahulu di PostgreSQL bila belum ada:
> `createdb -U postgres parkirku` atau via pgAdmin / DBeaver.

### 4. Migrasi & Seeder Database

Jalankan perintah migrasi dan pengisian data dummy awal:

```bash
php artisan migrate:fresh --seed
```

Data awal yang otomatis terbuat:
- **Admin**: `admin@parkirku.test` / password: `password`
- **Petugas**: `petugas@parkirku.test` / password: `password`
- 3 Jenis Kendaraan (`Motor`, `Mobil`, `Bus`)
- 12 Kendaraan plat nomor Indonesia dengan sesi parkir aktif & riwayat selesai

---

## Menjalankan Project (Penting: Butuh 2 Server)

Sesuai materi **Pertemuan 10**, server built-in `php artisan serve` bersifat single-thread. Agar halaman Dashboard dan Laporan dapat memanggil REST API internal tanpa mengalami *deadlock*, jalankan **dua instance server** di dua terminal berbeda:

```bash
# Terminal 1 — Server Web Utama (Akses browser di port 8000)
php artisan serve --port=8000

# Terminal 2 — Server Internal REST API (Port 8011)
php artisan serve --port=8011
```

Buka aplikasi di browser:
👉 **http://127.0.0.1:8000**

---

## Menjalankan Pengujian Otomatis

Aplikasi dilengkapi suite test Feature & Unit lengkap:

```bash
php artisan test
```

---

## Panduan Skenario Demo UAS

Langkah-langkah lengkap dan urutan demo di hadapan dosen penguji tersedia di file:
👉 **[DEMO.md](./DEMO.md)**
