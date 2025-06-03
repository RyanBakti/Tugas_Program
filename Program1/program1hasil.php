<?php
session_start();

// Ambil data dari URL parameter
$nama = isset($_GET['nama']) ? htmlspecialchars($_GET['nama']) : '';
$nilai = isset($_GET['nilai']) ? (float)$_GET['nilai'] : 0;
$status = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : '';

// Jika tidak ada data, redirect ke index
if (empty($nama) || empty($status)) {
    header("Location: program1.html");
    exit();
}

// Handle clear history
if (isset($_POST['clear_history'])) {
    unset($_SESSION['riwayat']);
    header("Location: program1hasil.php?" . $_SERVER['QUERY_STRING']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Penilaian - <?php echo $nama; ?></title>
    <link rel="stylesheet" href="program1.css">
</head>
<body>
    <div class="container">
        <h1>📊 Hasil Penilaian</h1>
        
        <div class="result">
            <h3>Detail Hasil</h3>
            <p><strong>Nama Siswa:</strong> <?php echo $nama; ?></p>
            <p><strong>Nilai:</strong> <?php echo number_format($nilai, 1); ?></p>
            <p><strong>Status:</strong> 
                <span class="<?php echo ($status == 'LULUS') ? 'status-lulus' : 'status-tidak-lulus'; ?>">
                    <?php echo $status; ?>
                </span>
            </p>
            
            <?php if ($status == 'LULUS'): ?>
                <div class="success-message">
                    🎉 Selamat! <?php echo $nama; ?> berhasil lulus ujian dengan nilai <?php echo $nilai; ?>.
                </div>
            <?php else: ?>
                <div class="error-message">
                    📚 <?php echo $nama; ?> perlu mengikuti program remedial untuk mencapai nilai kelulusan (≥70).
                </div>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="program1.html">
                <button type="button">🔄 Input Data Baru</button>
            </a>
        </div>
        
        <!-- Riwayat Penilaian -->
        <?php if (isset($_SESSION['riwayat']) && !empty($_SESSION['riwayat'])): ?>
            <div style="margin-top: 30px; padding: 20px; background-color: #fafafa; border-radius: 10px;">
                <h3>📋 Riwayat Penilaian</h3>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Nilai</th>
                            <th>Status</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['riwayat'] as $index => $data): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($data['nama']); ?></td>
                                <td style="text-align: center;"><?php echo $data['nilai']; ?></td>
                                <td style="text-align: center;">
                                    <span class="<?php echo ($data['status'] == 'LULUS') ? 'status-lulus' : 'status-tidak-lulus'; ?>">
                                        <?php echo $data['status']; ?>
                                    </span>
                                </td>
                                <td style="text-align: center; font-size: 12px;"><?php echo $data['waktu']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <form method="POST" style="margin-top: 15px;">
                    <button type="submit" name="clear_history" value="1" class="btn-warning">
                        🗑️ Hapus Riwayat
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>