<?php
include "koneksi.php";

// Proses update jika form disubmit
if(isset($_POST['ubah'])) {
    $pompa_on = $_POST['pompa_on'];
    $pompa_off = $_POST['pompa_off'];

    $sql = "UPDATE kontrol_pompa SET pompa_on='$pompa_on', pompa_off='$pompa_off' WHERE id=1";

    if ($koneksi->query($sql) === TRUE) {
        // Redirect to the same page after successful update
        echo '<script>alert("Data berhasil disimpan");</script>';

        // Redirect to the same page after successful update
        echo '<script>window.location.href = "index.php?page=kontrol_pompa";</script>';
        exit(); 
    } else {
        echo json_encode(array("message" => "Error: " . $sql . "<br>" . $koneksi->error));
    }
}

// Ambil data pompa dari database
$pompa = mysqli_query($koneksi, "SELECT * FROM kontrol_pompa");
while($data = mysqli_fetch_array($pompa)){
?>
<div class="col-md-6 offset-md-3">
    <div class="card mb-4">
        <div class="card-header bg">
            Kontrol Pompa Air
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="pompa_on">Pompa Hidup Kelembapan</label>
                    <input type="text" name="pompa_on" id="pompa_on" class="form-control" value="<?php echo $data['pompa_on']; ?>">
                </div>
                <div class="form-group">
                    <label for="pompa_off">Pompa Mati Kelembapan</label>
                    <input type="text" name="pompa_off" id="pompa_off" class="form-control" value="<?php echo $data['pompa_off']; ?>">
                </div>

                <div class="d-flex justify-content-left">
                    <button type="submit" name="ubah" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
}

$koneksi->close();
?>
