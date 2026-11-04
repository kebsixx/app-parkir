# Design — ParkirKu

**Dokumen:** UI/UX dan implementasi tampilan
**Produk:** ParkirKu
**Stack:** Laravel 12, Blade, Livewire 4 (manual), Tailwind CSS 4, PostgreSQL
**Versi:** 0.1
**Tanggal:** 4 November 2026

---

## 1. Arah Desain

ParkirKu dipakai di dua konteks yang berbeda:

1. **Gerbang parkir** — petugas berdiri, kadang memakai tablet/HP, butuh tombol besar dan alur cepat.
2. **Kantor** — admin duduk di desktop, butuh tabel, filter, dan laporan yang mudah dibaca.

Arah visual: **utilitarian modern**. Bersih, kontras tinggi, tidak banyak hiasan. Terasa seperti alat kerja yang cepat, bukan landing page.

Kata kunci:
- cepat,
- jelas,
- tenang,
- terpercaya,
- tidak ramai.

Yang dihindari:
- gradient dekoratif,
- glassmorphism,
- animasi berlebihan,
- ilustrasi besar yang tidak membantu kerja,
- card di dalam card di dalam card,
- warna status yang saling bertabrakan.

---

## 2. Prinsip Desain

1. **Satu layar, satu tugas.** Check-in dan checkout tidak dicampur dalam satu halaman.
2. **Nomor polisi adalah bintang.** Plat nomor selalu jadi elemen paling mudah dicari dan dibaca.
3. **Aksi utama selalu terlihat.** Tombol Catat Masuk dan Konfirmasi Bayar tidak boleh tersembunyi.
4. **Status harus terbaca tanpa membaca.** Warna, badge, dan label harus konsisten.
5. **Mobile tetap operasional.** Tabel berubah menjadi kartu, bukan sekadar diperkecil.
6. **Sedikit klik.** Cari kendaraan, pilih, simpan. Cari tiket, bayar, selesai.
7. **Tidak mengandalkan warna saja.** Badge selalu punya teks.

---

## 3. Struktur Navigasi

### Desktop

Sidebar kiri tetap, lebar 240px.

```
Dashboard
Parkir Aktif
Check-in
Kendaraan
Tarif          (admin)
Laporan        (admin)
```

Bagian bawah sidebar:
- nama user,
- role,
- tombol logout.

### Mobile/tablet

- Topbar tipis berisi logo dan menu.
- Bottom navigation 4 item utama:
  - Dashboard
  - Aktif
  - Check-in
  - Kendaraan
- Menu “Lainnya” untuk Tarif, Laporan, dan Logout.

### Route Web

| Route | Halaman | Akses |
|---|---|---|
| `/login` | Login | Guest |
| `/dashboard` | Dashboard | Auth |
| `/parking/check-in` | Check-in | Auth |
| `/parking/active` | Sesi aktif | Auth |
| `/parking/{session}/checkout` | Checkout | Auth |
| `/vehicles` | CRUD kendaraan | Auth |
| `/vehicle-types` | CRUD tarif | Admin |
| `/reports` | Laporan | Admin |

---

## 4. Layout System

### Grid

- Konten maksimal: `1200px`.
- Padding halaman: 16px mobile, 24px tablet, 32px desktop.
- Jarak antar section: 24px mobile, 32px desktop.
- Form: lebar maksimal 560px untuk check-in; checkout memakai dua kolom di desktop.
- Tabel: full width di dalam card.

### Struktur halaman

```
App shell
├── Sidebar / bottom nav
├── Topbar
│   ├── breadcrumb / judul
│   └── aksi utama
└── Main content
    ├── page header
    ├── summary / filter
    └── content card
```

### Breakpoint

| Nama | Lebar | Perilaku |
|---|---|---|
| `sm` | 640px | form mulai dua kolom |
| `md` | 768px | tabel sederhana |
| `lg` | 1024px | sidebar tampil |
| `xl` | 1280px | konten lega, tabel penuh |

---

## 5. Design Tokens

Tailwind CSS 4 memakai konfigurasi CSS-first di `resources/css/app.css`. Tidak perlu `tailwind.config.js`.

```css
@import "tailwindcss";

@theme {
  --font-sans: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
  --font-mono: "JetBrains Mono", ui-monospace, SFMono-Regular, monospace;

  --color-ink: #111827;
  --color-ink-soft: #334155;
  --color-muted: #64748b;
  --color-line: #e2e8f0;
  --color-surface: #f8fafc;
  --color-card: #ffffff;

  --color-primary: #0f766e;
  --color-primary-strong: #115e59;
  --color-primary-soft: #ccfbf1;

  --color-accent: #f59e0b;
  --color-accent-soft: #fef3c7;

  --color-success: #15803d;
  --color-success-soft: #dcfce7;

  --color-danger: #b91c1c;
  --color-danger-soft: #fee2e2;

  --radius-card: 0.75rem;
  --radius-control: 0.5rem;
}
```

### Palet

| Token | Hex | Pemakaian |
|---|---|---|
| Ink | `#111827` | teks utama, sidebar |
| Primary | `#0F766E` | tombol utama, link aktif |
| Accent | `#F59E0B` | aksen tiket/plat, highlight |
| Success | `#15803D` | status selesai, pembayaran sukses |
| Danger | `#B91C1C` | error, hapus, sesi bermasalah |
| Surface | `#F8FAFC` | latar halaman |
| Card | `#FFFFFF` | kartu dan tabel |
| Muted | `#64748B` | teks sekunder |

Aturan kontras:
- Teks utama di atas putih memakai `ink`.
- Teks di atas primary memakai putih.
- Accent tidak dipakai sebagai warna teks kecil di atas putih; pakai sebagai latar badge dengan teks `ink`.
- Jangan memakai warna saja untuk menandai status; selalu sertakan teks.

### Tipografi

| Peran | Font | Ukuran |
|---|---|---|
| Judul halaman | Instrument Sans | 24–30px, 600 |
| Judul card | Instrument Sans | 16–18px, 600 |
| Body | Instrument Sans | 14–16px, 400 |
| Label form | Instrument Sans | 13–14px, 500 |
| Plat nomor | JetBrains Mono | 16–20px, 600, tracking lebar |
| Kode tiket | JetBrains Mono | 13–14px, 500 |
| Angka statistik | Instrument Sans | 28–36px, 650 |

Aturan:
- Maksimal dua family.
- Tidak memakai font dekoratif.
- Angka uang memakai `tabular-nums`.
- Plat nomor selalu monospace dan uppercase.

### Spacing

Skala 4px:
`4, 8, 12, 16, 20, 24, 32, 40, 48`.

### Radius dan Shadow

- Card: 12px.
- Input/button: 8px.
- Badge: full rounded.
- Shadow card: sangat tipis, lebih mengandalkan border.
- Tidak memakai shadow besar.

---

## 6. Komponen UI

### Blade Components

| Komponen | Fungsi |
|---|---|
| `x-ui.button` | tombol primary/secondary/danger/ghost |
| `x-ui.input` | input dengan label, error, hint |
| `x-ui.select` | select dengan label dan error |
| `x-ui.card` | container konten |
| `x-ui.badge` | status |
| `x-ui.table` | wrapper tabel responsif |
| `x-ui.empty-state` | keadaan kosong |
| `x-ui.alert` | pesan sukses/error |
| `x-ui.stat-card` | kartu statistik dashboard |
| `x-ui.confirm-dialog` | konfirmasi hapus/checkout |
| `x-ui.plate` | tampilan plat nomor monospace |
| `x-ui.ticket-code` | kode tiket monospace |

### Livewire Components

| Komponen | Fungsi |
|---|---|
| `vehicle-search` | pencarian kendaraan dengan debounce |
| `check-in-form` | form check-in |
| `active-sessions-table` | daftar sesi aktif + search + pagination |
| `checkout-panel` | ringkasan biaya, input uang, kembalian |
| `vehicle-form` | create/edit kendaraan via modal atau halaman |
| `vehicle-type-form` | create/edit tarif |
| `report-filter` | filter tanggal laporan |
| `toast` | notifikasi ringan |

Prinsip Livewire:
- Gunakan `wire:model.live.debounce.300ms` untuk search.
- Gunakan `wire:loading` dan `wire:target` untuk state.
- Gunakan `wire:key` pada list.
- Jangan memindahkan seluruh CRUD ke Livewire; controller tetap dipakai untuk halaman resource.
- Form checkout memakai satu komponen Livewire agar perhitungan kembalian real-time.

---

## 7. Halaman dan Wireframe

### 7.1 Login

Tujuan: masuk cepat, tidak mengalihkan perhatian.

```
┌──────────────────────────────────────────────┐
│                                              │
│   PARKIRKU                    [ panel info ] │
│   Sistem Parkir Kendaraan                    │
│                                              │
│   ┌────────────────────────────┐             │
│   │ Masuk                      │             │
│   │ Email                      │             │
│   │ [________________________] │             │
│   │ Password                   │             │
│   │ [________________________] │             │
│   │ [        Masuk         ]   │             │
│   └────────────────────────────┘             │
│                                              │
└──────────────────────────────────────────────┘
```

Desktop: dua kolom. Kiri brand + satu kalimat manfaat. Kanan card login.
Mobile: satu kolom, card penuh lebar.

Detail:
- Label jelas, bukan placeholder saja.
- Error validasi di bawah field.
- Tombol primary full width.
- Tidak ada link registrasi (akun dibuat admin/seeder).
- Fokus otomatis ke email.

### 7.2 Dashboard

```
┌ Sidebar ┬──────────────────────────────────────────────┐
│         │ Dashboard                         [+ Check-in]│
│         │ Ringkasan operasional hari ini                │
│         │ ┌──────────┐ ┌──────────┐ ┌──────────┐       │
│         │ │ Parkir   │ │ Transaksi│ │Pendapatan│       │
│         │ │ 12       │ │ 34       │ │ Rp 102k  │       │
│         │ └──────────┘ └──────────┘ └──────────┘       │
│         │                                               │
│         │ Sesi Aktif Terbaru                            │
│         │ ┌───────────────────────────────────────────┐ │
│         │ │ Tiket  Plat      Jenis  Masuk   Aksi     │ │
│         │ │ PKR... B 1234..  Mobil  08:12   Checkout │ │
│         │ └───────────────────────────────────────────┘ │
└─────────┴───────────────────────────────────────────────┘
```

Komponen:
- 3 stat card: kendaraan parkir, transaksi hari ini, pendapatan hari ini.
- Tabel 5 sesi aktif terbaru.
- Tombol utama: Check-in.
- Link “Lihat semua” ke Parkir Aktif.

State:
- API gagal: tampilkan alert ringan “Statistik belum bisa dimuat” dan angka `0`.
- Tidak ada sesi: empty state dengan tombol Check-in.

### 7.3 Check-in

Tujuan: selesai di bawah 30 detik.

Desktop: dua kolom.
- Kiri: form utama.
- Kanan: panduan singkat dan 5 kendaraan terakhir.

```
Check-in Kendaraan
Cari kendaraan atau daftarkan baru.

┌─────────────────────────────────────┐
│ Nomor polisi                        │
│ [ B 1234 XYZ                  ] [Cari]│
│                                     │
│ Kendaraan ditemukan                 │
│ ┌─────────────────────────────────┐ │
│ │ B 1234 XYZ · Mobil · Budi      │ │
│ └─────────────────────────────────┘ │
│                                     │
│ Tarif per jam: Rp 3.000             │
│                                     │
│ [        Catat Masuk            ]   │
└─────────────────────────────────────┘
```

Detail:
- Input plat besar, monospace, uppercase otomatis.
- Enter memicu pencarian.
- Jika tidak ditemukan: tampil tombol “Daftarkan kendaraan ini”.
- Jika kendaraan sudah parkir: tampil badge merah “Masih parkir” dan tombol checkout.
- Setelah sukses: tampil panel tiket dengan kode besar, tombol “Cetak/lihat tiket” (opsional), dan “Check-in lagi”.

Mobile:
- Form satu kolom.
- Tombol aksi sticky di bawah.
- Input plat full width, font 18px.

### 7.4 Parkir Aktif

```
Parkir Aktif
[ Cari tiket / plat / pemilik ]        [Check-in]

┌────────────────────────────────────────────────────┐
│ Tiket        Plat        Jenis  Masuk   Durasi Aksi│
│ PKR-...      B 1234 XYZ  Mobil  08:12   1j 12m [Checkout]│
└────────────────────────────────────────────────────┘
```

Desktop: tabel.
Mobile: card list.

Card mobile:
```
┌────────────────────────────────────┐
│ B 1234 XYZ             [1j 12m]    │
│ Mobil · Budi                       │
│ PKR-20261104-0012                  │
│ [ Checkout ]                       │
└────────────────────────────────────┘
```

Detail:
- Badge durasi berjalan.
- Tombol checkout selalu terlihat.
- Search debounce.
- Pagination 10.
- Empty state: “Belum ada kendaraan parkir.”

### 7.5 Checkout

Tujuan: menghitung biaya dan menyelesaikan pembayaran tanpa ragu.

Desktop: dua kolom.
- Kiri: ringkasan sesi.
- Kanan: pembayaran.

```
┌───────────────────────────────┬──────────────────────────────┐
│ B 1234 XYZ                    │ Pembayaran                    │
│ Mobil · Budi                  │ Total tagihan                 │
│ Tiket PKR-...                 │ Rp 9.000                      │
│                               │                               │
│ Masuk   08:12                 │ Uang diterima                 │
│ Keluar  10:27                 │ [ Rp 10.000            ]      │
│ Durasi  2 jam 15 menit        │                               │
│ Tarif   Rp 3.000/jam          │ Kembalian                     │
│                               │ Rp 1.000                      │
│                               │                               │
│                               │ [ Konfirmasi Bayar ]          │
└───────────────────────────────┴──────────────────────────────┘
```

Detail:
- Total biaya paling menonjol.
- Uang diterima memakai input angka dengan format ribuan.
- Kembalian real-time, merah jika kurang.
- Tombol konfirmasi disabled jika uang kurang.
- Konfirmasi kedua sebelum menyimpan.
- Setelah sukses: halaman sukses dengan ringkasan dan tombol “Checkout lagi” / “Kembali ke aktif”.

Mobile:
- Ringkasan di atas, pembayaran di bawah.
- Tombol sticky di bawah.

### 7.6 Kendaraan

Desktop: tabel + search + tombol tambah.
Kolom: plat, jenis, pemilik, merek/warna, aksi.

Mobile: card.

Form:
- Plat nomor.
- Jenis kendaraan.
- Nama pemilik (opsional).
- Merek (opsional).
- Warna (opsional).

Validasi:
- Plat wajib, unik, maksimal 20 karakter.
- Jenis wajib.
- Nama pemilik maksimal 100.

### 7.7 Tarif

Hanya admin.

Tabel: nama jenis, tarif per jam, jumlah kendaraan, aksi.

Form:
- Nama jenis.
- Tarif per jam (input angka, prefix Rp).

Delete:
- Disabled jika sudah dipakai.
- Tooltip/alasan jelas.

### 7.8 Laporan

```
Laporan Parkir
[ Dari: 2026-11-01 ] [ Sampai: 2026-11-04 ] [ Tampilkan ]

┌───────────────┐ ┌───────────────┐
│ Transaksi     │ │ Pendapatan    │
│ 34            │ │ Rp 102.000    │
└───────────────┘ └───────────────┘

┌──────────────────────────────────────────────────────┐
│ Tiket  Plat       Jenis  Masuk  Keluar  Durasi  Total│
└──────────────────────────────────────────────────────┘
```

Detail:
- Default hari ini.
- Filter validasi: tanggal mulai <= tanggal akhir.
- Tombol cetak opsional.
- Empty state jika tidak ada transaksi.
- Error API ditampilkan sebagai alert, bukan halaman kosong.

---

## 8. State dan Feedback

### Loading

- Tombol submit: spinner kecil + teks “Memproses…”.
- Tabel: skeleton baris.
- Search: indikator halus di kanan input.
- Livewire: `wire:loading` pada target yang tepat.

### Empty

- Dashboard: “Belum ada kendaraan parkir.”
- Kendaraan: “Belum ada kendaraan terdaftar.”
- Laporan: “Tidak ada transaksi pada rentang tanggal ini.”
- Selalu ada aksi berikutnya.

### Error

- Validasi: teks merah di bawah field, `aria-describedby`.
- Gagal API: alert kuning/merah di atas konten.
- Gagal checkout: pesan jelas, data tidak hilang.
- 404: halaman sederhana dengan tombol kembali.

### Sukses

- Toast singkat: “Kendaraan berhasil dicatat masuk.”
- Checkout: halaman/panel sukses dengan total dan kembalian.
- Jangan memakai alert browser bawaan.

---

## 9. Interaksi dan Motion

- Transisi hover: 150ms.
- Transisi panel/modal: 180–220ms.
- Tidak ada animasi looping.
- Hormati `prefers-reduced-motion`.
- Fokus keyboard terlihat jelas.
- Modal: fokus masuk ke field pertama, Esc menutup, klik backdrop menutup dengan konfirmasi jika ada perubahan.
- Toast muncul 3–5 detik, bisa ditutup.

---

## 10. Responsif

### Mobile-first

- Form satu kolom.
- Tombol aksi full width atau sticky.
- Tabel berubah menjadi card.
- Sidebar menjadi bottom nav.
- Angka statistik tetap terbaca.
- Tidak ada horizontal scroll yang tidak perlu.

### Tablet

- Dua kolom untuk check-in/checkout.
- Sidebar bisa collapse.
- Tabel mulai tampil.

### Desktop

- Sidebar tetap.
- Konten lega.
- Tabel penuh.
- Aksi utama di kanan atas.

---

## 11. Aksesibilitas

- Semua input punya `<label>`.
- Error terhubung dengan `aria-describedby`.
- Tombol ikon punya `aria-label`.
- Kontras teks minimal 4.5:1.
- Target sentuh minimal 44x44px.
- Fokus tidak dihapus tanpa pengganti.
- Status tidak hanya warna.
- Tabel punya `<caption>` atau heading yang jelas.
- Gunakan `scope="col"` pada header tabel.
- Dialog memakai `role="dialog"` dan `aria-modal="true"`.
- Bahasa halaman `lang="id"`.

---

## 12. Implementasi Tailwind CSS 4

### File CSS

`resources/css/app.css`:

```css
@import "tailwindcss";

@theme {
  --font-sans: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
  --font-mono: "JetBrains Mono", ui-monospace, SFMono-Regular, monospace;
  --color-ink: #111827;
  --color-primary: #0f766e;
  --color-primary-strong: #115e59;
  --color-accent: #f59e0b;
  --color-success: #15803d;
  --color-danger: #b91c1c;
  --color-surface: #f8fafc;
  --radius-card: 0.75rem;
  --radius-control: 0.5rem;
}

@layer base {
  html {
    color-scheme: light;
  }

  body {
    @apply bg-surface text-ink antialiased;
  }
}
```

### Aturan pemakaian

- Gunakan token (`bg-primary`, `text-ink`, `border-line`) bukan hex acak di view.
- Jangan membuat `tailwind.config.js`; Tailwind 4 memakai CSS-first.
- Utility class panjang dipecah menjadi Blade component.
- Dark mode tidak masuk MVP.

### Font

- `Instrument Sans` untuk UI.
- `JetBrains Mono` untuk plat dan kode tiket.
- Muat lewat `@fontsource` atau font lokal. Sediakan fallback system font.
- Jangan memakai font dari CDN yang tidak stabil saat demo offline.

---

## 13. Struktur Folder Tampilan

```
resources/
├── css/
│   └── app.css
├── js/
│   └── app.js
└── views/
    ├── layouts/
    │   ├── app.blade.php
    │   └── guest.blade.php
    ├── partials/
    │   ├── navbar.blade.php
    │   ├── sidebar.blade.php
    │   ├── alert.blade.php
    │   └── pagination.blade.php
    ├── components/
    │   └── ui/
    │       ├── button.blade.php
    │       ├── input.blade.php
    │       ├── select.blade.php
    │       ├── card.blade.php
    │       ├── badge.blade.php
    │       ├── table.blade.php
    │       ├── empty-state.blade.php
    │       └── stat-card.blade.php
    ├── auth/
    │   └── login.blade.php
    ├── dashboard.blade.php
    ├── parking/
    │   ├── check-in.blade.php
    │   ├── active.blade.php
    │   ├── checkout.blade.php
    │   └── success.blade.php
    ├── vehicles/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   └── show.blade.php
    ├── vehicle-types/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   └── edit.blade.php
    └── reports/
        └── index.blade.php
```

---

## 14. Arsitektur Tampilan dan Data

### Pola umum

- Halaman resource: Controller + Form Request + Blade.
- Interaksi dinamis: Livewire component.
- API: Controller di `app/Http/Controllers/Api/` + API Resource.
- Dashboard dan laporan: Controller memanggil API internal lewat `Http::get()`.

### Alur dashboard

```
Browser → DashboardController → Http::get(INTERNAL_API_URL/api/stats)
        → Api\StatsController → PostgreSQL
        → JSON → DashboardController → Blade dashboard
```

### Alur laporan

```
Browser → ReportController → Http::get(INTERNAL_API_URL/api/parking-sessions?...)
        → Api\ParkingSessionController → PostgreSQL
        → JSON → ReportController → Blade laporan
```

### Dua server development

```bash
# server web
php artisan serve --port=8000

# server API internal
php artisan serve --port=8011
```

`.env`:

```env
INTERNAL_API_URL=http://127.0.0.1:8011
```

Alasan: `php artisan serve` single-threaded. Tanpa dua server, request dashboard yang memanggil API ke port yang sama akan deadlock.

### Batas Livewire

Gunakan Livewire untuk:
- pencarian,
- perhitungan kembalian,
- modal form,
- notifikasi,
- update status tanpa reload.

Jangan gunakan Livewire untuk:
- menggantikan semua resource controller,
- query kompleks yang seharusnya di Model/Service,
- seluruh halaman CRUD jika tidak perlu.

---

## 15. Penulisan Konten

Gaya bahasa:
- Indonesia,
- singkat,
- imperatif untuk aksi,
- tidak teknis untuk pengguna.

Contoh:
- “Catat Masuk” bukan “Submit”.
- “Uang diterima” bukan “Paid amount”.
- “Kendaraan masih parkir” bukan “Conflict”.
- “Tidak ada kendaraan parkir” bukan “Data not found”.

Format:
- Uang: `Rp 9.000` (titik ribuan).
- Tanggal: `4 Nov 2026`.
- Waktu: `10:27`.
- Durasi: `2 jam 15 menit`.
- Plat: `B 1234 XYZ`.
- Tiket: `PKR-20261104-0012`.

---

## 16. Checklist QA Visual

- [ ] Login tampil baik di 360px, 768px, 1280px.
- [ ] Tidak ada horizontal scroll di mobile.
- [ ] Tombol utama terlihat tanpa scroll berlebihan.
- [ ] Form check-in selesai < 30 detik.
- [ ] Checkout menampilkan total dan kembalian dengan benar.
- [ ] Tabel berubah menjadi card di mobile.
- [ ] Empty state punya aksi berikutnya.
- [ ] Error validasi terbaca dan terhubung ke field.
- [ ] Status punya teks, bukan hanya warna.
- [ ] Fokus keyboard terlihat.
- [ ] Kontras teks memenuhi 4.5:1.
- [ ] Modal bisa ditutup dengan Esc.
- [ ] `wire:loading` muncul saat proses.
- [ ] Data uang memakai `tabular-nums`.
- [ ] Plat dan tiket memakai monospace.
- [ ] Halaman laporan tidak crash saat API mati.
- [ ] Dua server berjalan saat demo dashboard/laporan.

---

## 17. Keputusan Desain

| Keputusan | Alasan |
|---|---|
| Light theme saja | Mengurangi scope; area gerbang biasanya terang |
| Sidebar desktop + bottom nav mobile | Navigasi cepat di dua konteks |
| Plat monospace besar | Nomor polisi adalah data utama |
| Warna primary teal | Tenang, terbaca, tidak generik biru |
| Accent amber | Menandai tiket/plat tanpa mendominasi |
| Tanpa chart | MVP cukup angka dan tabel; chart tidak menambah nilai demo |
| Tanpa shadcn | shadcn khusus React; stack Blade tidak memakai React |
| Livewire terbatas | Menjaga materi controller/Form Request tetap terpakai |
| PostgreSQL | Sesuai keputusan project; gunakan `pdo_pgsql` |
