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

	$('.amountOrdered').bind('input', calculateInvoiceTotal);

	function calculateInvoiceTotal()
	{
		var subtotal = 0.0, iva = 0.0, tax = 0.0, total = 0.0;

		$('#invoiceProducts tbody tr').each(function() {
			var tr = $(this);

			var price = tr.find('.productPrice').first().text();
			price = price.substring(1);
			var orderedAmount = tr.find('.amountOrdered').first().val();
			orderedAmount = (orderedAmount == '') ? 0 : orderedAmount;

			subtotal += parseFloat(price) * parseFloat(orderedAmount);
		});
		
		subtotal.toFixed(2);

		iva = $('#invoiceTax').prev().text().substring(5, 7) / 100;
		tax = iva * subtotal;
		tax.toFixed(2);

		total = tax + subtotal;
		total.toFixed(2);

		$('#invoiceSubtotal').text('$' + subtotal.toString());
		$('#invoiceTax').text('$' + tax.toString());
		$('#invoiceTotal').text('$' + total.toString());
	}
	/*** CALCULATE INVOICE TOTAL ***/
});