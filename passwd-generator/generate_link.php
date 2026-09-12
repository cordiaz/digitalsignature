<?php

// database connection
include __DIR__ . '/../connect.php';

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
 $stmt = $conn->prepare("insert into passwdgen (name, email, password) values (?, ?, ?)");
 $stmt->bind_param("sss", $name, $email, $password);
 $stmt->execute();
 $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
 $base_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
 echo "Your random link is: " . htmlspecialchars($base_url) . "/detail.php?msg=".htmlspecialchars($password);
 echo "<br>";
 echo "Your random SHA1 is: ".htmlspecialchars($encpt_password);
 echo "<br>";
 echo "Your name is: ".htmlspecialchars($name);
 echo "<br>";
 echo "Your email is: ".htmlspecialchars($email);
}
 
?>