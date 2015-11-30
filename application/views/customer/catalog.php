<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file catalog.php
* @brief This file shows to customers all the available products for ordering
* @details Products are displayed in a HTML table. The customer can type in
  quantities he wishes to purhcase, then click add to cart.
******************************************************************************/
?><!DOCTYPE html><html>
<head>
	<link href="<?echo base_url();?>css/table.css" rel="stylesheet" type="text/css"/>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
	<script src="<?echo base_url();?>js/catalog.js"></script>
</head>
<body>
<h2>Candy Store</h2>

<?php
	echo form_open('candystore/catalog')."\n";
	echo "<p>Welcome ".$customer['name']."!</p>\n";
	echo "<p>Here's our catalog of delicious candy.</p>\n";
	// show catalog in HTML table
	echo "<table>";
	echo "<tr><th>Name</th><th>Description</th><th>Price</th>
		<th>Photo</th><th>Quantity</th><th>Subtotal</th></tr>\n";
	foreach ($products as $product) {
		echo "<tr>";
		echo "<td>".$product->name."</td>\n";
		echo "<td>".$product->description."</td>\n";
		echo "<td name='price'>".$product->price."</td>\n";
		echo "<td><img src='".base_url()."images/product/".$product->photo_url.
			"' width='100px'/></td>\n";
		echo "<td><input type='number' size='5' name='{$product->id}'/>"."</td>";
		echo "<td id='subtotal'>"."</td>";
		echo "</tr>\n";
	}
	echo "<tr><th colspan='5'>Total</th><td id='total' ></td></tr>\n";
	echo "</table><br>\n";
	// Hidden field - total value of order (updatable by JQuery)
	echo form_hidden('total','');
	echo form_error('total')."\n"; 
	echo form_submit('submit', 'Add to Cart')."\n";
	echo form_submit('submit', 'View/Edit Cart')."\n"; 
	echo form_close()."<br>";
	echo "<p>Note: Clicking view/edit cart without adding first will not 
		update the contents of your cart!</p>";
?>
