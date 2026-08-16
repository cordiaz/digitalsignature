<?php
$con = mysqli_connect("localhost","t42590_ds","2RgH5e4TaUkz7MnT","t42590_digitalsignature");

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

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