<?php
class Peminjam
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM peminjam ORDER BY id_peminjam DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM peminjam WHERE id_peminjam = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(string $nama, string $nim, string $telepon): bool
    {
        $stmt = $this->db->prepare("INSERT INTO peminjam (nama_peminjam, nim, telepon) VALUES (:nama, :nim, :telp)");
        return $stmt->execute([':nama' => $nama, ':nim' => $nim, ':telp' => $telepon]);
    }

    public function update(int $id, string $nama, string $nim, string $telepon): bool
    {
        $stmt = $this->db->prepare("UPDATE peminjam SET nama_peminjam = :nama, nim = :nim, telepon = :telp WHERE id_peminjam = :id");
        return $stmt->execute([':nama' => $nama, ':nim' => $nim, ':telp' => $telepon, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM peminjam WHERE id_peminjam = :id");
        return $stmt->execute([':id' => $id]);
    }
}
