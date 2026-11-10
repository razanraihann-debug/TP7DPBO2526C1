CREATE DATABASE IF NOT EXISTS db_peminjaman_alat;
USE db_peminjaman_alat;

DROP TABLE IF EXISTS peminjaman;
DROP TABLE IF EXISTS alat;
DROP TABLE IF EXISTS peminjam;
DROP TABLE IF EXISTS kategori;

CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE alat (
    id_alat INT AUTO_INCREMENT PRIMARY KEY,
    nama_alat VARCHAR(100) NOT NULL,
    id_kategori INT NOT NULL,
    kondisi ENUM('Baik','Rusak Ringan','Rusak Berat') NOT NULL DEFAULT 'Baik',
    CONSTRAINT fk_alat_kategori FOREIGN KEY (id_kategori)
        REFERENCES kategori(id_kategori) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE peminjam (
    id_peminjam INT AUTO_INCREMENT PRIMARY KEY,
    nama_peminjam VARCHAR(100) NOT NULL,
    nim VARCHAR(20) NOT NULL UNIQUE,
    telepon VARCHAR(20) DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE peminjaman (
    id_peminjaman INT AUTO_INCREMENT PRIMARY KEY,
    id_alat INT NOT NULL,
    id_peminjam INT NOT NULL,
    tgl_pinjam DATE NOT NULL,
    tgl_kembali DATE DEFAULT NULL,
    status ENUM('Dipinjam','Dikembalikan') NOT NULL DEFAULT 'Dipinjam',
    CONSTRAINT fk_pinjam_alat FOREIGN KEY (id_alat)
        REFERENCES alat(id_alat) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_pinjam_peminjam FOREIGN KEY (id_peminjam)
        REFERENCES peminjam(id_peminjam) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO kategori (nama_kategori, deskripsi) VALUES
('Elektronik', 'Perangkat elektronik laboratorium'),
('Alat Ukur', 'Alat untuk pengukuran'),
('Perkakas', 'Peralatan tangan dan perbaikan');

INSERT INTO alat (nama_alat, id_kategori, kondisi) VALUES
('Proyektor Epson', 1, 'Baik'),
('Multimeter Digital', 2, 'Baik'),
('Obeng Set', 3, 'Rusak Ringan'),
('Oscilloscope', 2, 'Baik');

INSERT INTO peminjam (nama_peminjam, nim, telepon) VALUES
('Budi Santoso', '2300001', '081234567890'),
('Siti Aminah', '2300002', '081298765432'),
('Andi Wijaya', '2300003', '082111222333');

INSERT INTO peminjaman (id_alat, id_peminjam, tgl_pinjam, tgl_kembali, status) VALUES
(1, 1, '2026-11-01', '2026-11-03', 'Dikembalikan'),
(2, 2, '2026-11-05', NULL, 'Dipinjam'),
(4, 3, '2026-11-08', NULL, 'Dipinjam');
