<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file receipt.php
* @brief This page shows the user's receipt after he has paid for his order
* @details Shows summary of order and gives option to print receipt. Also emails it.
******************************************************************************/
?><head>
	<link href="<?echo base_url();?>css/table.css" rel="stylesheet" type="text/css"/>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
</head>
<body>
<h2>Receipt</h2>
<?php
	// load email library for later
	$this->load->library('email');
	// Show order summary in a HTML table
	$receipt = "<p>Order date and time: ".date('Y-m-d H:i:s')."</p>";
	$receipt .= "<p>Bill to: ".$customer['name']."</p>";
	$receipt .= "<p>Billing email: ".$customer['email']."</p>";
	$receipt .= "<p>Credit card: *********-".substr($creditcard_number,-4)."</p>";
	$receipt .= "<p>Items ordered:</p>";
	$receipt .= "<table>";
	$receipt .= "<tr><th>Name</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>\n";
	$total = 0;
	foreach($orders as $key=>$value){
		if ($value>0) {
			$product = new Product();
			$product = $this->product_model->get($key);
			$receipt .= "<tr><td>".$product->name."</td>";
			$receipt .= "<td>".$product->price."</td>";
			$receipt .= "<td>".$value."</td>";
			$receipt .= "<td>".$value*floatval($product->price)."</td>";
			$receipt .= "</tr>\n";
			$total += $value*floatval($product->price);
		}
	}
	$receipt .= "<tr><th colspan='3'>Total</th><td>".$total."</td></tr>\n";
	$receipt .= "</table><br>\n";
	echo $receipt;
	// Place the whole page into a HTML email, send to the customer
	echo "<p><a href=# onclick='window.print();return false;'>Print Receipt</a></p>";
	echo anchor('', 'Back to store.');
	$this->email->from('bathroomsecurity@gmail.com');
	$this->email->to($customer['email']);
	$this->email->subject('Candystore Order');
	$this->email->message($receipt);
	$this->email->send();
?>
</body>
</html>
