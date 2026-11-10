<?php
class Alat
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // JOIN dengan tabel kategori (relasi FK id_kategori)
    public function getAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, k.nama_kategori
             FROM alat a
             JOIN kategori k ON a.id_kategori = k.id_kategori
             ORDER BY a.id_alat DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM alat WHERE id_alat = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(string $nama, int $idKategori, string $kondisi): bool
    {
        $stmt = $this->db->prepare("INSERT INTO alat (nama_alat, id_kategori, kondisi) VALUES (:nama, :kat, :kondisi)");
        return $stmt->execute([':nama' => $nama, ':kat' => $idKategori, ':kondisi' => $kondisi]);
    }

    public function update(int $id, string $nama, int $idKategori, string $kondisi): bool
    {
        $stmt = $this->db->prepare("UPDATE alat SET nama_alat = :nama, id_kategori = :kat, kondisi = :kondisi WHERE id_alat = :id");
        return $stmt->execute([':nama' => $nama, ':kat' => $idKategori, ':kondisi' => $kondisi, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM alat WHERE id_alat = :id");
        return $stmt->execute([':id' => $id]);
    }
}
