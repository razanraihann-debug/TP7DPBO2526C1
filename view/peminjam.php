<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../class/Peminjam.php';

$peminjam = new Peminjam($pdo);

// ---- Handler aksi ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'create') {
            $peminjam->create(trim($_POST['nama_peminjam']), trim($_POST['nim']), trim($_POST['telepon']));
            redirect('peminjam.php', 'Peminjam berhasil ditambahkan');
        } elseif ($action === 'update') {
            $peminjam->update((int) $_POST['id'], trim($_POST['nama_peminjam']), trim($_POST['nim']), trim($_POST['telepon']));
            redirect('peminjam.php', 'Peminjam berhasil diubah');
        } elseif ($action === 'delete') {
            $peminjam->delete((int) $_POST['id']);
            redirect('peminjam.php', 'Peminjam berhasil dihapus');
        }
    } catch (PDOException $ex) {
        redirect('peminjam.php', 'Gagal: NIM sudah dipakai, peminjam masih punya data peminjaman, atau input tidak valid', 'err');
    }
}

// ---- Read ----
$editData = isset($_GET['edit']) ? $peminjam->getById((int) $_GET['edit']) : null;
$list = $peminjam->getAll();

$pageTitle = 'Peminjam';
$pageSub = 'Data mahasiswa yang dapat meminjam alat.';
require_once __DIR__ . '/header.php';
?>
<div class="stats">
    <?= statCard('users', 'Total Peminjam', count($list), 'sky') ?>
</div>

<div class="grid">
    <div class="card form-card">
        <div class="card-head">
            <h2><?= icon($editData ? 'edit' : 'plus') ?> <?= $editData ? 'Edit Peminjam' : 'Tambah Peminjam' ?></h2>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
                <?php if ($editData): ?><input type="hidden" name="id" value="<?= e($editData['id_peminjam']) ?>"><?php endif; ?>
                <label>Nama Lengkap</label>
                <input type="text" name="nama_peminjam" placeholder="Nama peminjam" required value="<?= e($editData['nama_peminjam'] ?? '') ?>">
                <label>NIM</label>
                <input type="text" name="nim" placeholder="Nomor induk mahasiswa" required value="<?= e($editData['nim'] ?? '') ?>">
                <label>Telepon</label>
                <input type="text" name="telepon" placeholder="08xxxxxxxxxx" value="<?= e($editData['telepon'] ?? '') ?>">
                <div class="form-actions">
                    <button type="submit" class="btn-main"><?= $editData ? 'Simpan Perubahan' : 'Tambah Peminjam' ?></button>
                    <?php if ($editData): ?><a class="btn btn-cancel" href="peminjam.php">Batal</a><?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h2><?= icon('users') ?> Daftar Peminjam</h2>
            <div class="search"><?= icon('search', 16) ?><input type="text" placeholder="Cari nama / NIM..." data-filter="tbl"></div>
        </div>
        <div class="table-wrap">
            <table id="tbl">
                <thead><tr><th>#</th><th>Peminjam</th><th>NIM</th><th>Telepon</th><th class="r">Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($list as $i => $row): ?>
                    <tr data-row>
                        <td class="id-chip"><?= e($row['id_peminjam']) ?></td>
                        <td>
                            <div class="person">
                                <div class="avatar alt<?= $i % 4 ?>"><?= e(initials($row['nama_peminjam'])) ?></div>
                                <div class="person-name"><?= e($row['nama_peminjam']) ?></div>
                            </div>
                        </td>
                        <td><span class="badge badge-blue"><?= e($row['nim']) ?></span></td>
                        <td class="muted"><?= e($row['telepon'] ?: '-') ?></td>
                        <td>
                            <div class="actions">
                                <a class="btn icon-btn edit" title="Edit" href="?edit=<?= e($row['id_peminjam']) ?>"><?= icon('edit', 16) ?></a>
                                <form method="post" onsubmit="return confirm('Hapus peminjam ini?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= e($row['id_peminjam']) ?>">
                                    <button class="icon-btn del" title="Hapus" type="submit"><?= icon('trash', 16) ?></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$list): ?>
                    <tr><td colspan="5" class="empty"><?= icon('inbox', 40) ?><div>Belum ada peminjam</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
