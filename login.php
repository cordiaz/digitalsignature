<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
		<style>
.content {
  max-width: 640px;
  margin: auto;
}
</style>
<body>
	<div class="content">
    <h2>Silahkan Login</h2>
		<hr size="1">
    <form action="action-login.php" method="post">
    <table>
        <tr>
            <td>Username</td><td><input style="margin: 5px 5px 5px 24px;" type="text" name="username"></td>
        </tr>
        <tr>
            <td>Password</td><td><input style="margin: 5px 5px 5px 24px;" type="password" name="password"></td>
        </tr>
        <tr>
            <td><input type="reset" value="Cancel"> <input type="submit" value="Login"></td><td></td>
        </tr>
    </table>
    </form>
		</div>
</body>
</html>