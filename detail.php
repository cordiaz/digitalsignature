<?php
    if(isset($_GET['msg'])){
        $msg    =$_GET['msg'];
    }
    else {
        die ("Error. No msg selected!");    
    }
    include "connect.php";
    $query    =mysqli_query($conn, "SELECT * FROM tamu WHERE msg='$msg'");
    $result   =mysqli_fetch_array($query);
?>
<html>
<head>
    <title>Detail Number Digital Signature</title>
</head>
	<style>
.content {
  max-width: 640px;
  margin: auto;
}
</style>
<body>
	<div class="content">
    <h2>Detail Number Digital Signature</h2>
		<hr size="1">
    <p><i>Note: Di bawah ini adalah Detail Number Digital Signature berdasarkan msg</i> - <b><?php echo $msg?></b></p>
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
            <td>: <?php echo $result['address']?></td>
        </tr>
        <tr>
            <td>Kota</td>
            <td>: <?php echo $result['city']?></td>
        </tr>
        <tr>
            <td>URL</td>
			<td>: <b>https://cordiaz.com/digitalsignature/detail.php?msg=<?php echo $result['msg']?></b></td>
        </tr>
		<tr>
            <td>Timestamp</td>
			<td>: <?php echo $result['timestamp']?></td>
        </tr>
        <tr height="40">
            <td></td>
            <td>   <!--- <a href="./">Kembali</a> ---></td>
        </tr>
    </table>
		</div>
</body>
</html>