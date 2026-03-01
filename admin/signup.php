<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$root = $_SERVER['DOCUMENT_ROOT'] . '/rim_caffe/';
include($root . 'includes/db_connect.php');

$error = ""; 


if (isset($_POST['signup'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = $_POST['password']; 

    // Check if username already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    if(mysqli_num_rows($check) > 0) {
        $error = "Username already taken!";
    } else {
        // Insert as 'customer'
        $query = "INSERT INTO users (username, email, password, role) VALUES ('$user', '$email', '$pass', 'customer')";
        
        if(mysqli_query($conn, $query)) {
            header("Location: login.php?msg=account_created");
            exit();
        } else {
            $error = "Registration failed: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rim  Caffe  | Join Us</title>
     <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .auth-screen {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle at center, #1a1a1a 0%, #050505 100%);
            padding: 20px;
        }
        .auth-card-custom {
            width: 100%;
            max-width: 420px;
            text-align: center;
        }
        .status-box {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .status-error { background: rgba(231, 76, 60, 0.1); color: #e74c3c; border: 1px solid rgba(231, 76, 60, 0.2); }
        
        /* Matching the Contact Page Labels */
        .auth-card-custom label {
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gold);
            font-weight: 600;
        }
        .auth-card-custom input {
            background: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            margin-top: 5px;
            color: white;
        }
    </style>
</head>
<body class="about-page-body">

    <div class="auth-screen">
        <div class="glass-card auth-card-custom">
            <h1 class="premium-title" style="font-size: 2.5rem; margin-bottom: 5px;">Rim Caffe</h1>
            <p style="letter-spacing: 3px; color: #888; text-transform: uppercase; font-size: 0.7rem; margin-bottom: 30px;">Create Member Account</p>

            <form method="POST">
                <div style="text-align: left;">
                    <label><i class="fas fa-user"></i> Chosen Username</label>
                    <input type="text" name="username" placeholder="Username" required autocomplete="off">
                    
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" name="email" placeholder="email@example.com" required>

                    <label><i class="fas fa-lock"></i> Security Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" name="signup" class="btn-smart" style="width: 100%; margin-top: 35px;">
                    Register Now <i class="fas fa-user-plus" style="font-size: 0.8rem; margin-left: 10px;"></i>
                </button>
            </form>

            <?php if(!empty($error)): ?>
                <div class="status-box status-error" style="margin-top: 20px;">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div style="margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 20px;">
                <p style="font-size: 0.9rem; color: #777;">
                    Already a member? <a href="login.php" style="color: var(--gold); text-decoration: none; font-weight: 600;">Login here</a>
                </p>
                <a href="../index.php" style="display: inline-block; margin-top: 15px; color: #555; text-decoration: none; font-size: 0.8rem;">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
    
</body>
</html>