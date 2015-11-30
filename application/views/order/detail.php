<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file detail.php
* @brief This file shows the specific details of a completed order in a table
* @details Redirect here when admin clicks "view" from the index page
******************************************************************************/
?><html>
<head>
	<link href="<?echo base_url();?>css/table.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<h2>Order details</h2>
<?php 
	// Show individual details of an order (how much paid, what was ordered, etc)
	echo "<p>" . anchor('candystore/index','Back') . "</p>\n";
	echo "<p> Order ID: ".$order->id."</p>\n";
	echo "<p> Customer ID: ".$order->customer_id."</p>\n";
	echo "<p> Customer Name: ".$customer->first." ".$customer->last."</p>\n";
	echo "<p> Credit Card (Last 4 digits): ".substr($order->creditcard_number,-4)."</p>\n";
	echo "<p> Order Value: ".$order->total."</p>\n";
	echo "<p> Items ordered: </p>\n";
	echo "<table>\n";
	echo "<tr><th>Name</th><th>Photo</th><th>Quantity</th></tr>\n";
	foreach ($order_items as $item){
		$product = $this->product_model->get($item->product_id);
		echo "<tr><td>".$product->name."</td>";
		echo "<td><img src='".base_url()."images/product/".$product->photo_url.
			"' width='100px'/></td>";
		echo "<td>".$item->quantity."</td></tr>\n";
	}
	echo "</table>\n";
?>	
</body>
</html>
