<?php
include 'includes/db_connect.php';
$msg = "";

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "Email already exists!";
    } else {
        $sql = "INSERT INTO users (name, email, password, phone, balance) VALUES ('$name', '$email', '$pass', '$phone', 500.00)";
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php?status=registered");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Rim Café | Premium Membership</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background: #0b0b0b; display: flex; align-items: center; justify-content: center; min-height: 100vh; font-family: 'Poppins'; }
        .reg-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(212,175,55,0.2); padding: 40px; border-radius: 30px; backdrop-filter: blur(20px); width: 400px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        h2 { font-family: 'Playfair Display'; color: #d4af37; text-align: center; margin-bottom: 30px; }
        input { width: 100%; padding: 12px; margin-bottom: 20px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; color: white; outline: none; }
        input:focus { border-color: #d4af37; }
        .btn-reg { width: 100%; padding: 12px; background: #d4af37; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-reg:hover { transform: scale(1.02); box-shadow: 0 0 20px rgba(212,175,55,0.4); }
    </style>
</head>
<body>
    <div class="reg-card">
        <h2>Create Account</h2>
        <?php if($msg) echo "<p style='color:#ff4d4d; text-align:center;'>$msg</p>"; ?>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <button type="submit" name="register" class="btn-reg">Register Now</button>
        </form>
        <p style="color: #888; text-align: center; margin-top: 20px; font-size: 0.9rem;">
            Already have an account? <a href="login.php" style="color: #d4af37;">Login</a>
        </p>
    </div>
</body>
</html>