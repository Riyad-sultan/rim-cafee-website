<?php 
session_start();
include 'includes/db_connect.php'; 
include "includes/header.php"; 

if (!isset($_SESSION['user_id'])) {
    header("Location: admin/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Up Balance | Rim Caffe</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .recharge-card { max-width: 450px; margin: 80px auto; padding: 40px; text-align: center; }
        .amount-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 25px 0; }
        .amount-btn { 
            padding: 15px; background: rgba(212, 175, 55, 0.1); border: 1px solid var(--gold); 
            color: var(--gold); border-radius: 12px; cursor: pointer; font-weight: 600; transition: 0.3s;
        }
        .amount-btn:hover { background: var(--gold); color: #000; }
        .custom-input { width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid #444; border-radius: 10px; color: white; margin-bottom: 20px; text-align: center; font-size: 1.2rem; }
    </style>
</head>
<body>

<div class="recharge-card glass-card">
    <i class="fas fa-coins" style="font-size: 3rem; color: var(--gold); margin-bottom: 20px;"></i>
    <h2 style="color: #fff; font-family: 'Playfair Display';">Recharge Your Wallet</h2>
    <p style="color: #888; font-size: 0.9rem;">Add funds to continue your coffee journey.</p>

    <form action="process_recharge.php" method="POST">
        <div class="amount-grid">
            <button type="button" class="amount-btn" onclick="setAmount(10)">$10.00</button>
            <button type="button" class="amount-btn" onclick="setAmount(25)">$25.00</button>
            <button type="button" class="amount-btn" onclick="setAmount(50)">$50.00</button>
            <button type="button" class="amount-btn" onclick="setAmount(100)">$100.00</button>
        </div>

        <label style="color: #aaa; font-size: 0.8rem;">OR ENTER CUSTOM AMOUNT</label>
        <input type="number" name="amount" id="customAmount" class="custom-input" placeholder="0.00" min="1" step="0.01" required>

        <button type="submit" name="recharge" class="btn-smart">Add Funds Now</button>
    </form>
</div>

<script>
    function setAmount(val) {
        document.getElementById('customAmount').value = val;
    }
</script>

</body>
</html>