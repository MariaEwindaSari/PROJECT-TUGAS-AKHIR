<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Sensor</h6>
    </div>
    <div class="card-body">
         <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Suhu</th>
                    <th>Kelembapan</th>
                    <th>Kelembapan Tanah</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                include 'koneksi.php';
                $no=1;
                $data = mysqli_query($koneksi,"SELECT * FROM data_sensor ORDER BY created_at DESC");
                while($tampil = mysqli_fetch_array($data)) {
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $tampil['suhu']; ?></td>
                    <td><?php echo $tampil['kelembapan']; ?></td>
                    <td><?php echo $tampil['kelembapan_tanah']; ?></td>
                    <td><?php echo $tampil['created_at']; ?></td>
                    <td>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalHapus<?php echo $tampil['id']; ?>">
                    Hapus
                    </button>
                    <!-- Modal Hapus-->
                    <div class="modal fade" id="modalHapus<?php echo $tampil['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalHapusLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title fw-bold" style="color: white;" id="modalHapusLabel">Hapus Data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST">
                                    <div class="form-group">
                                        <input type="hidden" name="id" value="<?php echO $tampil['id'] ?>">
                                        Apakah anda yakin ingin menghapus data <?php echO $tampil['created_at'] ?> ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                        <button type="submit" class="btn btn-warning" name="hapus">Iya</button>
                                    </div>
                                    <?php 
                                    if(isset($_POST['hapus'])){
                                        $id = $_POST['id'];
                                        $sql = $koneksi->query("DELETE FROM data_sensor WHERE id='$id'");
                                        if($sql){
                                            ?>
                                            <script>
                                                alert("Data Berhasil Dihapus");
                                                window.location.href="?page=data_sensor";
                                            </script>
                                            <?php
                                        }
                                    }
                                    ?>
                                </form>
                                
                            </div>
                        
                        </div>
                    </div>
                    </div>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalDetail<?php echo $tampil['id']; ?>">
                    Detail
                    </button>
                    <div class="modal fade" id="modalDetail<?php echo $tampil['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title fw-bold" style="color: white;" id="modalDetailLabel">Detail Data Sensor</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col sm-5">
                                    Tanggal <br/>
                                    Suhu    <br/>
                                    Kelembapan <br/>
                                    Kelembapan Tanah <br/>
                                </div>
                                <div class="col-sm-8">
                                    : <?php echo $tampil['created_at'] ?> <br/>
                                    : <?php echo $tampil['suhu'] ?> <br/>
                                    : <?php echo $tampil['kelembapan'] ?> <br/>
                                    : <?php echo $tampil['kelembapan_tanah'] ?> <br/>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Keluar</button>
                        </div>
                        </div>
                    </div>
                    </div>
                    </td>
                </tr>
                <?php
                }       
                ?>                  
                </tbody>
            </table>
        </div>
    </div>
</div>