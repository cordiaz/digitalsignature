<?php
 
// database connection
$dbhost = 'localhost';
$dbuser = 't42590_ds';
$dbpass = '2RgH5e4TaUkz7MnT';
$db = 't42590_digitalsignature';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass , $db) or die($conn); 
 
// create function for generate random password
function generate_link($len = 8){
 $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
 $password = substr( str_shuffle( $chars ), 0, $len );
 return $password;
}
 
// insert into database
if(isset($_POST['signup'])) {
 $name=$_POST['name'];
 $email=$_POST['email'];
 $password = generate_link();
 $encpt_password= sha1($password);
 mysqli_query($conn, "insert into passwdgen (name, email, password) values ('$name', '$email', '$password')");
 echo "Your random link is: https://cordiaz.com/digitalsignature/detail.php?msg=".$password;
 echo "<br>";
 echo "Your random SHA1 is: ".$encpt_password;
 echo "<br>";
 echo "Your name is: ".$name;
 echo "<br>";
 echo "Your email is: ".$email;
}
 
?>