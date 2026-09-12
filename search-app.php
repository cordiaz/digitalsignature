<?php
include "connect.php";
$con = $conn;

// Perform query
//if ($result = mysqli_query($con, "SELECT * FROM tamu")) {
//  echo "Returned rows are: " . mysqli_num_rows($result);
  // Free result set
//  mysqli_free_result($result);
// }

if ($result = mysqli_query($con, "SELECT * FROM tamu where name like '%unpam%' ")) {
  echo "Returned rows are: " . mysqli_num_rows($result);
  // Free result set
  mysqli_free_result($result);
}

mysqli_close($con);
?>