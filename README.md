# PBL Lab Business Analytics

## Installation

Untuk Instalasi Ikuti Step Berikut:

1. Clone Repository:

```
git clone -b prod https://github.com/baynobu/PBL-Lab-Business-Analytics.git
```

Lalu ubah nama folder nya menjadi lab-ba

2. Create Database 'lab-ba':

```
CREATE DATABASE lab-ba;
```

3. Backup Database .sql file
   [Click here](https://github.com/baynobu/PBL-Lab-Business-Analytics/blob/docs/DB%20%2B%20etc/db%20final/db-seed-lab-ba-completed.sql)

4. Run laragon server

5. Configure database di folder /app/config/database.php

6. Lalu run

```
localhost/lab-ba/public/index.php
```

> Pastikan folder aplikasi web bernama `lab-ba`.

## Folder Structure

```
lab-ba
├── README.md
├── admin
│   ├── berita
│   │   ├── edit.php
│   │   ├── hapus.php
│   │   ├── manage.php
│   │   └── tambah.php
│   ├── dashboard.php
│   ├── dosen
│   │   ├── edit.php
│   │   ├── hapus.php
│   │   ├── manage.php
│   │   └── tambah.php
│   ├── galeri
│   │   ├── edit.php
│   │   ├── hapus.php
│   │   ├── manage.php
│   │   └── tambah.php
│   ├── kategori
│   │   ├── edit.php
│   │   ├── hapus.php
│   │   ├── manage.php
│   │   └── tambah.php
│   ├── kelola_jam
│   │   └── jam-tidak-tersedia.php
│   ├── kontak
│   │   └── lab.php
│   ├── logout.php
│   ├── peminjaman
│   │   ├── hapus.php
│   │   ├── manage.php
│   │   └── set.php
│   ├── pengaturan
│   │   └── website.php
│   ├── profil
│   │   ├── edit.php
│   │   ├── hapus.php
│   │   ├── manage.php
│   │   └── tambah.php
│   ├── publikasi
│   │   ├── edit.php
│   │   ├── hapus.php
│   │   ├── manage.php
│   │   └── tambah.php
│   └── users
│       ├── edit.php
│       ├── hapus.php
│       ├── manage.php
│       └── tambah.php
├── app
│   ├── config
│   │   └── database.php
│   ├── controllers
│   │   └── PeminjamanController.php
│   ├── models
│   │   ├── Admin.php
│   │   ├── Berita.php
│   │   ├── Dosen.php
│   │   ├── Galeri.php
│   │   ├── JamTidakTersedia.php
│   │   ├── Kategori.php
│   │   ├── KontakLab.php
│   │   ├── Peminjaman.php
│   │   ├── Profil.php
│   │   ├── Publikasi.php
│   │   └── Settings.php
│   └── utils
│       ├── log.php
│       └── session.php
├── public
│   ├── assets
│   │   ├── css
│   │   │   └── style.css
│   │   ├── img
│   │   │   ├── logo.png
│   │   │   └── maskot.png
│   │   └── js
│   │       ├── dropdown-fix.js
│   │       └── script.js
│   ├── berita.php
│   ├── berita_detail.php
│   ├── dosen.php
│   ├── galeri.php
│   ├── index.php
│   ├── jadwal.php
│   ├── login.php
│   ├── peminjaman.php
│   ├── profil.php
│   ├── publikasi.php
│   ├── publikasi_detail.php
│   ├── test-db.php
│   └── uploads
│       ├── berita
│       ├── dosen
│       ├── galeri
│       ├── logo
│       │   └── logo.png
│       └── publikasi
└── views
    ├── home.php
    ├── layouts
    │   ├── footer.php
    │   └── header.php
    └── peminjaman.php
```
