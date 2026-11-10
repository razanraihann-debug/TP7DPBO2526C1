<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../class/Alat.php';
require_once __DIR__ . '/../class/Kategori.php';

$alat = new Alat($pdo);
$kategori = new Kategori($pdo);
$kondisiList = ['Baik', 'Rusak Ringan', 'Rusak Berat'];

// ---- Handler aksi ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'create') {
            $alat->create(trim($_POST['nama_alat']), (int) $_POST['id_kategori'], $_POST['kondisi']);
            redirect('alat.php', 'Alat berhasil ditambahkan');
        } elseif ($action === 'update') {
            $alat->update((int) $_POST['id'], trim($_POST['nama_alat']), (int) $_POST['id_kategori'], $_POST['kondisi']);
            redirect('alat.php', 'Alat berhasil diubah');
        } elseif ($action === 'delete') {
            $alat->delete((int) $_POST['id']);
            redirect('alat.php', 'Alat berhasil dihapus');
        }
    } catch (PDOException $ex) {
        redirect('alat.php', 'Gagal: alat masih tercatat di data peminjaman atau input tidak valid', 'err');
    }
}

// ---- Read ----
$editData = isset($_GET['edit']) ? $alat->getById((int) $_GET['edit']) : null;
$list = $alat->getAll();
$kategoriList = $kategori->getAll();

$total = count($list);
$baik = count(array_filter($list, fn($r) => $r['kondisi'] === 'Baik'));

$pageTitle = 'Alat';
$pageSub = 'Kelola inventaris alat beserta kondisinya.';
require_once __DIR__ . '/header.php';
?>
<div class="stats">
    <?= statCard('package', 'Total Alat', $total, 'indigo') ?>
    <?= statCard('check', 'Kondisi Baik', $baik, 'green') ?>
    <?= statCard('alert', 'Perlu Perhatian', $total - $baik, 'rose') ?>
</div>

<div class="grid">
    <div class="card form-card">
        <div class="card-head">
            <h2><?= icon($editData ? 'edit' : 'plus') ?> <?= $editData ? 'Edit Alat' : 'Tambah Alat' ?></h2>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
                <?php if ($editData): ?><input type="hidden" name="id" value="<?= e($editData['id_alat']) ?>"><?php endif; ?>
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" placeholder="Contoh: Proyektor" required value="<?= e($editData['nama_alat'] ?? '') ?>">
                <label>Kategori</label>
                <select name="id_kategori" required>
                    <option value="">Pilih kategori</option>
                    <?php foreach ($kategoriList as $k): ?>
                        <option value="<?= e($k['id_kategori']) ?>" <?= ($editData['id_kategori'] ?? '') == $k['id_kategori'] ? 'selected' : '' ?>>
                            <?= e($k['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Kondisi</label>
                <select name="kondisi">
                    <?php foreach ($kondisiList as $kd): ?>
                        <option value="<?= e($kd) ?>" <?= ($editData['kondisi'] ?? 'Baik') === $kd ? 'selected' : '' ?>><?= e($kd) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-actions">
                    <button type="submit" class="btn-main"><?= $editData ? 'Simpan Perubahan' : 'Tambah Alat' ?></button>
                    <?php if ($editData): ?><a class="btn btn-cancel" href="alat.php">Batal</a><?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h2><?= icon('package') ?> Daftar Alat</h2>
            <div class="search"><?= icon('search', 16) ?><input type="text" placeholder="Cari alat..." data-filter="tbl"></div>
        </div>
        <div class="table-wrap">
            <table id="tbl">
                <thead><tr><th>#</th><th>Alat</th><th>Kategori</th><th>Kondisi</th><th class="r">Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($list as $i => $row): ?>
                    <tr data-row>
                        <td class="id-chip"><?= e($row['id_alat']) ?></td>
                        <td>
                            <div class="person">
                                <div class="avatar alt<?= $i % 4 ?>"><?= e(initials($row['nama_alat'])) ?></div>
                                <div class="person-name"><?= e($row['nama_alat']) ?></div>
                            </div>
                        </td>
                        <td><span class="badge badge-blue"><?= e($row['nama_kategori']) ?></span></td>
                        <td><span class="badge <?= $row['kondisi'] === 'Baik' ? 'badge-green' : ($row['kondisi'] === 'Rusak Ringan' ? 'badge-yellow' : 'badge-red') ?>"><?= e($row['kondisi']) ?></span></td>
                        <td>
                            <div class="actions">
                                <a class="btn icon-btn edit" title="Edit" href="?edit=<?= e($row['id_alat']) ?>"><?= icon('edit', 16) ?></a>
                                <form method="post" onsubmit="return confirm('Hapus alat ini?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= e($row['id_alat']) ?>">
                                    <button class="icon-btn del" title="Hapus" type="submit"><?= icon('trash', 16) ?></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$list): ?>
                    <tr><td colspan="5" class="empty"><?= icon('inbox', 40) ?><div>Belum ada alat</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
