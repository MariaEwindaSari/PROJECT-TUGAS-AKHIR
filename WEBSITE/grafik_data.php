<?php
// File: grafik_data.php
include 'koneksi.php'; // Sertakan file koneksi ke database


$sql = "SELECT created_at, suhu, kelembapan, kelembapan_tanah FROM data_sensor ORDER BY created_at DESC LIMIT 5";
$result = $koneksi->query($sql);

// Siapkan array untuk data grafik
$data = array();
while ($row = $result->fetch_assoc()) {
    $created_at = $row['created_at'];
    $suhu = $row['suhu'];
    $kelembapan = $row['kelembapan'];
    $kelembapan_tanah = $row['kelembapan_tanah'];

    // Masukkan data ke dalam array
    $data[] = array(
        'created_at' => $created_at,
        'suhu' => floatval($suhu),
        'kelembapan' => floatval($kelembapan),
        'kelembapan_tanah' => floatval($kelembapan_tanah)
    );
}

// Ubah data ke format JSON
echo json_encode($data);

// Tutup koneksi database
$koneksi->close();
?>
