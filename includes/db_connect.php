<?php
$host= "";
$user="";
$pass ="";
$db = "rim_caffe";
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn){
    die("Database connection Error : " .mysqli_connect_error());
}
?>