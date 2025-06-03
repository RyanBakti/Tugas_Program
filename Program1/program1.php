<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk membersihkan input
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk validasi input
function validateInput($nama, $nilai) {
    $errors = array();
    
    if (empty($nama)) {
        $errors[] = "Nama tidak boleh kosong!";
    } elseif (strlen($nama) < 2) {
        $errors[] = "Nama minimal 2 karakter!";
    }
    
    if (empty($nilai)) {
        $errors[] = "Nilai tidak boleh kosong!";
    } elseif (!is_numeric($nilai)) {
        $errors[] = "Nilai harus berupa angka!";
    } elseif ($nilai < 0 || $nilai > 100) {
        $errors[] = "Nilai harus antara 0-100!";
    }
    
    return $errors;
}

// Fungsi untuk menentukan status kelulusan
function getStatus($nilai) {
    return ($nilai >= 70) ? "LULUS" : "TIDAK LULUS";
}

// Fungsi untuk menyimpan ke riwayat
function saveToHistory($nama, $nilai, $status) {
    if (!isset($_SESSION['riwayat'])) {
        $_SESSION['riwayat'] = array();
    }

    array_unshift($_SESSION['riwayat'], array(
        'nama' => $nama,
        'nilai' => $nilai,
        'status' => $status,
        'waktu' => date('d/m/Y H:i:s')
    ));
    
    // Batasi riwayat maksimal 10 data
    if (count($_SESSION['riwayat']) > 10) {
        array_pop($_SESSION['riwayat']);
    }
}

// Proses form jika ada data POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = cleanInput($_POST['nama']);
    $nilai = cleanInput($_POST['nilai']);
    
    // Validasi input
    $errors = validateInput($nama, $nilai);
    
    if (empty($errors)) {
        $nilai = (float)$nilai;
        $status = getStatus($nilai);
        
        // Simpan ke riwayat
        saveToHistory($nama, $nilai, $status);
        
        // Redirect ke halaman hasil dengan data
        $params = http_build_query(array(
            'nama' => $nama,
            'nilai' => $nilai,
            'status' => $status,
            'grade' => $grade
        ));
        
        header("Location: program1hasil.php?" . $params);
        exit();
    } else {
        // Jika ada error, redirect kembali dengan pesan error
        $_SESSION['errors'] = $errors;
        $_SESSION['old_nama'] = $nama;
        $_SESSION['old_nilai'] = $nilai;
        header("Location: program1.html");
        exit();
    }
} else {
    // Jika tidak ada POST data, redirect ke index
    header("Location: program1.html");
    exit();
}
?>