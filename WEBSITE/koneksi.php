<?php

$koneksi = new mysqli('localhost','root','','penyiram');
if($koneksi) {

}else{
    echo "coneksi gagal";
    exit;
}
?>