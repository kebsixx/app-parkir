# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

Laravel 12 (Blade, session auth manual), Livewire 4, Tailwind CSS 4, PostgreSQL, Vite. *(tersurat dari README/DESIGN.md/codebase)*

## Users
- **Petugas gerbang/operasional (mobile/tablet, sering berdiri):** mencatat check-in, memantau parkir aktif, memproses checkout/pembayaran tunai cepat dan minim kesalahan.
- **Admin/kantor (desktop):** mengelola master kendaraan & tarif, melihat laporan/transaksi/pendapatan. *(peran & konteks tersurat dari DESIGN.md/README/routes; asumsi: satu area parkir sebagai konteks utama demo/UAS.)*

## Product Purpose
ParkirKu adalah sistem manajemen operasional parkir kendaraan digital untuk menggantikan pencatatan manual: check-in dengan kode tiket unik, pemantauan sesi aktif, checkout dengan perhitungan durasi/tarif dan kembalian, serta ringkasan/laporan. Sukses = alur gerbang selesai cepat (<30 detik target check-in per DESIGN.md), data transaksi nyata tersimpan, dan kebutuhan UAS (app baru, CRUD+relasi+auth+API+Blade konsumsi API) terpenuhi.

## Positioning
Alat kerja parkir **utilitarian modern** (bukan landing page): nomor polisi/kode tiket sebagai fokus, alur terpisah (check-in/checkout), responsif operasional (desktop sidebar + mobile bottom nav), dan integrasi Laravel end-to-end (P1–P10) termasuk konsumsi internal REST API (2 server dev, `INTERNAL_API_URL`).

## Operating Context
- **Gerbang:** HP/tablet, input cepat, tombol besar/aksi utama terlihat, kondisi terang (light theme MVP).
- **Kantor:** desktop, tabel/filter/laporan.
- **Demo/dev:** butuh 2 instance `php artisan serve` (`:8000` web, `:8011` internal API) untuk hindari deadlock P10. Akun demo: `admin@parkirku.test` / `petugas@parkirku.test` (password `password`).

## Capabilities and Constraints
- Auth manual session (tanpa starter kit), role `admin`/`petugas`, middleware proteksi (`auth`, admin-only untuk tarif/laporan).
- Master: jenis kendaraan+tarif (admin), data kendaraan (plat uppercase/unik, cari).
- Transaksi nyata: check-in tiket unik; checkout durasi dibulatkan ke atas per jam, bayar tunai+kembalian, `DB::transaction`/anti-race (`lockForUpdate`) di service.
- REST API + API Resource (`/api/vehicles`, `/api/parking-sessions`, `/api/stats`); dashboard/laporan konsumsi API internal via `Http::get()`.
- UI: Bahasa Indonesia, format uang `Rp` titik ribuan, plat/kode monospace. Light theme saja (MVP). **Belum diputuskan eksplisit oleh user interview:** fitur lanjutan di luar UAS (cetak tiket fisik, multi-lokasi, pembayaran non-tunai, dark mode, dsb.). *(sebagian poin “belum diputuskan” = asumsi konservatif dari scope MVP/DESIGN.md.)*

## Brand Commitments
- Nama produk: **ParkirKu**.
- Arah visual/UX: utilitarian modern, cepat-jelas-tenang-terpercaya; hindari dekorasi/gradient/glassmorphism berlebihan. Font: **Instrument Sans** (UI) + **JetBrains Mono** (plat/tiket) — komitmen `docs/DESIGN.md`.
- Copy imperatif ID (“Catat Masuk”, “Konfirmasi Bayar”). *(sumber: DESIGN.md/README)*

## Evidence on Hand
- `README.md`, `DEMO.md` (repo): skenario demo, setup, kredensial demo.
- `docs/DESIGN.md`: spesifikasi UI/UX/tokens/komponen/QA.
- `Projek UAS.md`, `modul-laravel-12/` (workspace): requirement & cakupan materi.
- Seeder data dummy (admin/petugas, jenis kendaraan, kendaraan, sesi). **Absensi:** tidak ada testimoni/benchmark produksi nyata/brand asset eksternal — jangan difabrikasi.

## Product Principles
1. **Kecepatan operasional:** minim langkah/klik untuk check-in & checkout.
2. **Kejelasan data kritis:** plat, status, durasi, total, kembalian harus langsung terbaca.
3. **Keandalan transaksi:** perhitungan & status konsisten; lindungi dari error/race pada pembayaran.
4. **Aksesibilitas & responsivitas kerja:** dapat dipakai di mobile gerbang dan desktop kantor; status tidak hanya warna.
5. **Kesederhanaan MVP:** fitur/dokumen sesuai UAS & nilai demo; hindari kompleksitas yang tidak diminta.

## Accessibility & Inclusion
- `lang="id"`, label/error terhubung (`aria-describedby`/`aria-invalid` di komponen form), fokus terlihat, target sentuh ≥44px pada kontrol kritis, hormati `prefers-reduced-motion`, status punya teks (bukan hanya warna). Standar target praktis: kontras teks ≥4.5:1 (WCAG AA). *(sumber: DESIGN.md + perbaikan audit; asumsi: kebutuhan inklusi spesifik tambahan belum dinyatakan user.)*
