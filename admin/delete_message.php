<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include __DIR__ . '/../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Make sure the table name 'contact_messages' is correct in your DB
    $delete_query = "DELETE FROM contact_messages WHERE id = '$id'";
    
    if (mysqli_query($conn, $delete_query)) {
        // Success: Go back to inbox with a success notice (optional)
        header("Location: inbox.php?msg=deleted");
    } else {
        // Error: Show the error or redirect
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: inbox.php");
}
exit();
?>