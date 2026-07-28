# ERMIS BPOM — Fondasi Aplikasi (Fase 1)

Fondasi Laravel 11 untuk Enterprise Risk Management Information System BPOM, mencakup:
**Auth (custom `sys_users`) + Three Lines Model + Konteks Organisasi + Identifikasi Risiko + Analisis Risiko**,
lengkap dengan state machine approval 3 lini, `RiskEngineService` (lookup matriks risiko), dan frontend Blade + Tailwind.

## ⚠️ Catatan Penting Sebelum Mulai

1. **Sandbox pembuatan kode ini tidak punya akses ke Packagist**, jadi saya tidak bisa menjalankan
   `composer install` untuk memvalidasi proyek end-to-end. Semua kode ditulis manual mengikuti konvensi
   Laravel 11 — ikuti langkah instalasi di bawah untuk menyatukannya dengan skeleton resmi Laravel.
2. ✅ **`ref_matriks_risikos` dan `ref_level_risikos` SUDAH memakai data RESMI**, diambil langsung dari
   Kepka BPOM Lampiran I — Tabel Peta Risiko Inheren (BAB V.D.7) dan Tabel Selera Risiko BPOM
   (BAB III.D.2.h.5). 25 nilai matriks sudah diverifikasi unik mencakup 1–25 persis. `wajib_pengujian_pengendalian`
   bernilai `true` untuk label Tinggi (16-19) & Sangat Tinggi (20-25) sesuai BAB VII.B. Tetap disarankan
   satu putaran QA silang oleh 2 orang sebelum go-live (sesuai kebiasaan baik pengelolaan data acuan).
3. RBAC ditulis manual via tabel `sys_roles`/`sys_user_roles` + Laravel Policy/Gate — **tidak** memakai
   Spatie Permission, supaya skema persis mengikuti dokumen perencanaan Bapak.

## Cakupan (yang sudah jadi)

| Modul | Migration | Model | Service | Controller | View |
|---|---|---|---|---|---|
| Auth & Three Lines Model | ✅ | ✅ | — | ✅ (Login) | ✅ |
| Konteks Organisasi | ✅ | ✅ | `WorkflowService` | ✅ | ✅ |
| Identifikasi Risiko | ✅ | ✅ | `WorkflowService` | ✅ | ✅ |
| Analisis Risiko | ✅ | ✅ | `RiskEngineService` | ✅ | ✅ (+ preview AJAX) |
| RTP (Rencana Tindak Pengendalian) | ✅ | ✅ | `WorkflowService`, `RiskEngineService` | ✅ | ✅ |
| Pemantauan / Reviu (Anak Lampiran I.c) | ✅ | ✅ | — | ✅ | ✅ |
| Knowledge Base (Glosarium, Kriteria, Kaidah K01-K08, Metode Penilaian) | ✅ | ✅ | `PernyataanRisikoValidationService` | ✅ | ✅ |
| Dokumen Dukung Analisis (past record/pengalaman/literatur) | ✅ | ✅ | — | ✅ | ✅ (di halaman edit Analisis) |

**Belum dibangun** (menunggu instruksi Bapak modul mana selanjutnya): Laporan Manajemen Risiko Semester
(Anak Lampiran I.d), Modul Fraud (Anak Lampiran I.e), LED (Anak Lampiran I.f), Maturitas (Lampiran II),
Pengujian Aktivitas Pengendalian (PAP-1/PAP-2), dan Modul Ekspor 8 Anak Lampiran (XLSX/DOCX/PDF).

## Langkah Instalasi

```bash
# 1. Buat proyek Laravel 11 resmi (butuh akses internet ke Packagist)
composer create-project laravel/laravel ermis-bpom
cd ermis-bpom

# 2. Timpakan seluruh isi folder hasil Claude ini ke atas proyek barusan
#    (app/, config/auth.php, database/, resources/, routes/, bootstrap/app.php,
#     composer.json, package.json, tailwind.config.js, vite.config.js, postcss.config.js, .env.example)
#    -- backup dulu file bawaan yang tertimpa (mis. app/Providers/AppServiceProvider.php lama).

# 3. Install ulang dependency (memastikan composer.json baru terbaca)
composer install
npm install

# 4. Siapkan .env
cp .env.example .env
php artisan key:generate
# Sesuaikan DB_* dengan MySQL/PostgreSQL Bapak, buat database "ermis_bpom" dulu

# 5. Migrasi & seed data referensi + akun demo
php artisan migrate --seed

# 6. Build asset frontend
npm run build
# atau saat development: npm run dev (di terminal terpisah)

# 7. Jalankan
php artisan serve
```

Akun demo (password semua: `password`):
- `pengelola@lokapom-kotbar.test` — Lini 1, Pengelola Risiko (buat draft)
- `kepala@lokapom-kotbar.test` — Lini 1, Kepala UPR (approve final + TTD digital)
- `spi@lokapom-kotbar.test` — Lini 2, Ketua Tim SPI (reviu)
- `auditor@lokapom-kotbar.test` — Lini 2, Auditor Internal (reviu)

## Alur Uji Coba Cepat

1. Login sebagai **Pengelola Risiko** → buat **Konteks Organisasi** → "Ajukan Reviu ke Lini 2".
2. Login sebagai **Ketua Tim SPI** → buka Konteks Organisasi tsb → **Setujui Reviu Lini 2**.
3. Login sebagai **Kepala UPR** → **Setujui & TTD Digital** (isi kunci TTD bebas untuk demo).
4. Login sebagai **Pengelola Risiko** → sekarang bisa membuat **Identifikasi Risiko** (konteks harus
   berstatus "Disetujui Kepala UPT" dulu) → ulangi alur reviu 3 lini yang sama.
5. Dari halaman detail Identifikasi Risiko → **+ Tambah Analisis** → isi level kemungkinan/dampak inheren,
   badge level risiko akan muncul otomatis (dihitung live via `RiskEngineService`, bukan rumus kali).
6. Jika level risiko residual/inheren **> 15**, tautan **"+ Wajib RTP"** akan muncul di baris analisis
   tersebut (sesuai BAB VI.D.1.b: "risiko dengan nilai level risiko lebih dari 15 menjadi prioritas
   untuk ditangani"). Buat RTP → ulangi alur reviu 3 lini yang sama (Ajukan Reviu → Reviu Lini 2 →
   Approve Kepala UPT).
7. Setelah RTP **Disetujui Kepala UPT**, tombol **"+ Catat Progress"** muncul di halaman detail RTP untuk
   mencatat Pemantauan/Reviu berkala (Anak Lampiran I.c) — progress %, due date, dan simpulan efektivitas
   pengendalian.
8. Sidebar **Knowledge Base** berisi Glosarium (40 istilah, bisa dicari), Kriteria Kemungkinan & Dampak
   (tabel resmi berjenjang per UPT/Manajemen Puncak/dst), dan Kaidah Pernyataan Risiko (K01-K08).
9. Saat mengisi **Pernyataan Risiko** di form Identifikasi Risiko, validasi berjalan otomatis (debounce
   600ms) memanggil `PernyataanRisikoValidationService` dan menampilkan peringatan K01/K03/K07 langsung
   di bawah textarea — sifatnya *advisory*, tidak memblokir submit (`tingkat_pelanggaran` semua diset
   `ALERT_PERINGATAN`; bisa diubah ke `ALERT_BLOKIR` per baris di `ref_kaidah_pernyataan_risikos` bila
   Bapak ingin menegakkannya lebih ketat).
10. Di halaman **edit Analisis Risiko**, ada bagian "Dokumen Pendukung Analisis" untuk mengunggah past
    record/pengalaman/literatur yang mendasari penentuan level kemungkinan/dampak.

## Keputusan Desain Penting

- **`sys_users` pakai UUID sebagai primary key** (bukan `id` bigint bawaan Laravel), karena seluruh FK
  `created_by`/`approved_by`/dst di skema asli memakai `foreignUuid(...)->constrained('sys_users')`.
  `config/auth.php` sudah diarahkan ke provider `sys_users` + model `App\Models\SysUser`.
- **`kode_risiko` unik per `(upt_id, tahun_anggaran)`**, bukan unik nasional — sesuai Catatan Implementasi
  Revisi 2 poin 2. Dicek dengan `lockForUpdate()` di `IdentifikasiRisikoController::store()` untuk mencegah
  race condition saat banyak UPT input bersamaan.
- **`WorkflowService` digeneralisasi** untuk `TrnKonteksOrganisasi`, `TrnIdentifikasiRisiko`, dan
  `TrnRencanaTindakPengendalian` sekaligus, karena ketiganya memakai state machine 3 lini yang identik
  persis di dokumen perencanaan.
- **Frontend Blade + Tailwind (bukan Inertia/Vue)** sesuai pilihan Bapak — bisa dimigrasikan ke Inertia
  nanti tanpa mengubah struktur backend (Controller sudah memisahkan logic dari presentasi).
- **RTP wajib disusun untuk risiko level residual (atau inheren bila residual belum diisi) di atas 15**,
  dicek di `RtpPolicy::bolehBuatRtp()` — sesuai BAB VI.D.1.b Kepka. RTP memakai `WorkflowService` yang
  sama, hanya beda satu kolom (`signature_hash` tunggal, bukan `_lini1`/`_lini2` terpisah) — sudah
  ditangani via `instanceof` check di dalam service.
- **Pemantauan/Reviu hanya bisa dicatat setelah RTP berstatus Disetujui Kepala UPT** (`RtpPolicy::catatPemantauan()`).
- **K01-K08 dipetakan langsung dari BAB IV.D.2 huruf a-h** Kepka (bukan istilah resmi Kepka — Kepka
  hanya menulis "huruf a-h", penomoran K01-K08 murni konvensi internal ERMIS). K02/K04/K05/K06/K08
  bersifat structural/manual judgment (sebagian sudah dijamin lewat unique key & foreign key), sedangkan
  K01/K03/K07 divalidasi otomatis lewat `PernyataanRisikoValidationService`.
- **Data kriteria kemungkinan & dampak (BAB V.D.5-D.6) sudah memakai angka resmi** dari dokumen Kepka —
  termasuk seluruh ambang batas rupiah, persentase, IPKP, dan masa pidana per tingkatan UPR.

## Langkah Selanjutnya

Beri tahu saya modul mana yang mau dilanjutkan berikutnya, misalnya:
- **Laporan Manajemen Risiko Semester** (Anak Lampiran I.d) + reviu Lini 2 atasnya
- **Modul Ekspor** (XLSX/DOCX/PDF untuk 8 Anak Lampiran)
- **Modul Fraud, LED, Maturitas, atau Pengujian Aktivitas Pengendalian (PAP)**
