<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include __DIR__ . '/../includes/db_connect.php';

// Fetch all messages, newest first
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

// Fetch counts for the navigation badges
$pending_orders_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status = 'Pending'");
$pending_orders = mysqli_fetch_assoc($pending_orders_query)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox | Rim Café Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
</head>
<body>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; border-bottom: 1px solid var(--glass-border); padding-bottom: 20px;">
        <h1 style="font-family: 'Playfair Display'; color: var(--gold); margin: 0;">Admin Inbox</h1>
        <nav style="display: flex; align-items: center;">
            <a href="admin_dashboard.php" style="color: white; margin-right: 25px; text-decoration: none;">Inventory</a>
            <a href="view_orders.php" style="color: white; margin-right: 25px; text-decoration: none;">
                Orders <?php if($pending_orders > 0): ?>
                    <span style="background: #ff4d4d; color: white; border-radius: 50%; padding: 2px 8px; font-size: 12px;"><?= $pending_orders ?></span>
                <?php endif; ?>
            </a>
            <a href="inbox.php" style="color: var(--gold); margin-right: 25px; text-decoration: none; font-weight: 600;">Messages</a>
            <a href="logout.php" style="color: #ff4d4d; text-decoration: none;" onclick="return confirm('Logout?')">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <div class="inbox-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="font-family: 'Playfair Display'; margin: 0;">Customer Communications</h2>
            <span style="font-size: 0.9rem; color: #888;"><?= mysqli_num_rows($result) ?> Total Messages</span>
        </div>

        <table width="100%" style="border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="color: var(--gold); border-bottom: 1px solid var(--glass-border);">
                    <th style="padding: 15px;">Date</th>
                    <th style="padding: 15px;">Customer Name</th>
                    <th style="padding: 15px;">Subject</th>
                    <th style="padding: 15px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="msg-row" style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: 0.3s;" onclick="window.location='view_message.php?id=<?= $row['id'] ?>'">
                        <td style="padding: 20px 15px; color: #888; font-size: 0.85rem;">
                            <?= date('M d, Y', strtotime($row['created_at'])) ?>
                        </td>
                        <td style="padding: 20px 15px;">
                            <div style="font-weight: 600;"><?= htmlspecialchars($row['name']) ?></div>
                            <div style="font-size: 0.75rem; color: #666;"><?= htmlspecialchars($row['email']) ?></div>
                        </td>
                        <td style="padding: 20px 15px; color: #ccc;">
                            <span class="unread-dot"></span> <?= htmlspecialchars($row['subject']) ?>
                        </td>
                        <td style="padding: 20px 15px; text-align: right;">
                            <a href="view_message.php?id=<?= $row['id'] ?>" class="action-btn">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="padding: 50px; text-align: center; color: #666;">
                            <i class="fas fa-envelope-open" style="font-size: 3rem; margin-bottom: 20px; display: block;"></i>
                            Your inbox is currently empty.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>