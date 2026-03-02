<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include __DIR__ . '/../includes/db_connect.php';

if (isset($_POST['add_item'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $cat = $_POST['category'];
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    
    // IMAGE UPLOAD LOGIC
    $img_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    
    // This path goes UP one level from 'admin', then into 'assets/images/items'
    $upload_path = "../assets/images/items/" . $img_name;

    if (move_uploaded_file($tmp_name, $upload_path)) {
        // Success: File moved to folder, now save name to Database
        $sql = "INSERT INTO menu (name, price, category, description, image) 
                VALUES ('$name', '$price', '$cat', '$desc', '$img_name')";
        mysqli_query($conn, $sql);
        header("Location: admin_dashboard.php");
    } else {
        echo "Failed to upload image. Make sure the 'items' folder exists!";
    }
}
?>

<div class="glass-card" style="max-width:500px; margin: 50px auto;">
    <h2 style="color:var(--gold);">Add New Dish</h2>
    <form method="POST" enctype="multipart/form-data"> <input type="text" name="name" placeholder="Dish Name" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="text" name="category" placeholder="Category (e.g. Coffee)">
        <textarea name="description" placeholder="Description"></textarea>
        
        <label style="display:block; margin-bottom:10px;">Upload Dish Photo:</label>
        <input type="file" name="image" accept="image/*" required>
        
        <button type="submit" name="add_item" class="btn-smart" style="width:100%;">Upload to Menu</button>
    </form>
</div>