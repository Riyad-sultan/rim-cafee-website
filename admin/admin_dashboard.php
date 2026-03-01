<?php
session_start();
if(isset($_SESSION["admin"])){ header("Location: login");exit();}
include __DIR__ . '/../includes/db_connect.php';
$items = mysqli_query($conn, "SELECT * FROM menu");

$order_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status = 'Pending'");
$order_data = mysqli_fetch_assoc($order_count_query);
$pending_orders = $order_data['total'];

// Get the total number of customer contact messages
$msg_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM contact_messages");
$msg_data = mysqli_fetch_assoc($msg_count_query);
$total_messages = $msg_data['total'];

// Retrieve the 5 most recent messages to show a "Recent Inquiries" preview
$recent_messages = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");

// Calculate total revenue for the current month by joining orders and menu prices
$revenue_query = mysqli_query($conn, "
    SELECT SUM(orders.quantity * menu.price) as total_revenue 
    FROM orders 
    JOIN menu ON orders.item_id = menu.id 
    WHERE MONTH(orders.order_date) = MONTH(CURRENT_DATE()) 
    AND YEAR(orders.order_date) = YEAR(CURRENT_DATE())
");
$revenue_data = mysqli_fetch_assoc($revenue_query);
$monthly_earnings = $revenue_data['total_revenue'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rim Caffe  | admin Dashbord</title>
    <title>Rim Caffe | Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .msg-preview-row:hover { background: rgba(212, 175, 55, 0.05) !important; }
        .badge-new { background: var(--gold); color: black; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; margin-left: 5px; }
    </style>
</head>
<body style="background: #0b0b0b; color: white; padding: 30px; font-family: 'Poppins', sans-serif;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px;">
        <h1 style="font-family: 'Playfair Display'; color: var(--gold); margin: 0;">Admin Panel</h1>
        <nav style="display: flex; align-items: center;">
            <a href="admin_dashboard.php" style="color: var(--gold); margin-right: 25px; text-decoration: none; font-weight: 600;">Inventory</a>
            <a href="view_orders.php" style="color: white; margin-right: 25px; text-decoration: none;">
                Orders <?php if($pending_orders > 0): ?>
                    <span style="background: #ff4d4d; color: white; border-radius: 50%; padding: 2px 8px; font-size: 12px;"><?= $pending_orders ?></span>
                <?php endif; ?>
            </a>
            <a href="inbox.php" style="color: white; margin-right: 25px; text-decoration: none;">
                Messages <?php if($total_messages > 0): ?>
                    <span style="background: var(--gold); color: black; border-radius: 50%; padding: 2px 8px; font-size: 12px;"><?= $total_messages ?></span>
                <?php endif; ?>
            </a>
            <a href="logout.php" style="color: #ff4d4d; text-decoration: none; font-weight: 600;" onclick="return confirm('Logout from Admin Panel?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 3fr; gap: 30px;">
        
        <div class="glass-card" style="height: fit-content; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); padding: 25px; border-radius: 20px;">
            <h3 style="margin-bottom: 25px; font-family: 'Playfair Display'; color: var(--gold);">Business Overview</h3>
            
            <div style="margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px;">
                <p style="color: #888; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Monthly Revenue</p>
                <h2 style="color: #2ecc71; margin: 5px 0;">$<?= number_format($monthly_earnings, 2) ?></h2>
            </div>

            <div style="margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px;">
                <p style="color: #888; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Pending Orders</p>
                <h2 style="color: var(--gold); margin: 5px 0;"><?= $pending_orders ?></h2>
            </div>

            <div style="margin-bottom: 25px;">
                <p style="color: #888; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">Total Messages</p>
                <h2 style="color: #4db8ff; margin: 5px 0;"><?= $total_messages ?></h2>
            </div>
            
            <a href="inbox.php" class="btn-smart" style="display: block; width: 100%; text-align: center; font-size: 0.9rem; margin-top: 10px;">Open Inbox</a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 30px;">
            
            <div class="glass-card" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); padding: 25px; border-radius: 20px;">
                <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 20px;">
                    <h2 style="font-family: 'Playfair Display'; margin: 0;">Menu Inventory</h2>
                    <a href="add_product.php" class="btn-smart" style="padding: 10px 20px; text-decoration: none;">+ Add New Dish</a>
                </div>

                <table width="100%" style="text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="color: var(--gold); border-bottom: 1px solid rgba(212, 175, 55, 0.3);">
                            <th style="padding: 15px 10px;">Dish Name</th>
                            <th style="padding: 15px 10px;">Price</th>
                            <th style="padding: 15px 10px;">Category</th>
                            <th style="padding: 15px 10px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($items)): ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 15px 10px;"><?= htmlspecialchars($row['name']) ?></td>
                            <td style="padding: 15px 10px; font-weight: 600;">$<?= number_format($row['price'], 2) ?></td>
                            <td style="padding: 15px 10px;"><span class="category-badge" style="font-size: 0.7rem; background: rgba(212,175,55,0.1); color: var(--gold); padding: 3px 8px; border-radius: 4px;"><?= htmlspecialchars($row['category']) ?></span></td>
                            <td style="padding: 15px 10px;">
                                <a href="edit_product.php?id=<?= $row['id'] ?>" style="color: #4db8ff; text-decoration:none; margin-right: 15px;"><i class="fas fa-edit"></i></a>
                                <a href="delete_product.php?id=<?= $row['id'] ?>" style="color: #ff4d4d; text-decoration:none;" onclick="return confirm('Delete this?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="glass-card" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); padding: 25px; border-radius: 20px;">
                <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 20px;">
                    <h2 style="font-family: 'Playfair Display'; margin: 0;">Recent Customer Inquiries</h2>
                    <a href="inbox.php" style="color: var(--gold); text-decoration: none; font-size: 0.9rem;">View All →</a>
                </div>

                <table width="100%" style="text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="color: #888; font-size: 0.8rem; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th style="padding: 10px;">Date</th>
                            <th style="padding: 10px;">Customer</th>
                            <th style="padding: 10px;">Subject</th>
                            <th style="padding: 10px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($recent_messages) > 0): ?>
                            <?php while($msg = mysqli_fetch_assoc($recent_messages)): ?>
                            <tr class="msg-preview-row" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 15px 10px; font-size: 0.85rem; color: #666;"><?= date('M d', strtotime($msg['created_at'])) ?></td>
                                <td style="padding: 15px 10px; font-weight: 600;"><?= htmlspecialchars($msg['name']) ?></td>
                                <td style="padding: 15px 10px; color: #ccc;"><?= htmlspecialchars($msg['subject']) ?></td>
                                <td style="padding: 15px 10px; text-align: right;">
                                    <a href="view_message.php?id=<?= $msg['id'] ?>" style="color: var(--gold); text-decoration: none; font-size: 0.85rem; border: 1px solid var(--gold); padding: 4px 10px; border-radius: 5px;">Read</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="padding: 30px; text-align: center; color: #666;">No recent messages.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div> 
    
    </div>
    
</body>
</html>