<?php
include '../includes/db_connect.php';
$id = $_GET['id'];
$res = mysqli_query($conn, "SELECT * FROM menu WHERE id=$id");
$data = mysqli_fetch_assoc($res);

if(isset($_POST['update'])) {
    $n = $_POST['name'];
    $p = $_POST['price'];
    $c = $_POST['category'];

    $sql = "UPDATE menu SET name='$n', price='$p', category='$c' WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        header("Location: admin_dashboard.php");
    }
}
?>
<body style="background: #0f0f0f; color: white; font-family: sans-serif;">
    <div class="glass-card" style="width: 400px; margin: auto; margin-top: 100px;">
        <form method="POST">
            <h3>Update Item</h3>
            <input type="text" name="name" value="<?php echo $data['name']; ?>" style="width:100%; margin-bottom:10px;"><br>
            <input type="text" name="price" value="<?php echo $data['price']; ?>" style="width:100%; margin-bottom:10px;"><br>
            <input type="text" name="category" value="<?php echo $data['category']; ?>" style="width:100%; margin-bottom:10px;"><br>
            <button type="submit" name="update" class="btn-smart">Save Changes</button>
        </form>
    </div>
</body>