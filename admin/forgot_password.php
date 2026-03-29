<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'] . '/rim_caffe/';
include($root . 'includes/db_connect.php');

if (isset($_POST['reset_password'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_pass = $_POST['new_password']; // In production, use password_hash()

    // Verify that the username and email match a record in the database
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$user' AND email='$email'");

    if (mysqli_num_rows($check) > 0) {
        // Update the password
        $update = mysqli_query($conn, "UPDATE users SET password='$new_pass' WHERE username='$user'");
        if ($update) {
            header("Location: login.php?msg=account_created"); // Reuse account_created msg or make a new one
            exit();
        } else {
            $error = "Error updating password.";
        }
    } else {
        $error = "Username and Email do not match our records.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rim Caffe | Reset Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            background: radial-gradient(circle at center, #1a1a1a 0%, #0b0b0b 100%); 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
            font-family: 'Poppins', sans-serif; 
        }
        .login-card { 
            width: 380px; 
            padding: 50px 40px; 
            text-align: center; 
            border-radius: 25px; 
            background: rgba(255, 255, 255, 0.03); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(15px); 
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5); }
        .login-logo { 
            color: var(--gold); 
            font-family: 'Playfair Display', serif; 
            font-size: 2.2rem; 
            margin-bottom: 10px; 
            display: block; 
        }
        .input-group { 
            position: relative; 
            margin-bottom: 20px; 
        }
        .input-group i { 
            position: absolute; 
            left: 15px; top: 50%; 
            transform: translateY(-50%); 
            color: var(--gold); 
        }
        .input-group input { 
            width: 100%; 
            padding: 14px 15px 14px 45px; 
            background: rgba(255, 255, 255, 0.05); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            border-radius: 12px; 
            color: white; 
            outline: none; 
            box-sizing: border-box; 
        }
        .btn-smart { 
            width: 100%; 
            padding: 15px; 
            border: none; 
            border-radius: 12px; 
            font-weight: 600; 
            text-transform: uppercase; 
            cursor: pointer; 
            background: var(--gold); 
            color: #000; 
        }
        .error-msg { 
            background: rgba(231, 76, 60, 0.1); 
            color: #e74c3c; 
            padding: 10px; 
            border-radius: 8px; 
            margin-top: 20px; 
            font-size: 0.85rem; 
            border: 1px solid rgba(231, 76, 60, 0.2); 
        }
    </style>
</head>
<body>
    <div class="login-card">
        <span class="login-logo">Reset Password</span>
        <p style="color: #888; margin-bottom: 30px; font-size: 0.9rem;">Verify your details to set a new password</p>
        
        <form method="POST">
            <div class="input-group"><i class="fas fa-user"></i><input type="text" name="username" placeholder="Username" required></div>
            <div class="input-group"><i class="fas fa-envelope"></i><input type="email" name="email" placeholder="Registered Email" required></div>
            <div class="input-group"><i class="fas fa-key"></i><input type="password" name="new_password" placeholder="New Password" required></div>
            <button type="submit" name="reset_password" class="btn-smart">Update Password</button>
        </form>

        <?php if(isset($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <p style="margin-top: 25px; font-size: 0.9rem; color: #666;">
            Remembered it? <a href="login.php" style="color: var(--gold); text-decoration: none;">Back to Login</a>
        </p>
    </div>
</body>
</html>