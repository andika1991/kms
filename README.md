# 📚 Knowledge Management System (KMS) - Laravel Web App

Aplikasi **Knowledge Management System (KMS)** berbasis Laravel, dirancang untuk memudahkan organisasi dalam mengelola, menyimpan, dan menyebarkan informasi penting secara terstruktur. Aplikasi ini menggunakan Laravel 10 sebagai back-end dan Vite + Tailwind CSS sebagai sistem build front-end.

---

## 🔑 Fitur Utama

- ✅ Autentikasi User,Admin,Pegawai,Magang,Kepala Sub Bidang,Kepala Bagian,Sekretaris,Kepala Dinas
- 📁 Upload dan Manajemen Dokumen,Berbagi Dokumen
- 🗂️ Kategorisasi dan Tagging Pengetahuan
- 🔍 Pencarian Dokumen Pintar
- Forum Diskusi
- 📊 Dashboard Ringkasan Aktivitas
- 👥 Role-Based Access Control (RBAC)
- 📥 Unduhan Dokumen & Statistik
- 🛠️ CRUD Modul KMS

---

## ⚙️ Persyaratan Sistem

Pastikan Anda sudah menginstal:

| Komponen       | Versi Minimal     |
|----------------|-------------------|
| PHP            | 8.1               |
| Composer       | 2.x               |
| MySQL / MariaDB| 5.7 / 10.x        |
| Node.js        | 18.x / 20.x       |
| NPM            | 9.x / 10.x        |
| Git            | Terbaru           |

---

## 🚀 Cara Instalasi & Setup Lokal

Ikuti langkah-langkah berikut agar aplikasi bisa berjalan di lingkungan lokal Anda.

### 1. Clone Project

```bash
git clone https://github.com/andika1991/kms.git
cd kms-laravel
```

---

### 2. Install Dependency Laravel

```bash
composer install
```

---

### 3. Salin dan Atur File `.env`

```bash
cp .env.example .env
```

Ubah konfigurasi database di file `.env` sesuai dengan environment lokal:

```env
APP_NAME=KMS Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kms_db
DB_USERNAME=root
DB_PASSWORD=
```

---

### 4. Generate Application Key

```bash
php artisan key:generate
```

---

### 5. Setup Database

- Buat database bernama `kms_db` di MySQL
- Lalu jalankan migrasi & seeder (jika tersedia):

```bash
php artisan migrate --seed
```

---
Rekomendasi jika terdapat eror maka import saja file sql berikut ke database
https://drive.google.com/file/d/1VUNiv7FIB7_vhS-eo6wCARlUHaqBYWtF/view?usp=sharing
### 6. Install Front-End Dependencies

```bash
npm install
```

---

### 7. Jalankan Vite untuk Build Front-End

#### Untuk pengembangan (auto-reload):

```bash
npm run dev
```

#### Untuk produksi:

```bash
npm run build
```

---

### 8. Jalankan Aplikasi Laravel

```bash
php artisan serve
```

Akses aplikasi di browser:

```
http://localhost:8000
```

---

## 🗂️ Struktur Folder

```
kms-laravel/
├── app/
│   ├── Http/
│   ├── Models/
│   └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── uploads/
├── resources/
│   ├── views/
│   └── js/
├── routes/
│   └── web.php
├── .env
├── package.json
├── vite.config.js
├── tailwind.config.js
└── README.md
```
Untuk Posisi controllernya dapat diperhatikan saja di ruotingnya di web.php
---

## 🧪 Testing & Troubleshooting

Jika ada error:

```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize
```

Cek status database:

```bash
php artisan migrate:status
```

Pastikan semua route terdaftar:

```bash
php artisan route:list
```

---

## 🙌 Kontribusi

1. Fork repository ini
2. Buat branch fitur baru:
   ```bash
   git checkout -b fitur-baru
   ```
3. Commit dan push perubahan:
   ```bash
   git commit -m "Tambah fitur baru"
   git push origin fitur-baru
   ```
4. Buat Pull Request ke branch `main`

---

## 📝 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE). Anda bebas menggunakan dan memodifikasi dengan menyebutkan nama pembuat.

---

## 📬 Kontak Pengembang
Jika ingin diskusi lebih lanjut/ menanyakan error dapat menghubungi:
- 👨‍💻 Nama: **Andika Fikri Azhari**
- 📧 Email: andikapsw30@gmail.com
- 🌐 GitHub: [github.com/andikafikri](https://github.com/andika1991)

- 👨‍💻 Nama: **Reguel Andreas Pangaribuan**
- 📧 Email: reguelandreas@gmail.com
- 🌐 GitHub: [github.com/reguelpangarib](https://github.com/reguelpangarib)

- 👨‍💻 Nama: **Candra Wijaya**
- 📧 Email: 
- 🌐 GitHub: 
---

## 📌 Catatan

> Jika Anda menggunakan package tambahan seperti Laravel Breeze, Spatie Permission, Filepond, CKEditor, atau lainnya, harap sesuaikan langkah instalasi di atas dengan dokumentasi masing-masing package.

---

Selamat berkarya dan semoga bermanfaat! 🚀
