<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file list.php
* @brief This page displays all available products made by the admin
* @details When the admin logs in, a table of products will be displayed at the
  top of the admin's index page. There are links to view extra details or edit
  details, for each individual product
******************************************************************************/
?><html>
<head>
	<link href="<?echo base_url();?>css/table.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<h2>Product Table</h2>
<?php 
	// Link to a form for adding new products
	echo "<p>".anchor('candystore/newForm','Add New')."</p>";
	// Show all the products available for customers to buy, as a HTML table
	echo "<table>";
	echo "<tr><th>Name</th><th>Description</th><th>Price</th><th>Photo</th></tr>\n";
	foreach ($products as $product) {
		echo "<tr>";
		echo "<td>".$product->name."</td>\n";
		echo "<td>".$product->description."</td>\n";
		echo "<td>".$product->price."</td>\n";
		echo "<td><img src='".base_url()."images/product/".$product->photo_url.
			"' width='100px'/></td>\n";
		// Delete button gets a javascript prompt to prevent accidents
		echo "<td>".anchor("candystore/deleteProduct/$product->id",'Delete',
			"onClick='return confirm(\"Do you really want to delete this product?\");'").
			"</td>\n";
		echo "<td>".anchor("candystore/editForm/$product->id",'Edit')."</td>\n";
		echo "<td>".anchor("candystore/read/$product->id",'View')."</td>\n";
			
		echo "</tr>\n";
	}
	echo "</table>";
?>	
</body>
</html>
