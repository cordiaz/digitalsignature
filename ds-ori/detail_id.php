<?php
    if(isset($_GET['id'])){
        $id    =$_GET['id'];
    }
    else {
        die ("Error. No id selected!");    
    }
    include "connect.php";
    $query    =mysqli_query($conn, "SELECT * FROM tamu WHERE id='$id'");
    $result    =mysqli_fetch_array($query);
?>
<html>
<head>
    <title>Detail Number Digital Signature</title>
</head>
<body>
    <h2>Detail Number Digital Signature</h2>
    <p><i>Note: Dibawah ini adalah Detail Number Digital Signature berdasarkan id</i> <b><?php echo $id?></b></p>
    <table border="0" cellpadding="4">
        <tr>
            <td size="90">Nama Klien</td>
            <td>: <?php echo $result['name']?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td>: <?php echo $result['email']?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>: <?php echo $result['address']?>, <?php echo $result['address']?></td>
        </tr>
        <tr>
            <td>Kota</td>
            <td>: <?php echo $result['city']?></td>
        </tr>
        <tr>
            <td>Angka Acak</td>
            <td>: <?php echo $result['msg']?></td>
        </tr>

        <tr height="40">
            <td></td>
            <td>   <a href="./">Kembali</a></td>
        </tr>
    </table>
</body>
</html>