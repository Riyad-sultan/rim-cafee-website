<?php
session_start();
include 'includes/db_connect.php';

if (isset($_POST['recharge']) && isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $amount = (float)$_POST['amount'];

    if ($amount > 0) {
        // Update the balance by ADDING the new amount
        $sql = "UPDATE users SET balance = balance + $amount WHERE id = '$uid'";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Success! $$amount has been added to your account.');
                    window.location.href='menu.php';
                  </script>";
        }
    }
}
?>