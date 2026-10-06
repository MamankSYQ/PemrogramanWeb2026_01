<?php
session_start();

$nama = trim($_POST['nama'] ?? '');
$noAnggota = trim($_POST['no_anggota'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
} elseif (!preg_match("/^[\p{L}\s.'-]{3,100}$/u", $nama)) {
    $errors[] = "Nama hanya boleh berisi huruf, spasi, titik, apostrof, atau tanda hubung (3-100 karakter).";
}

if ($noAnggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
} elseif (!preg_match('/^[A-Za-z0-9-]{3,20}$/', $noAnggota)) {
    $errors[] = "No. Anggota hanya boleh berisi huruf, angka, dan tanda hubung (3-20 karakter).";
} else {
    foreach ($_SESSION['anggota'] ?? [] as $a) {
        if (strcasecmp($a['no_anggota'], $noAnggota) === 0) {
            $errors[] = "No. Anggota sudah terdaftar.";
            break;
        }
    }
}

if (mb_strlen($alamat) > 255) {
    $errors[] = "Alamat maksimal 255 karakter.";
}

if ($noHp !== '' && !preg_match('/^\+?[0-9]{10,15}$/', $noHp)) {
    $errors[] = "No. HP harus berupa angka 10-15 digit (boleh diawali +).";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;   
}

if (!isset($_SESSION['anggota'])) {
    $_SESSION['anggota'] = [];
}

$_SESSION['anggota'][] = [
    'nama' => $nama,
    'no_anggota' => $noAnggota,
    'alamat' => $alamat,
    'no_hp' => $noHp,
];

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
header('Location: list.php');
exit;