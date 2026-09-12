<?php

// database connection
include __DIR__ . '/../connect.php';

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
 $stmt = $conn->prepare("insert into passwdgen (name, email, password) values (?, ?, ?)");
 $stmt->bind_param("sss", $name, $email, $password);
 $stmt->execute();
 echo "Your random number is: <br>".htmlspecialchars($password);
 echo "<br>";
 echo "Your random number is: <br>".htmlspecialchars($encpt_password);
 echo "<br>";
 echo "Your random number is: <br>".htmlspecialchars($name);
 echo "<br>";
 echo "Your random number is: <br>".htmlspecialchars($email);
}
 
?>