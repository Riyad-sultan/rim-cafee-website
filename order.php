<?php
session_start();
include 'includes/db_connect.php';
include "includes/header.php";

if(!isset($_SESSION['user_id'])){
    header("Location: admin/login.php?msg=please_login");
    exit();
}
$item_ids = isset($_GET['item_ids']) ? $_GET['item_ids'] : [];
$selected_items = [];
$total_potential_cost = 0;

if (!empty($item_ids)) {
    $ids_string = implode(',', array_map('intval', $item_ids));
    $query = "SELECT * FROM menu WHERE id IN ($ids_string)";
    $result = mysqli_query($conn, $query);
    
    while($row = mysqli_fetch_assoc($result)) {
        $selected_items[] = $row;
        $total_potential_cost += $row['price'];
    }
}

// Fetch fresh balance to check against total
$uid = $_SESSION['user_id'];
$bal_res = mysqli_query($conn, "SELECT balance FROM users WHERE id = '$uid'");
$user_row = mysqli_fetch_assoc($bal_res);
$current_balance = $user_row['balance'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Your Order | Rim Caffe</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #0b0b0b; }
        .order-container { max-width: 650px; margin: 60px auto; padding: 0 20px; }
        .section-title { text-align: center; font-family: 'Playfair Display', serif; color: var(--gold); font-size: 2.5rem; margin-bottom: 30px; }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 25px; padding: 40px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4); }
        .selection-item { display: flex; gap: 20px; align-items: center; margin-bottom: 20px; padding: 15px; border-radius: 15px; background: rgba(212, 175, 55, 0.05); border: 1px solid rgba(212, 175, 55, 0.1); }
        .selection-item img { width: 70px; height: 70px; border-radius: 12px; object-fit: cover; border: 1.5px solid var(--gold); }
        label { display: block; margin: 20px 0 8px; font-size: 0.9rem; font-weight: 600; color: var(--gold); text-transform: uppercase; letter-spacing: 1px; }
        input, textarea { width: 100%; padding: 14px 18px; background: rgba(255, 255, 255, 0.07) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; border-radius: 12px !important; color: #fff !important; }
        .qty-input { width: 80px !important; padding: 8px !important; text-align: center; margin-left: auto; border: 1px solid var(--gold) !important; }
        .btn-smart { margin-top: 30px; padding: 16px; width: 100%; cursor: pointer; background: var(--gold); color: #000; border: none; border-radius: 12px; font-weight: bold; font-size: 1.1rem; }
        .back-link { display: block; text-align: center; margin-top: 25px; color: #888; text-decoration: none; }
        
        /* New Summary Style */
        .order-summary {
            margin-top: 20px;
            padding: 20px;
            border-top: 1px dashed rgba(212, 175, 55, 0.3);
        }
        .summary-line { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 1.1rem; }
        .total-highlight { color: var(--gold); font-weight: 700; font-size: 1.4rem; }
    </style>
</head>
<body>
    <section class="order-container">
    <h2 class="section-title">Complete Your Order</h2>
    
    <div class="glass-card">
        <?php if (!empty($selected_items)): ?>
            
            <form action="process_order.php" method="POST" id="orderForm">
                <div style="margin-bottom: 30px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px;">
                    <h4 style="color: var(--gold); margin-bottom: 20px; font-family: 'Playfair Display'; font-size: 1.3rem;">Items & Quantities:</h4>
                    
                    <?php foreach ($selected_items as $item): ?>
                        <div class="selection-item">
                            <input type="hidden" name="item_ids[]" value="<?php echo $item['id']; ?>">
                            <input type="hidden" class="item-price" value="<?php echo $item['price']; ?>">
                            
                            <img src="assets/images/items/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                            
                            <div style="flex-grow: 1;">
                                <h3 style="color: #fff; font-size: 1.1rem; margin: 0;"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p style="font-weight: bold; color: var(--gold); margin: 4px 0 0 0;">$<?php echo number_format($item['price'], 2); ?></p>
                            </div>

                            <div style="text-align: right;">
                                <p style="font-size: 0.7rem; color: #888; margin-bottom: 5px;">QTY</p>
                                <input type="number" name="quantities[<?php echo $item['id']; ?>]" value="1" min="1" class="qty-input quantity-field" required>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-summary">
                    <div class="summary-line">
                        <span style="color: #aaa;">Your Balance:</span>
                        <span style="color: #2ecc71;">$<?php echo number_format($current_balance, 2); ?></span>
                    </div>
                    <div class="summary-line">
                        <span style="color: #fff;">Total Bill:</span>
                        <span class="total-highlight" id="displayTotal">$<?php echo number_format($total_potential_cost, 2); ?></span>
                    </div>
                    <p id="balanceWarning" style="color: #ff4d4d; font-size: 0.8rem; display: none; margin-top: 10px; text-align: center;">
                        <i class="fas fa-exclamation-circle"></i> Insufficient balance for this quantity!
                    </p>
                </div>

                <label><i class="fas fa-user"></i> Your Full Name</label>
                <input type="text" name="customer_name" placeholder="John Doe" value="<?php echo $_SESSION['username']; ?>" required>

                <label><i class="fas fa-phone"></i> Phone Number</label>
                <input type="text" name="customer_phone" placeholder="09..." required>

                <label><i class="fas fa-comment-dots"></i> Special Instructions</label>
                <textarea name="notes" placeholder="Example: Less sugar, extra ice..."></textarea>

                <button type="submit" name="submit_order" id="submitBtn" class="btn-smart">
                    Confirm & Place Order
                </button>
                
                <a href="menu.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Change Item Selection
                </a>
            </form>

        <?php else: ?>
            <div style="text-align: center; padding: 20px;">
                <i class="fas fa-shopping-basket" style="font-size: 3rem; color: var(--gold); margin-bottom: 20px;"></i>
                <p style="color: #fff;">No items selected.</p>
                <a href="menu.php" class="btn-smart" style="display: inline-block; text-decoration:none;">Browse Menu</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    const userBalance = <?php echo $current_balance; ?>;
    const qtyInputs = document.querySelectorAll('.quantity-field');
    const priceInputs = document.querySelectorAll('.item-price');
    const displayTotal = document.getElementById('displayTotal');
    const submitBtn = document.getElementById('submitBtn');
    const balanceWarning = document.getElementById('balanceWarning');

    function updateTotal() {
        let total = 0;
        qtyInputs.forEach((input, index) => {
            const qty = parseInt(input.value) || 0;
            const price = parseFloat(priceInputs[index].value);
            total += qty * price;
        });

        displayTotal.innerText = '$' + total.toFixed(2);

        // Check if user has enough money
        if (total > userBalance) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
            balanceWarning.style.display = 'block';
        } else {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
            balanceWarning.style.display = 'none';
        }
    }

    qtyInputs.forEach(input => {
        input.addEventListener('input', updateTotal);
    });

    // Run once on load
    updateTotal();
</script>

<?php include "includes/footer.php"; ?>
    
</body>
</html>