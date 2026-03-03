<?php
// 1. Connect to Database
include 'includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Get and Sanitize Form Data
    // We use trim to remove accidental spaces
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // 3. Insert into the contact_messages table using Prepared Statements
    // This is safer and more professional than passing variables directly into the string
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as strings (ssss)
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // 4. Redirect back to contact.php with success status
            mysqli_stmt_close($stmt);
            header("Location: contact.php?status=success");
            exit();
        } else {
            // 5. Redirect back with error status if execution fails
            header("Location: contact.php?status=error");
            exit();
        }
    } else {
        // Redirect if the statement preparation fails
        header("Location: contact.php?status=error");
        exit();
    }
} else {
    // 6. Direct access protection
    header("Location: contact.php");
    exit();
}
?>