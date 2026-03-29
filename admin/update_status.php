<?php
include '../includes/db_connect.php';

// HANDLE UPDATE STATUS
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $query = "UPDATE orders SET status = '$status' WHERE id = $id";
    mysqli_query($conn, $query);
}

// HANDLE DELETE
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $query = "DELETE FROM orders WHERE id = $delete_id";
    mysqli_query($conn, $query);
}

// Redirect back to the view page
header("Location: view_orders.php");
exit();
?>