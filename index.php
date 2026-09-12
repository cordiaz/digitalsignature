<?php
    session_start();
    if (!isset($_SESSION['username'])){
        header("Location: login.php");
        exit;
    }
?>
<html>
<head>
<title>Input Digital Signature</title>
</head>
	<style>
.content {
  max-width: 640px;
  margin: auto;
}
</style>
<body>
	<div class="content">
<h2>Detail Digital Signature</h2>
<hr size="1">
<form name="tamu" method="post" action="generate_link.php">
	<table>
		<tr><td>Nama Klien</td>
			<td><input  style="margin: 5px 5px 5px 24px;" type="text" name="name"></td></tr>
		<tr><td>Email</td>
			<td><input style="margin: 5px 5px 5px 24px;" type="text" name="email"></td></tr>
		<tr><td>Keterangan</td><td><input style="margin: 5px 5px 5px 24px;" type="text" name="address"></td></tr>
		<tr><td>Kota</td><td><input style="margin: 5px 5px 5px 24px;" type="text" name="city"></td></tr>
		<tr><td><input type="reset" name="reset" value="Reset"> <input type="submit" name="submit" value="Send"></td><td></td></tr>
	</table>
</form>
		<a href="action-logout.php">Logout</a>
		</div>
</body>
</html>