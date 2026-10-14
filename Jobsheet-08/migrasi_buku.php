<?php
require __DIR__ . '/includes/koneksi.php';

$file = __DIR__ . '/data/buku.json';
if (!file_exists($file)) {
    die("File $file tidak ditemukan.\n");
}

$data = json_decode(file_get_contents($file), true);
if (!is_array($data)) {
    die("JSON tidak valid: " . json_last_error_msg() . "\n");
}

$stmt = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
);

$pdo->beginTransaction();
try {
    foreach ($data as $b) {
        $stmt->execute([
            'judul'     => $b['judul'] ?? '',
            'pengarang' => $b['pengarang'] ?? '',
            'tahun'     => (int) ($b['tahun'] ?? 0),
            'isbn'      => $b['isbn'] ?? '',
            'stok'      => (int) ($b['stok'] ?? 0),
            'kategori'  => $b['kategori'] ?? '',
        ]);
    }
    $pdo->commit();
    echo count($data) . " buku berhasil dimigrasi.\n";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Migrasi dibatalkan: " . $e->getMessage() . "\n";
}