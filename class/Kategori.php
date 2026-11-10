<?php
class Kategori
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM kategori ORDER BY id_kategori DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM kategori WHERE id_kategori = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(string $nama, string $deskripsi): bool
    {
        $stmt = $this->db->prepare("INSERT INTO kategori (nama_kategori, deskripsi) VALUES (:nama, :deskripsi)");
        return $stmt->execute([':nama' => $nama, ':deskripsi' => $deskripsi]);
    }

    public function update(int $id, string $nama, string $deskripsi): bool
    {
        $stmt = $this->db->prepare("UPDATE kategori SET nama_kategori = :nama, deskripsi = :deskripsi WHERE id_kategori = :id");
        return $stmt->execute([':nama' => $nama, ':deskripsi' => $deskripsi, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM kategori WHERE id_kategori = :id");
        return $stmt->execute([':id' => $id]);
    }
}
