<?php
if(session_status() === PHP_SESSION-NONE) session_start();
$root =$SERVER ['DOCUMENT_ROOT'] .'/rim_caffe/'
require_once($root . 'include/db_connect.php');
$error ="";
if(isset($_POST['login'])){
    $user = mysqli_real_escape_string($conn,$_POST['usrname']);
    $pass = $_POST['password'];
    $sql ="SELECT * FROM user WHERE username='$user'";
    $result = mysqli_query($conn,$sql);

    if($row = mysqli_fetch_assoc($result)){
        if($pass == $row['password'] || password_verify($pass,$row['passowrd'])){
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['user_balance'] = $row['balance'];
            if($row['role'] == ' admin'){
                $_SESSION['admin'] =$row['username'];
                header("Location: admin_dashboard.php");
            }else{
                header("Location: ../menu.php");
            }
            exit();
        } else { $error = "Incorrect Password!"; }
    } else { $error = "User not found!"; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rim Caffe | portal Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="login-screen">
    <div class="login-card-custom glass-card">
        <div class="login-header">
            <h1 class="premium-title">Rim Caffe</h1>
            <p class="portal-subtitle">portal Login</p>

        </div>
        <?php if(isset($_GET['msg'])):?>
            <div class="status-box status-success">
                <i class="fas fa-info-circle"></i> 
                <span>
                     <?php 
                        if($_GET['msg'] == 'please_login') echo "Please login to continue.";
                        if($_GET['msg'] == 'logged_out') echo "Successfully logged out.";
                        if($_GET['msg'] == 'account_created') echo "Account ready! Please login.";
                    ?>

                </span>
            </div>
            <?php endif;?>
            <form action="" method= "POST">
                <div class="input-group">
                     <label><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="username" placeholder="Enter your username" required>
                    
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <div class="forgot-pass-wrapper">
                    <a href="forgot_password.php" class="forgot-link">Forgot Password?</a>
                </div>
                
                <button type="submit" name="login" class="btn-login-submit">
                    Sign In <i class="fas fa-chevron-right"></i>
                </button>
            </form>
            <?php if(!empty($error)):?>
                <div class="status-box status-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
             <div class="login-footer">
                <p>New here? <a href="signup.php">Create Account</a></p>
                <a href="../index.php" class="back-home">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>

    </div>
    
</body>
</html>