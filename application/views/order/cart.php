<!DOCTYPE html>
<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file cart.php
* @brief This file shows a summary of the user's order before he actually pays
* @details Displays order details in a table and allows last-minute editing.
******************************************************************************/
?><html>
<head>
	<link href="<?echo base_url();?>css/table.css" rel="stylesheet" type="text/css"/>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
	<script src="<?echo base_url();?>js/catalog.js"></script>
</head>
<body>
<h2>Your cart</h2>
<?php
	echo form_open('candystore/catalog')."\n";
	// Show order details in HTML table
	echo "<p>Your order:</p>\n";
	echo "<table>";
	echo "<tr><th>Name</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>\n";
	$total = 0;
	foreach($orders as $key=>$value){
		if ($value>0) {
			$product = new Product();
			$product = $this->product_model->get($key);
			echo "<tr><td>".$product->name."</td>";
			echo "<td name='price'>".$product->price."</td>";
			//echo "<td>".$value."</td>";
			echo "<td>";
			echo "<input type='text' size='5' name='{$product->id}' value='$value'/>";
			echo "</td>";
			echo "<td id='subtotal'>".$value*floatval($product->price)."</td>";
			echo "</tr>\n";
			$total += $value*floatval($product->price);
		}
	}
	echo "<tr><th colspan='3'>Total</th><td id='total'>".$total."</td></tr>\n";
	echo "</table><br>\n";
	// Hidden field - total value of order (updatable by JQuery)
	echo form_hidden('total',$total);
	echo form_submit('submit', 'Save and Continue Shopping')."\n";
	echo form_submit('submit', 'Checkout')."\n"; 
	echo form_close()."<br>";
?>
