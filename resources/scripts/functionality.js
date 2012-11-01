$(function() {
	getMoneyFormat(1234.1);

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
		var productPrice = 0.0, productsCost = 0.0;

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
				productsCost = parseFloat(userAmount) * parseFloat(productPrice);
				subtotal += productsCost;

				tr.removeAttr('style');
				tr.find('td').last().text('$' + getMoneyFormat(productsCost));
			} else {
				tr.attr('style', 'color: red');
			}
		});

		VAT = $('.invoiceVAT').first().val();
		tax = VAT * subtotal;

		total = tax + subtotal;

		$('.invoiceSubtotal').first().text('$' + getMoneyFormat(subtotal));
		$('.invoiceTax').first().text('$' + getMoneyFormat(tax));
		$('.invoiceTotal').first().text('$' + getMoneyFormat(total));
	}
	/*** CALCULATE INVOICE TOTAL ***/


	/*** SHOW PAYMENT METHOD FORM ***/
	$('select[name="paymentMethod"]').change(function() {
		var select = $(this);

		if (parseFloat($('input[name="billBalance"]').val()) === 0.0) {
			select.val('');
			return;
		}

		var divId = '';

		switch (select.val()) {
			case 'card':
				divId = '#ingresar_tarjeta';
				break;

			case 'check':
				divId = '#ingresar_cheque';
				break;
		}

		$.facebox({ div: divId })

		select.val('');
	});
	/*** SHOW PAYMENT METHOD FORM ***/


	/*** BIND FACEBOX EVENTS ***/
	$(document).bind('reveal.facebox', function() {
		var billBalance = $('input[name="billBalance"]');
		var cardPaymentAmount = $('input[name="cardPaymentAmount"]').last();
		var checkPaymentAmount = $('input[name="checkPaymentAmount"]').last();
		
		// Bill balance as default to the payment amount
		cardPaymentAmount.val(billBalance.val());
		checkPaymentAmount.val(billBalance.val());

		$('.addCard').last().click(addCard);
		$('.addCheck').last().click(addCheck);
	});
	/*** BIND FACEBOX EVENTS ***/


	/*** ADD CARD PAYMENT TO BILL ***/
	function addCard() {
		var bank = $('select[name="cardBank"]').last();
		var cardNumber = $('input[name="cardNumber"]').last();
		var cardNumberVal = cardNumber.val();
		var billBalance = $('input[name="billBalance"]');
		var cardPaymentAmount = $('input[name="cardPaymentAmount"]').last();

		/*** VALIDATION ***/
		if (bank.val() === '') {
			alert('Escoge un banco.');
			return false;
		}

		if (cardNumberVal.length === 0) {
			alert('Escribe un número de tarjeta.');
			return false;
		}

		if (cardNumberVal.length < 14) {
			alert('Número de tarjeta demasiado corto.');
			return false;
		}

		if (cardNumberVal.length > 19) {
			alert('Número de tarjeta demasiado largo.');
			return false;
		}

		if (!/^\d+$/.test(cardNumberVal)) {
			alert('El número de tarjeta sólo puede contener números.');
			return false;
		}

		if ($('input[name="cards['+bank.val()+']['+cardNumberVal+']"]').length === 1) {
			alert('Esta tarjeta ya ha sido agregada.');
			return false;
		}
		
		if (parseFloat(cardPaymentAmount.val()) > parseFloat(billBalance.val())) {
			alert('No puedes pagar con la tarjeta más del saldo.');
			return false;
		}
		/*** VALIDATION ***/

		bankName = bank.find('option[value="' + bank.val() + '"]').text();
		var cardLast4Digits = cardNumberVal.substring(cardNumberVal.length - 4);

		var tr = $('<tr></tr>');
		tr.append('<td></td>');
		tr.append('<td></td>');
		tr.append('<td class="textAlign-right"><strong>Tarjeta ' + bankName + ' ***' + cardLast4Digits + '</strong>:</td>');
		tr.append('<td class="textAlign-right"><input type="hidden" name="cards['+bank.val()+']['+cardNumberVal+']" value="'+cardPaymentAmount.val()+'" /> $ '+getMoneyFormat(parseFloat(cardPaymentAmount.val()))+'</td>');
		$('.paymentMethod').last().before(tr);

		// We deactivate the cash input
		$('input[name="cash"]').attr('readonly', 'readonly');

		// Recalculate the bill balance
		calculateBillBalance();

		// First close facebox and after clean
		$(document).trigger('close.facebox');

		$(document).bind('afterClose.facebox', function() {
			// Clean form before leaving
			bank.val('');
			cardNumber.val('');
		});
	}
	/*** ADD CARD PAYMENT TO BILL ***/


	/*** ADD CARD PAYMENT TO BILL ***/
	function addCheck() {
		var bank = $('select[name="checkBank"]').last();
		var checkNumber = $('input[name="checkNumber"]').last();
		var checkNumberVal = checkNumber.val();
		var billBalance = $('input[name="billBalance"]');
		var checkPaymentAmount = $('input[name="checkPaymentAmount"]').last();

		/*** VALIDATION ***/
		if (bank.val() === '') {
			alert('Escoge un banco.');
			return false;
		}

		if (checkNumberVal.length === 0) {
			alert('Escribe un número de cheque.');
			return false;
		}

		if (!/^\d+$/.test(checkNumberVal)) {
			alert('El número de cheque sólo puede contener números.');
			return false;
		}

		if (checkNumberVal.length < 4) {
			alert('El número de cheque debe ser de al menos 4 dígitos.');
			return false;
		}

		if ($('input[name="checks['+bank.val()+']['+checkNumberVal+']"]').length === 1) {
			alert('Este cheque ya ha sido agregada.');
			return false;
		}

		if (isNaN(checkPaymentAmount.val()) || checkPaymentAmount.val() === '') {
			alert('Escribe una cantidad válida.');
			return false;
		}
		
		if (parseFloat(checkPaymentAmount.val()) > parseFloat(billBalance.val())) {
			alert('No puedes pagar con el cheque más del saldo.');
			return false;
		}
		/*** VALIDATION ***/

		bankName = bank.find('option[value="' + bank.val() + '"]').text();
		var checkLast4Digits = checkNumberVal.substring(checkNumberVal.length - 4);

		var tr = $('<tr></tr>');
		tr.append('<td></td>');
		tr.append('<td></td>');
		tr.append('<td class="textAlign-right"><strong>Cheque ' + bankName + ' ***' + checkLast4Digits + '</strong>:</td>');
		tr.append('<td class="textAlign-right"><input type="hidden" name="checks['+bank.val()+']['+checkNumberVal+']" value="'+checkPaymentAmount.val()+'" /> $ '+getMoneyFormat(parseFloat(checkPaymentAmount.val()))+'</td>');
		$('.paymentMethod').last().before(tr);

		// We deactivate the cash input
		$('input[name="cash"]').attr('readonly', 'readonly');

		// Recalculate the bill balance
		calculateBillBalance();

		// First close facebox and after clean
		$(document).trigger('close.facebox');

		$(document).bind('afterClose.facebox', function() {
			// Clean form before leaving
			bank.val('');
			checkNumber.val('');
		});
	}
	/*** ADD CARD PAYMENT TO BILL ***/


	/*** CALCULATE BILL BALANCE ***/
	calculateBillBalance();

	// We watch the input for a change
	$('input[name="cash"]').bind('input', calculateBillBalance);

	function calculateBillBalance()
	{
		var total = parseFloat($('input[name="billTotal"]').val()),
			cash = $('input[name="cash"]').val(),
			cash = isNaN(cash) || cash === '' ? 0 : parseFloat(cash);

		if (cash < 0) {
			cash = 0;
		}


		/*** PAID WITH CARDS ***/
		var paidWithCards = 0.0,
			cards = $('input[name^="cards"]');

		cards.each(function(){
			paidWithCards += parseFloat($(this).val()); 
		})
		/*** PAID WITH CARDS ***/


		/*** PAID WITH CHECKS ***/
		var paidWithChecks = 0.0,
			cards = $('input[name^="checks"]');

		cards.each(function(){
			paidWithChecks += parseFloat($(this).val()); 
		})
		/*** PAID WITH CHECKS ***/


		var paid = cash + paidWithCards + paidWithChecks,
			balance = total - paid,
			change = 0.0;

		if (balance < 0) {
			change = balance * -1;
			balance = 0;
		}

		var billBalance = $('.billBalance'),
			billChange = $('.billChange');

		billBalance.first().empty()
		.append('<input type="hidden" name="billBalance" value="'+balance+'" />')
		.append('$' + getMoneyFormat(balance));

		billChange.first().empty()
		.append('<input type="hidden" name="billChange" value="'+change+'" />')
		.append('$' + getMoneyFormat(change));
	}
	/*** CALCULATE BILL BALANCE ***/
});