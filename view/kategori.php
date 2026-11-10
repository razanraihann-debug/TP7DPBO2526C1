<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/../class/Kategori.php';

$kategori = new Kategori($pdo);

// ---- Handler aksi (Create / Update / Delete) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'create') {
            $kategori->create(trim($_POST['nama_kategori']), trim($_POST['deskripsi']));
            redirect('kategori.php', 'Kategori berhasil ditambahkan');
        } elseif ($action === 'update') {
            $kategori->update((int) $_POST['id'], trim($_POST['nama_kategori']), trim($_POST['deskripsi']));
            redirect('kategori.php', 'Kategori berhasil diubah');
        } elseif ($action === 'delete') {
            $kategori->delete((int) $_POST['id']);
            redirect('kategori.php', 'Kategori berhasil dihapus');
        }
    } catch (PDOException $ex) {
        redirect('kategori.php', 'Gagal: kategori masih dipakai oleh data alat atau input tidak valid', 'err');
    }
}

// ---- Read ----
$editData = isset($_GET['edit']) ? $kategori->getById((int) $_GET['edit']) : null;
$list = $kategori->getAll();

$pageTitle = 'Kategori';
$pageSub = 'Kelompokkan alat berdasarkan jenisnya.';
require_once __DIR__ . '/header.php';
?>
<div class="stats">
    <?= statCard('tag', 'Total Kategori', count($list), 'indigo') ?>
</div>

<div class="grid">
    <div class="card form-card">
        <div class="card-head">
            <h2><?= icon($editData ? 'edit' : 'plus') ?> <?= $editData ? 'Edit Kategori' : 'Tambah Kategori' ?></h2>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
                <?php if ($editData): ?><input type="hidden" name="id" value="<?= e($editData['id_kategori']) ?>"><?php endif; ?>
                <label>Nama Kategori</label>
                <input type="text" name="nama_kategori" placeholder="Contoh: Elektronik" required value="<?= e($editData['nama_kategori'] ?? '') ?>">
                <label>Deskripsi</label>
                <input type="text" name="deskripsi" placeholder="Keterangan singkat" value="<?= e($editData['deskripsi'] ?? '') ?>">
                <div class="form-actions">
                    <button type="submit" class="btn-main"><?= $editData ? 'Simpan Perubahan' : 'Tambah Kategori' ?></button>
                    <?php if ($editData): ?><a class="btn btn-cancel" href="kategori.php">Batal</a><?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h2><?= icon('tag') ?> Daftar Kategori</h2>
            <div class="search"><?= icon('search', 16) ?><input type="text" placeholder="Cari kategori..." data-filter="tbl"></div>
        </div>
        <div class="table-wrap">
            <table id="tbl">
                <thead><tr><th>#</th><th>Kategori</th><th>Deskripsi</th><th class="r">Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($list as $i => $row): ?>
                    <tr data-row>
                        <td class="id-chip"><?= e($row['id_kategori']) ?></td>
                        <td>
                            <div class="person">
                                <div class="avatar alt<?= $i % 4 ?>"><?= e(initials($row['nama_kategori'])) ?></div>
                                <div class="person-name"><?= e($row['nama_kategori']) ?></div>
                            </div>
                        </td>
                        <td class="muted"><?= e($row['deskripsi'] ?: '-') ?></td>
                        <td>
                            <div class="actions">
                                <a class="btn icon-btn edit" title="Edit" href="?edit=<?= e($row['id_kategori']) ?>"><?= icon('edit', 16) ?></a>
                                <form method="post" onsubmit="return confirm('Hapus kategori ini?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= e($row['id_kategori']) ?>">
                                    <button class="icon-btn del" title="Hapus" type="submit"><?= icon('trash', 16) ?></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$list): ?>
                    <tr><td colspan="4" class="empty"><?= icon('inbox', 40) ?><div>Belum ada kategori</div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
