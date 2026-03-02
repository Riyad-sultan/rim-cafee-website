<?php
include '../includes/db_connect.php';
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM menu WHERE id=$id");
}
header("Location: dashboard.php");
?>