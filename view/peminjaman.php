<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../class/Peminjaman.php';
require_once __DIR__ . '/../class/Alat.php';
require_once __DIR__ . '/../class/Peminjam.php';

$peminjaman = new Peminjaman($pdo);
$alat = new Alat($pdo);
$peminjam = new Peminjam($pdo);
$statusList = ['Dipinjam', 'Dikembalikan'];

// ---- Handler aksi ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $tglKembali = !empty($_POST['tgl_kembali']) ? $_POST['tgl_kembali'] : null;
    try {
        if ($action === 'create') {
            $peminjaman->create((int) $_POST['id_alat'], (int) $_POST['id_peminjam'], $_POST['tgl_pinjam'], $tglKembali, $_POST['status']);
            redirect('peminjaman.php', 'Peminjaman berhasil ditambahkan');
        } elseif ($action === 'update') {
            $peminjaman->update((int) $_POST['id'], (int) $_POST['id_alat'], (int) $_POST['id_peminjam'], $_POST['tgl_pinjam'], $tglKembali, $_POST['status']);
            redirect('peminjaman.php', 'Peminjaman berhasil diubah');
        } elseif ($action === 'delete') {
            $peminjaman->delete((int) $_POST['id']);
            redirect('peminjaman.php', 'Peminjaman berhasil dihapus');
        }
    } catch (PDOException $ex) {
        redirect('peminjaman.php', 'Gagal menyimpan data, periksa kembali input', 'err');
    }
}

// ---- Read ----
$editData = isset($_GET['edit']) ? $peminjaman->getById((int) $_GET['edit']) : null;
$list = $peminjaman->getAll();
$alatList = $alat->getAll();
$peminjamList = $peminjam->getAll();

$total = count($list);
$dipinjam = count(array_filter($list, fn($r) => $r['status'] === 'Dipinjam'));

$pageTitle = 'Peminjaman';
$pageSub = 'Catat transaksi peminjaman dan pengembalian alat.';
require_once __DIR__ . '/header.php';
?>
<div class="stats">
    <?= statCard('clipboard', 'Total Transaksi', $total, 'indigo') ?>
    <?= statCard('clock', 'Sedang Dipinjam', $dipinjam, 'amber') ?>
    <?= statCard('check', 'Sudah Kembali', $total - $dipinjam, 'green') ?>
</div>

<div class="grid">
    <div class="card form-card">
        <div class="card-head">
            <h2><?= icon($editData ? 'edit' : 'plus') ?> <?= $editData ? 'Edit Peminjaman' : 'Peminjaman Baru' ?></h2>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
                <?php if ($editData): ?><input type="hidden" name="id" value="<?= e($editData['id_peminjaman']) ?>"><?php endif; ?>
                <label>Alat</label>
                <select name="id_alat" required>
                    <option value="">Pilih alat</option>
                    <?php foreach ($alatList as $a): ?>
                        <option value="<?= e($a['id_alat']) ?>" <?= ($editData['id_alat'] ?? '') == $a['id_alat'] ? 'selected' : '' ?>><?= e($a['nama_alat']) ?></option>
                    <?php endforeach; ?>
                </select>
                <label>Peminjam</label>
                <select name="id_peminjam" required>
                    <option value="">Pilih peminjam</option>
                    <?php foreach ($peminjamList as $p): ?>
                        <option value="<?= e($p['id_peminjam']) ?>" <?= ($editData['id_peminjam'] ?? '') == $p['id_peminjam'] ? 'selected' : '' ?>><?= e($p['nama_peminjam']) ?> (<?= e($p['nim']) ?>)</option>
                    <?php endforeach; ?>
                </select>
                <div class="row">
                    <div>
                        <label>Tgl Pinjam</label>
                        <input type="date" name="tgl_pinjam" required value="<?= e($editData['tgl_pinjam'] ?? date('Y-m-d')) ?>">
                    </div>
                    <div>
                        <label>Tgl Kembali</label>
                        <input type="date" name="tgl_kembali" value="<?= e($editData['tgl_kembali'] ?? '') ?>">
                    </div>
                </div>
                <label>Status</label>
                <select name="status">
                    <?php foreach ($statusList as $s): ?>
                        <option value="<?= e($s) ?>" <?= ($editData['status'] ?? 'Dipinjam') === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-actions">
                    <button type="submit" class="btn-main"><?= $editData ? 'Simpan Perubahan' : 'Simpan Peminjaman' ?></button>
                    <?php if ($editData): ?><a class="btn btn-cancel" href="peminjaman.php">Batal</a><?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h2><?= icon('clipboard') ?> Daftar Peminjaman</h2>
            <div class="search"><?= icon('search', 16) ?><input type="text" placeholder="Cari alat / peminjam..." data-filter="tbl"></div>
        </div>
        <div class="table-wrap">
            <table id="tbl">
                <thead><tr><th>#</th><th>Alat</th><th>Peminjam</th><th>Periode</th><th>Status</th><th class="r">Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($list as $i => $row): ?>
                    <tr data-row>
                        <td class="id-chip"><?= e($row['id_peminjaman']) ?></td>
                        <td class="person-name nowrap"><?= e($row['nama_alat']) ?></td>
                        <td class="nowrap"><?= e($row['nama_peminjam']) ?></td>
                        <td class="nowrap">
                            <div><?= e(tgl($row['tgl_pinjam'])) ?></div>
                            <div class="person-sub">s/d <?= e(tgl($row['tgl_kembali'])) ?></div>
                        </td>
                        <td><span class="badge <?= $row['status'] === 'Dikembalikan' ? 'badge-green' : 'badge-yellow' ?>"><?= e($row['status']) ?></span></td>
                        <td>
                            <div class="actions">
                                <a class="btn icon-btn edit" title="Edit" href="?edit=<?= e($row['id_peminjaman']) ?>"><?= icon('edit', 16) ?></a>
                                <form method="post" onsubmit="return confirm('Hapus data peminjaman ini?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= e($row['id_peminjaman']) ?>">
                                    <button class="icon-btn del" title="Hapus" type="submit"><?= icon('trash', 16) ?></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$list): ?>
                    <tr><td colspan="6" class="empty"><?= icon('inbox', 40) ?><div>Belum ada data peminjaman</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
