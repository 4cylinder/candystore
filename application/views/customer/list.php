<h2>Registered Customers</h2>
<link href="<?echo base_url();?>css/table.css" rel="stylesheet" type="text/css"/>
<?php 
echo "<table>";
echo "<tr><th>First Name</th>
	<th>Last Name</th><th>Login ID</th><th>Email</th></tr>\n";

foreach ($customers as $customer) {
	if ($customer->login!="admin"){
		echo "<tr>";
		echo "<td>".$customer->first."</td>\n";
		echo "<td>".$customer->last."</td>\n";
		echo "<td>".$customer->login."</td>\n";
		echo "<td>".$customer->email."</td>\n";
		echo "<td>".anchor("candystore/deleteCustomer/$customer->id",'Delete',
			"onClick='return confirm(\"Do you really want to delete this customer?\");'").
			"</td>\n";
		echo "</tr>\n";
	}
}
echo "</table><br>";
// Delete all customers link
echo "<p>".anchor('candystore/deleteAllCustomers','Delete All Customers')."</p>";
?>
