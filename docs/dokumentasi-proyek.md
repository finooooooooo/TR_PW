# Dokumentasi Proyek Simkos G4

## Identitas dan pembagian tugas kelompok

**Tema:** Sistem Informasi Pengelolaan Rumah Kos (SIMKOS G4)

1. **Fino Rivalen (672025163)** — instalasi dan konfigurasi Laravel 12, migration dan seeder, autentikasi login/logout serta middleware, dan master layout Bootstrap responsif. Bagian utama: `database/migrations/`, `DatabaseSeeder.php`, `AuthController.php`, `routes/web.php`, `layouts/app.blade.php`, dan `auth/login.blade.php`.
2. **Archyeza Gaciyama Feodora (672025174)** — CRUD kamar, status tersedia/terisi, validasi nomor kamar unik, dan perlindungan penghapusan kamar yang memiliki masa sewa. Bagian utama: `Room.php`, `RoomController.php`, dan `resources/views/rooms/`.
3. **Mataya Oktaviano (672025012)** — CRUD dan pencarian penghuni, validasi NIK 16 digit yang unik, dan perlindungan penghapusan penghuni yang memiliki masa sewa. Bagian utama: `Tenant.php`, `TenantController.php`, dan `resources/views/tenants/`.
4. **Titan Brata (672025014)** — masa sewa dan pembayaran manual, relasi kamar–penghuni–sewa–pembayaran, serta sinkronisasi otomatis status kamar. Bagian utama: `Lease.php`, `Payment.php`, `LeaseController.php`, `PaymentController.php`, dan folder `resources/views/leases/` serta `resources/views/payments/`.
5. **Cornelius Alviko (672025226)** — dashboard, laporan PDF penghuni dan pembayaran dengan filter, pengujian alur aplikasi, dokumentasi, dan pengemasan berkas. Bagian utama: `DashboardController.php`, `ReportController.php`, dan folder `resources/views/dashboard/` serta `resources/views/reports/`.

## Deskripsi aplikasi

Simkos G4 adalah aplikasi web untuk satu lokasi rumah kos. Aplikasi membantu pengelola memelihara data kamar dan penghuni, mencatat masa sewa aktif atau selesai, mencatat pembayaran manual, serta melihat ringkasan operasional dan mengunduh laporan PDF.

## Fitur

- Login dan logout untuk akun pengelola.
- Tambah, lihat, ubah, dan hapus kamar maupun penghuni.
- Status kamar tersedia/terisi diperbarui berdasarkan masa sewa aktif.
- Pencatatan, perubahan, dan penutupan masa sewa.
- Pencatatan pembayaran secara manual, termasuk periode, nominal, metode pencatatan, dan status.
- Dashboard ringkasan kamar, penghuni, pembayaran, dan masa sewa yang segera berakhir.
- Laporan penghuni dan pembayaran dalam format PDF.

Tidak ada pemrosesan pembayaran online, integrasi bank, kontrak elektronik, perhitungan listrik, agen, atau pengelolaan banyak lokasi.

## Relasi data

- Satu kamar memiliki banyak riwayat masa sewa.
- Satu penghuni memiliki banyak riwayat masa sewa.
- Satu masa sewa memiliki banyak catatan pembayaran.
- Kamar dan penghuni yang sudah memiliki riwayat sewa tidak dapat dihapus agar data historis tetap tersimpan.

## Menjalankan aplikasi

Ikuti langkah pada [README proyek](../README.md): siapkan MySQL/MariaDB, salin `.env.example` ke `.env`, pasang dependensi Composer dan npm, buat application key, lalu jalankan migration dan seeder.

Akun lokal dari seeder: `admin@simkos.test` / `password`. Ganti kata sandi sebelum penggunaan nyata.

## Pengujian dan bukti pengumpulan

Jalankan `php artisan test` setelah PHP CLI mengaktifkan ekstensi `pdo_sqlite` dan `sqlite3`. Tes mencakup alur login, kamar, penghuni, masa sewa, pembayaran, validasi, dan laporan PDF. Jika SQLite tidak tersedia, aktifkan kedua ekstensi tersebut pada `php.ini` CLI.

Screenshot demonstrasi dengan data contoh tersedia di:

- [Dashboard](screenshots/dashboard.png)
- [Kamar](screenshots/rooms.png)
- [Penghuni dan pencarian](screenshots/tenants.png)
- [Pembayaran](screenshots/payments.png)
- [Laporan](screenshots/reports.png)

Sebelum dikumpulkan, kelompok tetap perlu menguji alur pada lingkungan MySQL/MariaDB dan melampirkan bukti pengujian/demo yang diwajibkan dosen.
