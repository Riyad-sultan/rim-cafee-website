<?php
session_start();
include 'includes/db_connect.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: admin/login.php?msg=please_login");
    exit();

}

if(isset($_POST['submit_order'])){
    $uid = $_SESSION['user_id'];
    $customer_name = mysqli_real_escape_string($conn,$_POST['customer_name']);
    $customer_phone = mysqli_real_escape_string($conn,$_POST['customer_phone']);
    $notes = mysqli_real_escape_string($conn,$POST['notes']);
    $item_ids = $_POST['item_ids'];
    $quantities = $_POST['quantities'];

    // CALCUALTE TOTAL BILL
    $total_bill = 0;
    $items_to_save =[];

    foreach($item_ids as $id){
        $id = (int)$id;
        $qty = (int)$quantities[$id];
         if ($qty > 0) {
            $res = mysqli_query($conn, "SELECT price FROM menu WHERE id = $id");
            $item = mysqli_fetch_assoc($res);
            $total_bill += ($item['price'] * $qty);
            $items_to_save[] = ['id' => $id, 'qty' => $qty];
        }
    }

    // 2. CHECK & SUBTRACT BALANCE
    $user_res = mysqli_query($conn, "SELECT balance FROM users WHERE id = '$uid'");
    $user_data = mysqli_fetch_assoc($user_res);

    if ($user_data['balance'] >= $total_bill) {
        // A. Subtract the money from the user
        $new_balance = $user_data['balance'] - $total_bill;
        mysqli_query($conn, "UPDATE users SET balance = '$new_balance' WHERE id = '$uid'");

        // B. Insert the orders
        $success = false;
        foreach ($items_to_save as $item) {
            $id = $item['id'];
            $qty = $item['qty'];
            
            // Note: We are using the columns your database expects
            $query = "INSERT INTO orders (user_id, item_id, customer_name, customer_phone, quantity, notes, status) 
                      VALUES ('$uid', '$id', '$customer_name', '$customer_phone', '$qty', '$notes', 'Preparing')";
            
            if(mysqli_query($conn, $query)) {
                $success = true;
            } else {
                // If it fails again, it means the column user_id REALLY isn't there
                die("Database Error: " . mysqli_error($conn));
            }
        }

        if ($success) {
            echo "<script>
                    alert('Order Successful! $" . number_format($total_bill, 2) . " deducted from your balance.');
                    window.location.href='index.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Insufficient Balance!');
                window.location.href='menu.php';
              </script>";
    }
} else {
    header("Location: menu.php");
    exit();
}
?>