<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file list.php
* @brief This page displays all completed orders made by customers
* @details When the admin logs in, a table of orders will be displayed at the
  bottom of the admin's index page.
******************************************************************************/
?>
<html>
<head>
	<link href="<?echo base_url();?>css/table.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<h2>Completed Orders</h2>
<?php 
// output all the customer orders (not order_items) in a HTML table
echo "<table>";
echo "<tr><th>Customer</th><th>Date</th><th>Time</th><th>Total</th></tr>\n";
foreach ($orders as $order) {
	echo "<tr>";
	$cid = $order->customer_id;
	// Find the customer who made the order and show his/her name
	foreach ($customers as $customer){
		if ($customer->id==$cid)
			echo "<td>".$customer->first." ".$customer->last."</td>";
	}
	echo "<td>".$order->order_date."</td>";
	echo "<td>".$order->order_time."</td>";
	echo "<td>".$order->total."</td>";
	echo "<td>".anchor("candystore/orderDetail/{$order->id}",'View')."</td>";
	// Delete button has a javascript prompt to prevent accidents
	echo "<td>".anchor("candystore/deleteOrder/{$order->id}",'Delete',
		"onClick='return confirm(\"Do you really want to delete this order?\");'").
		"</td>";
	echo "</tr>\n";
}
echo "</table>";
// Delete all orders link
echo "<p>".anchor('candystore/deleteAllOrders','Delete All Orders')."</p>";
?>
</body>
</html>
