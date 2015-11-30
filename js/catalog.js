/******************************************************************************
* Tzuo Shuin Yew (997266499)
* Chia-Heng Lin (997530970)
* March 20, 2014
* @file catalog.js
* @brief Dynamic updating of cart on customer catalog page
* @details When the user types in a quantity, subtotals and total are updated
******************************************************************************/
$(document).ready(function(){
	// When an "input" text box is changed, trigger this function
	$("input").change(function(){
		// calculate the subtotal
		var subtotal = parseInt($(this).val())*
			parseFloat($(this).parent().parent().find("td[name='price']").text());
		// Stick its value into the HTML table
		$(this).parent().parent().find('#subtotal').text(subtotal);
		// Calculate the full total
		var total = 0;
		$("td[id='subtotal']").each(function(){
			if ($(this).text()!="")
				total += parseFloat($(this).text());
		});
		// Put values into the hidden form entry and the HTML table
		$('#total').text(total);
		$("input[name='total']").val(total);
	});
});
