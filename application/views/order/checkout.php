<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file checkout.php
* @brief This page contains a form for the user to finalize his order
* @details The user can input his email, credit card #, and card expiry date
******************************************************************************/
?><html>
<head>
	<link href="<?echo base_url();?>css/table.css" rel="stylesheet" type="text/css"/>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
</head>
<body>
<h2>Check out and buy</h2>
<?php
	echo form_open('candystore/checkout')."\n";
	// Display order details, just like on the cart page, but without the
	// ability to edit the quantities.
	echo "<p>" . anchor('candystore/index','Cancel your order') . "</p>";
	echo "<table>";
	echo "<tr><th>Name</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>\n";
	$total = 0;
	foreach($orders as $key=>$value){
		if ($value>0) {
			$product = new Product();
			$product = $this->product_model->get($key);
			echo "<tr><td>".$product->name."</td>";
			echo "<td>".$product->price."</td>";
			echo "<td>".$value."</td>";
			echo "<td>".$value*floatval($product->price)."</td>";
			echo "</tr>\n";
			$total += $value*floatval($product->price);
		}
	}
	echo "<tr><th colspan='3'>Total</th><td>".$total."</td></tr>\n";
	echo "</table><br>\n";
	echo "<p>Bill to {$customer['name']}</p>";
	// Hidden field - total value of order
	echo form_hidden('total',$total);
	// By default the billing email is the one the customer registered with,
	// but he can choose to provide a different one via this form (if valid).
	echo form_label('Email')."\n"; 
	echo form_input('email',$customer['email'])."\n<br><br>"; 
	// Credit card info
	echo form_label('Credit card #')."\n"; 
	echo form_input('creditcard_number')."\n";
	// Don't show the error if this is the first checkout attempt
	if (!isset($submit) || $submit!="Checkout")
		echo form_error('creditcard_number')."\n";
	echo "<br><br>"; 
	echo form_label('Expiry Month (MM)')."\n"; 
	echo form_input('creditcard_month')."\n"; 
	if (!isset($submit) || $submit!="Checkout")
		echo form_error('creditcard_month')."\n";
	echo "<br><br>"; 
	echo form_label('Expiry Year (YY)')."\n"; 
	echo form_input('creditcard_year')."\n";
	if (!isset($submit) || $submit!="Checkout")
		echo form_error('creditcard_year')."\n";
	echo "<br><br>"; 
	echo form_submit('submit', 'Finish and Pay')."\n";
	echo form_close()."<br>";
?>
</body>
</html>
