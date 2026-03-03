<?php
session_start();
require_once 'includes/db_connect.php';

// 1.Security Check: Must be logged in
if(!isset($_SESSION['user_id'])){
    header("Location: admin/login.php?msg=please_login");
    exit();
}

if(isset($_GET['item_id'] && isset($_GET['price']))){
    $uid = $_SESSION['user_id'];
    $item_id = mysqli_real_escape_string($conn,$GET['item_id']);
    $price = (float)$_GET['price'];

    // 2. Get current Balance from Database (Always trust Db over Session)
    $query = mysqli_query($conn,"SELECT balance FROM users WHERE id = $uid");
    $user = mysqli_fetch_assoc($query);

    if($user['balance'] >=$price){

    // 3. calculation
    $new_balance = $user['balance'] -$price;

    // 4. Update Database (Subtract Money)
    $update_sql = "UPDATE users SET balance = '$new_balance'WHERE id = '$uid'";

    if(mysqli_query($conn,$update_sql)){
        //5. Record the order in the database
      $order_sql = "INSERT INTO orders (user_id, item_id, status, order_date) 
                          VALUES ('$uid', '$item_id', 'Preparing', NOW())";
            mysqli_query($conn, $order_sql);

            // 6. Success! Redirect to order page
            header("Location: my_orders.php?status=succsess");
            exit();

    }
    }else{
        // Not enough money
        header("Location: menu.php?error=insufficient_funds");
        exit();
    }

}else{
    header("Location:menu.php");
    exit();
}
