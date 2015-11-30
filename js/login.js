/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file login.js
* @brief Client-side validation for the login page
* @details Sets rules for form values with appropriate error messages.
  Uses the jquery validate library which must be called before this file.
******************************************************************************/
$(document).ready(function(){
	$.validator.setDefaults({
		submitHandler: function() {
			form.submit();
		}
	});
	$('form').validate({
		rules: {
			login: {
				required: true,
				maxlength: 16
			},
			password: {
				required: true,
				minlength: 6,
				maxlength: 16
			},
			first: {
				required: true,
				maxlength: 24
			},
			last: {
				required: true,
				maxlength: 24
			},
			email: {
				required: true,
				maxlength: 45,
				email: true
			}
		},
		messages: {
			login: {
				required: "Username needed",
				maxlength: "Usernames don't exceed 16 characters"
			},
			password: {
				required: "Password needed",
				minlength: "Passwords must be at least 6 characters",
				maxlength: "Passwords don't exceed 16 characters"
			},
			first: {
				required: "First name needed",
				maxlength: "First names don't exceed 24 characters"
			},
			last: {
				required: "Last name needed",
				maxlength: "Last names don't exceed 24 characters"
			},
			email: {
				required: "Email address needed",
				email: "Please enter a valid email address",
				maxlength: "Email addresses don't exceed 45 characters"
			}
		}
	});
});
