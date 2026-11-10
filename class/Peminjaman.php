<?php
class Peminjaman
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // JOIN dengan tabel alat dan peminjam (2 relasi FK)
    public function getAll(): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.*, a.nama_alat, m.nama_peminjam
             FROM peminjaman p
             JOIN alat a ON p.id_alat = a.id_alat
             JOIN peminjam m ON p.id_peminjam = m.id_peminjam
             ORDER BY p.id_peminjaman DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM peminjaman WHERE id_peminjaman = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(int $idAlat, int $idPeminjam, string $tglPinjam, ?string $tglKembali, string $status): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO peminjaman (id_alat, id_peminjam, tgl_pinjam, tgl_kembali, status)
             VALUES (:alat, :peminjam, :pinjam, :kembali, :status)"
        );
        return $stmt->execute([
            ':alat' => $idAlat,
            ':peminjam' => $idPeminjam,
            ':pinjam' => $tglPinjam,
            ':kembali' => $tglKembali,
            ':status' => $status,
        ]);
    }

    public function update(int $id, int $idAlat, int $idPeminjam, string $tglPinjam, ?string $tglKembali, string $status): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE peminjaman
             SET id_alat = :alat, id_peminjam = :peminjam, tgl_pinjam = :pinjam,
                 tgl_kembali = :kembali, status = :status
             WHERE id_peminjaman = :id"
        );
        return $stmt->execute([
            ':alat' => $idAlat,
            ':peminjam' => $idPeminjam,
            ':pinjam' => $tglPinjam,
            ':kembali' => $tglKembali,
            ':status' => $status,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM peminjaman WHERE id_peminjaman = :id");
        return $stmt->execute([':id' => $id]);
    }
}
