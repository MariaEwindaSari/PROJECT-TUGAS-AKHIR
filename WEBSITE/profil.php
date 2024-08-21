<?php
include "koneksi.php";
// Periksa apakah pengguna sudah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION['login'])) {
    header("location: login.php");
    exit();
}

// Ambil username dari session
$id = $_SESSION['id'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$nama_lengkap = $_SESSION['nama_lengkap'];
$avatar = $_SESSION['avatar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update nama lengkap jika diubah
    if (!empty($_POST['nama_lengkap'])) {
        $new_nama_lengkap = $_POST['nama_lengkap'];
        $query_update_nama_lengkap = "UPDATE user SET nama_lengkap = '$new_nama_lengkap' WHERE id = '$id'";
        mysqli_query($koneksi, $query_update_nama_lengkap);
        $_SESSION['nama_lengkap'] = $new_nama_lengkap; // Update session juga jika perlu
        $nama_lengkap = $new_nama_lengkap; // Update variabel $nama_lengkap untuk tampilan form
    }

    // Update username jika diubah
    if (!empty($_POST['username'])) {
        $new_username = $_POST['username'];
        $query_update_username = "UPDATE user SET username = '$new_username' WHERE id = '$id'";
        mysqli_query($koneksi, $query_update_username);
        $_SESSION['username'] = $new_username; // Update session juga jika perlu
        $username = $new_username; // Update variabel $username untuk tampilan form
    }

    // Update password jika diubah
    if (!empty($_POST['password'])) {
        $new_password = $_POST['password'];
        // Enkripsi password baru jika diperlukan, misalnya menggunakan password_hash
        
        $query_update_password = "UPDATE user SET password = '$new_password' WHERE id = '$id'";
        mysqli_query($koneksi, $query_update_password);
    }
}

$upload_dir = 'assets/'; // Direktori tempat menyimpan file upload

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $avatar_name = $_FILES['avatar']['name'];
    $avatar_tmp = $_FILES['avatar']['tmp_name'];
    $avatar_size = $_FILES['avatar']['size'];
    $avatar_error = $_FILES['avatar']['error'];

    // Ambil ekstensi file
    $avatar_ext = strtolower(pathinfo($avatar_name, PATHINFO_EXTENSION));

    // Ekstensi file yang diperbolehkan untuk diupload
    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');

    // Validasi ekstensi file
    if (in_array($avatar_ext, $allowed_extensions)) {
        // Validasi ukuran file (misalnya maksimal 2MB)
        if ($avatar_size <= 2 * 1024 * 1024) {
            // Generate nama unik untuk file
            $avatar_new_name = uniqid('avatar_') . '.' . $avatar_ext;

            // Pindahkan file ke direktori uploads
            if (move_uploaded_file($avatar_tmp, $upload_dir . $avatar_new_name)) {
                // Simpan lokasi file ke dalam session
                $_SESSION['avatar'] = $upload_dir . $avatar_new_name;
                // Beri pesan sukses jika perlu
                $avatar_path = $upload_dir . $avatar_new_name;
                $query_update_avatar = "UPDATE user SET avatar = '$avatar_path' WHERE id = '$id'";
                mysqli_query($koneksi, $query_update_avatar);
                $upload_success = "File avatar berhasil diunggah.";
            } else {
                $upload_error = "Maaf, terjadi kesalahan saat mengunggah file.";
            }
        } else {
            $upload_error = "Ukuran file terlalu besar. Maksimal 2MB yang diizinkan.";
        }
    } else {
        $upload_error = "Ekstensi file tidak diizinkan. Hanya JPG, JPEG, PNG, dan GIF yang diperbolehkan.";
    }
}

?>  
<section style="background-color: #eee;">
  <div class="container py-5">

    <div class="row">
      <div class="col-lg-4">
        <div class="card mb-4">
          <div class="card-body text-center">
          <?php if (isset($_SESSION['avatar'])) : ?>
                        <img src="<?php echo $_SESSION['avatar']; ?>" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <?php else : ?>
                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <?php endif; ?>
                        <?php if (isset($upload_success)) : ?>
                            <div class="alert alert-success"><?php echo $upload_success; ?></div>
                        <?php endif; ?>
                        <?php if (isset($upload_error)) : ?>
                            <div class="alert alert-danger"><?php echo $upload_error; ?></div>
                        <?php endif; ?>
            <h5 class="my-3"><?php echo $_SESSION['nama_lengkap']; ?>   </h5>
          </div>
        </div>
      </div>
    
      <div class="col-lg-8">
        <div class="card mb-4">
        <div class="card-header">
        <h2>Profil</h2>
        </div>
          <div class="card-body">
          <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $nama_lengkap; ?>">
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" class="form-control" id="password" name="password" value="<?php echo $password; ?>"  >
                            </div>
                            <div class="form-group">
                                <label for="avatar">Upload Foto Profil</label>
                                <input type="file" class="form-control-file" id="avatar" name="avatar">
                            </div>
                            <div class="d-flex justify-content-left">
                                <button type="submit" name="ubah" class="btn btn-primary">Simpan</button>
                            </div>
                            <?php if (isset($upload_success)) : ?>
                                <div class="alert alert-success mt-3"><?php echo $upload_success; ?></div>
                            <?php endif; ?>
                            <?php if (isset($upload_error)) : ?>
                                <div class="alert alert-danger mt-3"><?php echo $upload_error; ?></div>
                            <?php endif; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>