<?php
include '../includes/db_connect.php';

// handl search & data filter
$search =isset($_GET['search']) ? mysqli_real_escape_string($conn,$_Get['search']) :'';
$filter_date = isse($_GET['filter_date']) ? mysqli_real_escape_string($conn, $_GET['filter_date']) :'';

// Stats Queries
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(menu.price * orders.quantity) as rev FROM orders JOIN menu ON orders.item_id = menu.id WHERE orders.status = 'Completed'"))['rev'] ?? 0;
$pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM orders WHERE status = 'Pending'"))['c'] ?? 0;
$today_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM orders WHERE DATE(order_date) = CURDATE()"))['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management | Rim Caffe Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin_premium.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-body">

    <div class="admin-container">
        <div class="stats-grid">
            <div class="glass-card stat-card border-gold">
                <div class="stat-flex">
                    <i class="fas fa-wallet icon-gold"></i>
                    <div>
                        <p class="stat-label">Total Revenue</p>
                        <h2 class="stat-value">$<?= number_format($total_revenue, 2); ?></h2>
                    </div>
                </div>
            </div>
            <div class="glass-card stat-card border-orange">
                <div class="stat-flex">
                    <i class="fas fa-utensils icon-orange"></i>
                    <div>
                        <p class="stat-label">Pending</p>
                        <h2 class="stat-value"><?= $pending_count; ?> Orders</h2>
                    </div>
                </div>
            </div>
            <div class="glass-card stat-card border-blue">
                <div class="stat-flex">
                    <i class="fas fa-calendar-day icon-blue"></i>
                    <div>
                        <p class="stat-label">Today</p>
                        <h2 class="stat-value"><?= $today_count; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card main-content-card">
            <div class="management-header">
                <h2 class="page-title">Orders</h2>
                <form action="" method="GET" class="filter-form">
                    <input type="date" name="filter_date" value="<?= $filter_date ?>" class="input-dark">
                    <input type="text" name="search" value="<?= $search ?>" placeholder="Search..." class="input-dark">
                    <button type="submit" class="btn-smart">Apply</button>
                </form>
                <div class="action-buttons">
                    <a href="admin_dashboard.php" class="btn-smart">Dashboard</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT orders.*, menu.name as food_name FROM orders JOIN menu ON orders.item_id = menu.id WHERE 1=1";
                        if (!empty($search)) $query .= " AND (orders.customer_name LIKE '%$search%' OR menu.name LIKE '%$search%')";
                        if (!empty($filter_date)) $query .= " AND DATE(orders.order_date) = '$filter_date'";
                        $query .= " ORDER BY orders.order_date DESC";
                        
                        $result = mysqli_query($conn, $query);
                        if(mysqli_num_rows($result) > 0) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $status_class = ($row['status'] == 'Pending') ? 'status-pending' : 'status-completed';
                                $order_time = strtotime($row['order_date']);
                                $date_text = (date('Y-m-d', $order_time) == date('Y-m-d')) ? "Today, ".date('H:i', $order_time) : date('M d, H:i', $order_time);

                                echo "<tr class='order-row'>";
                                echo "<td data-label='ID'>#{$row['id']}</td>";
                                echo "<td data-label='Customer'>
                                        <div class='cust-info-mobile'>
                                            <span class='cust-name'>".htmlspecialchars($row['customer_name'])."</span>
                                            <small class='cust-phone'>".htmlspecialchars($row['customer_phone'])."</small>
                                        </div>
                                      </td>";
                                echo "<td data-label='Item' class='food-name'>".htmlspecialchars($row['food_name'])."</td>";
                                echo "<td data-label='Qty'>{$row['quantity']}</td>";
                                echo "<td data-label='Date' class='date-cell'>{$date_text}</td>";
                                echo "<td data-label='Status'><span class='status-badge $status_class'>".strtoupper($row['status'])."</span></td>";
                                echo "<td data-label='Actions'>
                                        <div class='action-flex'>";
                                            if($row['status'] == 'Pending') {
                                                echo "<a href='update_status.php?id={$row['id']}&status=Completed' class='action-icon check'><i class='fas fa-check-circle'></i></a>";
                                            } else {
                                                echo "<i class='fas fa-check-double done-icon'></i>";
                                            }
                                            echo "<a href='update_status.php?delete_id={$row['id']}' class='action-icon trash' onclick='return confirm(\"Delete?\")'><i class='fas fa-trash-alt'></i></a>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='no-data'>No orders found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>