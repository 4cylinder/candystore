<?php
/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file register.php
* @brief This page is a form for a user to register as a new customer
* @details Linked here from login.php. Has client and server side validation.
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
	<h1>Candy Store Registration</h1>
<?php
	echo "<p>" . anchor('candystore/index','Back') . "</p>";
	echo validation_errors();
	echo form_open('candystore/register')."\n";
	echo form_label('First Name')."\n";
	echo form_input('first')."\n<br>";
	echo form_label('Last Name')."\n";
	echo form_input('last')."\n<br>";
	echo form_label('Email Address')."\n";
	echo form_input('email')."\n<br>";
	echo form_label('Username')."\n"; 
	echo form_input('login')."\n<br>";
	echo form_label('Password')."\n";
	echo form_password('password')."\n<br>";
	echo form_submit('submit', 'Create New Account');
	echo form_close();
?>
</body>
</html>
