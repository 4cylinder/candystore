<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file login.php
* @brief Login form for users.
* @details Enter username and password here. Has client and server side validation
******************************************************************************/
?><!DOCTYPE html>
<html>
<head>
	<link href="<?echo base_url();?>css/form.css" rel="stylesheet" type="text/css"/>
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
	<script src="<?echo base_url();?>js/login.js"></script>
</head>
<body>
	<h1>Candy Store Login</h1>
<?php 
	echo validation_errors();
	echo form_open('candystore/login')."\n";
	echo form_label('Username')."\n"; 
	echo form_input('login')."\n<br><br>"; 
	echo form_label('Password')."\n";  
	echo form_password('password')."\n<br>"; 
	echo form_submit('submit', 'Login')."\n"; 
	echo form_close()."<br>";
	echo "<p> Don't have an account? Register one ";
	echo anchor('candystore/register', 'here.');
	echo "</p>";
?>
</body>
</html>
