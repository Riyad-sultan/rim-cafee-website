<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include __DIR__ . '/../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM contact_messages WHERE id = '$id'");
    $msg = mysqli_fetch_assoc($result);

    if (!$msg) {
        header("Location: inbox.php");
        exit();
    }
} else {
    header("Location: inbox.php");
    exit();
}

$pending_orders_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders WHERE status = 'Pending'");
$pending_orders = mysqli_fetch_assoc($pending_orders_query)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Message | Rim Café Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --gold: #d4af37;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
        }
        body { background: #0b0b0b; color: white; padding: 30px; font-family: 'Poppins', sans-serif; }
        
        .view-card {
            max-width: 900px;
            margin: 0 auto;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 50px;
            backdrop-filter: blur(15px);
        }

        .msg-header {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 30px;
            margin-bottom: 30px;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .meta-item {
            background: rgba(255,255,255,0.02);
            padding: 15px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .meta-item label {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 5px;
        }

        .message-content {
            line-height: 1.8;
            color: #ddd;
            font-size: 1.1rem;
            white-space: pre-wrap;
            margin-top: 20px;
        }

        .action-footer {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* BUTTON RESET & STYLING */
        .btn-reply-final {
            background: var(--gold) !important;
            color: #000 !important;
            padding: 12px 25px;
            border-radius: 10px;
            text-decoration: none !important;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }

        .btn-reply-final:hover {
            transform: translateY(-2px);
            filter: brightness(1.2);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }
    </style>
</head>
<body>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; border-bottom: 1px solid var(--glass-border); padding-bottom: 20px;">
        <h1 style="font-family: 'Playfair Display'; color: var(--gold); margin: 0;">Message Details</h1>
        <nav style="display: flex; align-items: center;">
            <a href="admin_dashboard.php" style="color: white; margin-right: 25px; text-decoration: none;">Inventory</a>
            <a href="view_orders.php" style="color: white; margin-right: 25px; text-decoration: none;">
                Orders <?php if($pending_orders > 0): ?>
                    <span style="background: #ff4d4d; color: white; border-radius: 50%; padding: 2px 8px; font-size: 12px;"><?= $pending_orders ?></span>
                <?php endif; ?>
            </a>
            <a href="inbox.php" style="color: var(--gold); margin-right: 25px; text-decoration: none;">Messages</a>
            <a href="logout.php" style="color: #ff4d4d; text-decoration: none;">Logout</a>
        </nav>
    </div>

    <div class="view-card">
        <div class="msg-header">
            <h2 style="font-family: 'Playfair Display'; color: var(--gold); font-size: 2rem; margin: 0;">
                <?= htmlspecialchars($msg['subject']) ?>
            </h2>
            
            <div class="meta-grid">
                <div class="meta-item">
                    <label>From</label>
                    <strong><?= htmlspecialchars($msg['name']) ?></strong>
                </div>
                <div class="meta-item">
                    <label>Email Address</label>
                    <strong><?= htmlspecialchars($msg['email']) ?></strong>
                </div>
                <div class="meta-item">
                    <label>Received On</label>
                    <strong><?= date('M d, Y - h:i A', strtotime($msg['created_at'])) ?></strong>
                </div>
            </div>
        </div>

        <div class="message-content"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>

        <div class="action-footer">
            <a href="inbox.php" style="text-decoration: none; color: #888;">
                <i class="fas fa-arrow-left"></i> Back to Inbox
            </a>
            
            <div style="display: flex; align-items: center; gap: 20px;">
                <a href="mailto:<?= htmlspecialchars($msg['email']) ?>?subject=Re: <?= rawurlencode($msg['subject']) ?>" 
                   class="btn-reply-final"
                   onclick="window.location.href=this.href; return false;">
                    <i class="fas fa-reply"></i> Reply via Email
                </a>
                
                <a href="delete_message.php?id=<?= $msg['id'] ?>" style="color: #ff4d4d; text-decoration: none; font-size: 0.9rem;" onclick="return confirm('Permanently delete this message?')">
                    <i class="fas fa-trash"></i> Delete
                </a>
            </div>
        </div>
    </div>

</body>
</html>