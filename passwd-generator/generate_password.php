<?php
 
// database connection
$dbhost = 'localhost';
$dbuser = 't42590_ds';
$dbpass = '2RgH5e4TaUkz7MnT';
$db = 't42590_digitalsignature';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass , $db) or die($conn); 
 
// create function for generate random password
function generate_password($len = 8){
 $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
// timestamp belum dimasukkan
 $timestamp = date('Y-m-d H:i:s');
 $password = substr( str_shuffle( $chars ), 0, $len );
 return $password;
}
 
// insert into database
if(isset($_POST['signup'])) {
 $name=$_POST['name'];
 $email=$_POST['email'];
 $password = generate_password();
 $encpt_password= sha1($password);
 mysqli_query($conn, "insert into passwdgen (name, email, password) values ('$name', '$email', '$password')");
 echo "Your random number is: <br>".$password;
 echo "<br>";
 echo "Your random number is: <br>".$encpt_password;
 echo "<br>";
 echo "Your random number is: <br>".$name;
 echo "<br>";
 echo "Your random number is: <br>".$email;
}
 
?>