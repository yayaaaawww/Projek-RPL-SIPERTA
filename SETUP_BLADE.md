# SIPERTA — Panduan Menjalankan (Tahap 1: Blade + Session Auth)

Versi ini sudah diubah dari **API (JSON)** menjadi **web Laravel biasa (Blade + session login)**.

## Yang sudah jadi di Tahap 1
- Login, Register, Logout (pakai session — tidak perlu token lagi)
- Dashboard untuk 4 role: Petani, Ahli, Pedagang, Admin (menampilkan data asli dari database)
- Layout bersama: sidebar (sesuai role) + header, pakai CSS kamu sendiri (`public/css/app.css`)

## Cara menjalankan di XAMPP

1. Nyalakan **Apache** + **MySQL** di XAMPP.

2. Buka terminal di folder project ini, lalu install dependency:
   ```
   composer install
   ```

3. Siapkan file `.env`:
   ```
   copy .env.example .env       (Windows)
   ```
   Lalu buka `.env` dan sesuaikan bagian database:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=siperta
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Buat database kosong bernama `siperta` lewat phpMyAdmin (http://localhost/phpmyadmin).

5. Generate app key + buat tabel + isi akun contoh:
   ```
   php artisan key:generate
   php artisan migrate:fresh --seed
   php artisan storage:link
   ```

6. Jalankan:
   ```
   php artisan serve
   ```
   Buka http://127.0.0.1:8000

## Akun untuk testing (password semua: `password123`)
| Role     | Email                  |
|----------|------------------------|
| Admin    | admin@siperta.com      |
| Petani   | petani@siperta.com     |
| Ahli     | ahli@siperta.com       |
| Pedagang | pedagang@siperta.com   |

## Catatan
- Ikon di sidebar yang masih `#` (Data, Konsultasi, Katalog, dll) akan diaktifkan di tahap berikutnya.
- File `routes/api.php` lama dibiarkan, tidak dipakai lagi & tidak mengganggu.
