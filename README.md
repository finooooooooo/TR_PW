# Simkos G4 — Sistem Informasi Pengelolaan Rumah Kos

Aplikasi Laravel untuk mengelola kamar, penghuni, masa sewa, dan catatan pembayaran manual di satu lokasi kos.

## Fitur

- Login dan logout; seluruh halaman pengelolaan memerlukan autentikasi.
- CRUD kamar dan penghuni, termasuk pencarian penghuni serta validasi NIK 16 digit yang unik.
- Pencatatan masa sewa; kamar otomatis berstatus tersedia atau terisi berdasarkan masa sewa aktif.
- Pencatatan pembayaran manual dengan periode, nominal, metode pencatatan, dan status.
- Dashboard ringkasan kamar, penghuni, pembayaran, dan masa sewa yang segera berakhir.
- Laporan penghuni dan pembayaran yang bisa diunduh sebagai PDF.
- Data contoh fiktif melalui seeder.

Pembayaran hanya dicatat. Aplikasi tidak memproses transfer, pembayaran online, atau integrasi bank. Aplikasi juga tidak mengelola kontrak elektronik, listrik, agen, maupun banyak properti.

## Teknologi

- PHP 8.2+
- Laravel 12
- MySQL atau MariaDB
- Blade dan Bootstrap 5
- DomPDF untuk laporan PDF

## Menjalankan aplikasi

1. Buat database MySQL/MariaDB bernama `simkos_g4`.
2. Salin `.env.example` menjadi `.env`, lalu sesuaikan `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.
3. Jalankan perintah berikut dari folder proyek:

   ```sh
   composer install
   php artisan key:generate
   npm install
   npm run build
   php artisan migrate --seed
   php artisan serve
   ```

4. Buka `http://127.0.0.1:8000`.

Seeder menyediakan akun demo **admin@simkos.test** dengan kata sandi **password**. Ganti kata sandi tersebut sebelum aplikasi digunakan dengan data sungguhan. Seeder aman dijalankan kembali untuk data contoh yang sama.

## Pengujian

Jalankan `php artisan test`. Pengujian memakai SQLite in-memory agar tidak mengubah database aplikasi dan mencakup login/logout, alur kamar–penghuni–sewa–pembayaran, validasi, perlindungan data riwayat, dan pembuatan PDF. Jika perintah tes melaporkan driver SQLite tidak ditemukan, aktifkan ekstensi `pdo_sqlite` dan `sqlite3` pada `php.ini` PHP CLI yang digunakan.

## Struktur data inti

- `rooms`: nomor, tipe, harga, fasilitas, dan status kamar.
- `tenants`: identitas dan kontak penghuni.
- `leases`: hubungan kamar–penghuni, tanggal sewa, harga bulanan, dan status.
- `payments`: catatan pembayaran terkait masa sewa.

Penghapusan kamar/penghuni yang memiliki riwayat sewa dan penghapusan masa sewa yang sudah memiliki pembayaran ditolak agar riwayat tidak ikut terhapus.

## Dokumen pengumpulan

Ringkasan aplikasi dan cara menjalankan ada di dokumen ini. Sebelum dikumpulkan, lengkapi nama anggota/pembagian tugas kelompok dan siapkan screenshot halaman serta bukti demonstrasi pengujian sesuai arahan dosen.
