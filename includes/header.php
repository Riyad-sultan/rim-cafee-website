<?php
if(session_status() === PHP_SESSION_NONE) {sessio_start();}
require_once __DIR__ . '/db_connect.php';
if (isset($_SESSION['user_id']) && isset($conn)) {
    $uid = $_SESSION['user_id'];
    $sync_query = "SELECT username, balance, role FROM users WHERE id = '$uid'";
    $sync_result = mysqli_query($conn, $sync_query);
    if ($sync_result && mysqli_num_rows($sync_result) > 0) {
        $user_db_data = mysqli_fetch_assoc($sync_result);
        $_SESSION['username'] = $user_db_data['username'];
        $_SESSION['user_balance'] = $user_db_data['balance'];
        $_SESSION['role'] = $user_db_data['role'];
    }
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<header class="navbar navbar-expand-lg navbar-dark bg-dark-custom shadow-sm sticky-top">
    <div class="container-fluid px-lg-5">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/images/logo.jpg" alt="Logo" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
            <span class="logo-text fw-bold">Rim Café</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#rimMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="rimMenu">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-center">
                <li class="nav-item"><a class="nav-link px-3" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="about.php">About</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="menu.php">Menu</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="contact.php">Contact</a></li>
                <?php if(isset($_SESSION['username'])): ?>
                    <li class="nav-item d-lg-none">
                         <a class="nav-link text-warning" href="recharge.php"><i class="fas fa-plus-circle"></i> Recharge</a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="d-flex flex-column flex-lg-row align-items-center gap-3 nav-right-bootstrap">
                <?php if(isset($_SESSION['username'])): ?>
                    <a href="recharge.php" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                        <i class="fas fa-wallet me-1"></i> $<?php echo number_format($_SESSION['user_balance'], 2); ?>
                    </a>

                    <div class="text-white small d-none d-xl-block">
                        <i class="fas fa-user-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </div>

                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <a href="admin/admin_dashboard.php" class="btn btn-sm btn-outline-info">Admin</a>
                    <?php endif; ?>

                    <a href="admin/logout.php" class="text-danger" onclick="return confirm('Exit Rim Café?')">
                        <i class="fas fa-power-off"></i>
                    </a>
                <?php else: ?>
                    <a href="admin/login.php" class="btn btn-outline-light btn-sm px-4">Login</a>
                    <a href="admin/signup.php" class="btn btn-warning btn-sm px-4 fw-bold">Join Now</a>
                <?php endif; ?>
            </div>
        </div>