# JANJI
Saya Razan Raihan Malik dengan NIM 2508838 mengerjakan Tugas Praktikum 7 pada Mata Kuliah Desain dan Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin

## Tema Website
Sistem peminjaman alat laboratorium/kampus. Admin dapat mengelola kategori alat, data alat, data peminjam (mahasiswa), serta mencatat transaksi peminjaman beserta status pengembaliannya.

## Struktur Folder
```
TP7DPBO2526C/
├── class/        Kategori.php, Alat.php, Peminjam.php, Peminjaman.php (1 class per tabel)
├── config/       database.php (class Database, koneksi PDO)
├── database/     db_peminjaman_alat.sql (struktur + data awal)
├── view/         header.php, footer.php (modular) + kategori.php, alat.php, peminjam.php, peminjaman.php
├── style/        style.css
├── index.php     titik awal aplikasi (redirect ke view/peminjaman.php)
└── README.md
```

## Database: `db_peminjaman_alat`
| Tabel | Kolom | Keterangan |
|---|---|---|
| `kategori` | id_kategori (PK), nama_kategori, deskripsi | Kategori alat |
| `alat` | id_alat (PK), nama_alat, **id_kategori (FK -> kategori)**, kondisi | Data alat |
| `peminjam` | id_peminjam (PK), nama_peminjam, nim (UNIQUE), telepon | Data peminjam |
| `peminjaman` | id_peminjaman (PK), **id_alat (FK -> alat)**, **id_peminjam (FK -> peminjam)**, tgl_pinjam, tgl_kembali, status | Transaksi peminjaman |

Relasi: `kategori 1 - N alat`, `alat 1 - N peminjaman`, `peminjam 1 - N peminjaman`. Semua FK memakai `ON DELETE RESTRICT`, jadi data yang masih dipakai tidak bisa dihapus (akan muncul pesan error).

## Cara Menjalankan
1. Jalankan Apache dan MySQL (Laragon/XAMPP).
2. Letakkan folder project di `www` (Laragon) atau `htdocs` (XAMPP).
3. Import `database/db_peminjaman_alat.sql` lewat phpMyAdmin (database akan dibuat otomatis).
4. Sesuaikan kredensial di `config/database.php` jika perlu (default: user `root`, password kosong).
5. Buka `http://localhost/TP7DPBO2526C/`.

## Flow Kode
1. User membuka halaman view (mis. `view/alat.php`) lewat browser.
2. `view/header.php` dimuat: me-require `config/database.php`, membuat objek `Database`, dan menyediakan `$pdo` + navigasi.
3. File view membuat objek class terkait (mis. `new Alat($pdo)`).
4. Saat user submit form (Tambah / Simpan / Hapus), request POST dikirim ke halaman yang sama dengan field `action` (`create` / `update` / `delete`).
5. View memanggil method class (`create()`, `update()`, `delete()`). Class menjalankan `$db->prepare()` lalu `execute()` dengan parameter ter-bind, tanpa query mentah.
6. Setelah selesai, view melakukan redirect (pola Post/Redirect/Get) dengan pesan sukses/gagal.
7. Pada request GET, view memanggil `getAll()` (dengan JOIN untuk tabel relasi) dan `getById()` (mode edit), lalu data ditampilkan dalam tabel dan form.

## CRUD per Tabel
| Tabel | Create | Read | Update | Delete |
|---|---|---|---|---|
| kategori | form tambah | tabel daftar | tombol Edit | tombol Hapus |
| alat | form tambah (pilih kategori) | tabel + JOIN kategori | tombol Edit | tombol Hapus |
| peminjam | form tambah | tabel daftar | tombol Edit | tombol Hapus |
| peminjaman | form tambah (pilih alat & peminjam) | tabel + JOIN alat & peminjam | tombol Edit | tombol Hapus |

## Dokumentasi
[![YouTube](https://img.shields.io/badge/YouTube-FF0000?style=for-the-badge&logo=youtube&logoColor=white)](https://youtu.be/ZTEJC5gP0GI?si=VZhlCf99WsJsqmoI)

klik untuk melihat video
