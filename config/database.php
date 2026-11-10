<?php
// Konfigurasi & koneksi database menggunakan PDO
class Database
{
    private string $host = 'localhost';
    private string $dbname = 'db_peminjaman_alat';
    private string $user = 'root';
    private string $pass = '';

    public PDO $db;

    public function __construct()
    {
        try {
            $this->db = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->user,
                $this->pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            die('Koneksi database gagal: ' . $e->getMessage());
        }
    }
}
