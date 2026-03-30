<?php
include '../includes/db_connect.php';

// sete headers to force download of  a CSV file 
header('Contatnt_Type: text/cvs; charset=utf-8');
header('Content-Disposition: attachment; filename=rim_caffe_orders_' . date('Y-m-d') . '.csv');

//open the output stream
$output = fopen('php://output','w');

//Set the columen headers for the excel file
fputcsv($output,array('Order ID', 'Customer Name', 'phone','Item Name ','Quantity','Notes', 'Order Date', 'Status'));
// Fetch the data from the database
$query = "SELECT orders.id, orders.customer_name, orders.customer_phone, menu.name, orders.quantity, orders.notes, orders.order_date, orders.status 
          FROM orders 
          JOIN menu ON orders.item_id = menu.id 
          ORDER BY orders.order_date DESC";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>