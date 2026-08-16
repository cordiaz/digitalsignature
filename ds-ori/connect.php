<?php
$hostmysql = "localhost";
$username = "t42590_ds";
$password = "2RgH5e4TaUkz7MnT";
$database = "t42590_digitalsignature";
$conn = mysqli_connect ($localhost, $username, $password, $database);
if ($conn){
 echo "<b> Koneksi Berhasil </b>";
}
else{
 die ("<b> Koneksi Gagal </b>");
}
?>