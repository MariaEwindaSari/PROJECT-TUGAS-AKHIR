<?php
include "koneksi.php"; // Pastikan file koneksi.php tersedia dan berisi koneksi ke database
// Jika ada request POST dari ESP8266 (untuk mengirim data dari ESP8266 ke database tabel data_sensor)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari POST request
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['suhu']) && isset($data['kelembapan']) && isset($data['kelembapan_tanah'])) {
        $suhu = $data['suhu'];
        $kelembapan = $data['kelembapan'];
        $kelembapan_tanah = $data['kelembapan_tanah'];

        // Simpan data ke database
        $sql = "INSERT INTO data_sensor (suhu, kelembapan, kelembapan_tanah) VALUES ('$suhu', '$kelembapan', '$kelembapan_tanah')";
        if ($koneksi->query($sql) === TRUE) {
            echo "Data berhasil disimpan ke database";
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    } else {
        echo "Data POST tidak lengkap";
    }
}
// Jika ada request GET (untuk mengambil data pompa_on dan pompa_off dari database tabel kontrol_pompa ke ESP8266)
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Query untuk mengambil nilai pompa_on dan pompa_off dari tabel kontrol_pompa
    $sql = "SELECT pompa_on, pompa_off FROM kontrol_pompa ORDER BY id DESC LIMIT 1"; // Ambil data terbaru

    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pompa_on = $row["pompa_on"];
        $pompa_off = $row["pompa_off"];

        // Formatkan respons sebagai JSON
        $response = [
            "pompa_on" => $pompa_on,
            "pompa_off" => $pompa_off
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo "0 results";
    }
}

$koneksi->close(); 
?>
