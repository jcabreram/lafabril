$(function() {

	$("#fecha, #fecha2").datepicker({
		dateFormat:'yy-mm-dd'
	}).attr('readonly', 'readonly');

	/*** All text inputs with .date class are going to have datepicker ***/
	$('input[type="text"].date').datepicker({
		dateFormat:'dd/mm/yy'
	}).attr('readonly', 'readonly');


	/*** CALCULATE INVOICE TOTAL ***/
	calculateInvoiceTotal();

	// We watch the input for a change
	$('.userAmount').bind('input', calculateInvoiceTotal);

	function calculateInvoiceTotal()
	{
		var subtotal = 0.0, VAT = 0.0, tax = 0.0, total = 0.0;
		var amountOrdered = 0.0, amountDelivered = 0.0, maximumAmountAllowed = 0.0, userAmount = 0.0;
		var productPrice = 0.0;

		$('#invoiceProducts tbody tr').each(function() {
			var tr = $(this);

			amountOrdered = tr.find('.amountOrdered').first().val();
			amountDelivered = tr.find('.amountDelivered').first().val();
			maximumAmountAllowed = parseFloat(amountOrdered) - parseFloat(amountDelivered);
			userAmount = tr.find('.userAmount').first().val();
			userAmount = userAmount === '' ? 0 : parseFloat(userAmount);
			userAmount = isNaN(userAmount) ? -1 : userAmount;

			productPrice = tr.find('.productPrice').first().val();
			
			if (userAmount >= 0 && userAmount <= maximumAmountAllowed) {
				tr.removeAttr('style');
				subtotal += parseFloat(userAmount) * parseFloat(productPrice);
			} else {
				tr.attr('style', 'color: red');
			}
		});

		VAT = $('.invoiceVAT').first().val();
		tax = VAT * subtotal;

		total = tax + subtotal;

		$('.invoiceSubtotal').first().text('$' + subtotal.toFixed(2));
		$('.invoiceTax').first().text('$' + tax.toFixed(2));
		$('.invoiceTotal').first().text('$' + total.toFixed(2));
	}
	/*** CALCULATE INVOICE TOTAL ***/


	/*** BILL ORDER ***/
	$('.addCard').click(function() {


		return;
	});
	/*** BILL ORDER ***/











});