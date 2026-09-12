<?php
    if(isset($_GET['msg'])){
        $msg    =$_GET['msg'];
    }
    else {
        die ("Error. No msg selected!");    
    }
    include "connect.php";
    $stmt = $conn->prepare("SELECT * FROM tamu WHERE msg = ?");
    $stmt->bind_param("s", $msg);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_array();

    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $base_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<html>
<head>
    <title>Detail Number Digital Signature</title>
</head>
<body>
    <h2>Detail Number Digital Signature</h2>
    <p><i>Note: Di bawah ini adalah Detail Number Digital Signature berdasarkan msg</i> - <b><?php echo htmlspecialchars($msg)?></b></p>
    <table border="0" cellpadding="4">
        <tr>
            <td size="90">Nama Klien</td>
            <td>: <?php echo htmlspecialchars($result['name'])?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td>: <?php echo htmlspecialchars($result['email'])?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>: <?php echo htmlspecialchars($result['address'])?>, <?php echo htmlspecialchars($result['address'])?></td>
        </tr>
        <tr>
            <td>Kota</td>
            <td>: <?php echo htmlspecialchars($result['city'])?></td>
        </tr>
        <tr>
            <td>URL</td>
			<td>: <b><?php echo htmlspecialchars($base_url)?>/detail.php?msg=<?php echo htmlspecialchars($result['msg'])?></b></td>
        </tr>
		<tr>
            <td>Timestamp</td>
			<td>: <?php echo htmlspecialchars($result['timestamp'])?></td>
        </tr>
        <tr height="40">
            <td></td>
            <td>   <a href="./">Kembali</a></td>
        </tr>
    </table>
</body>
</html>