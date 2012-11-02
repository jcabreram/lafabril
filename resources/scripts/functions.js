function getMoneyFormat(money)
{
	money = money.toFixed(2);
	var moneyString = money.toString();
	var moneyStringParts = moneyString.split('.');
	var integerPart = moneyStringParts[0];
	var floatPart = moneyStringParts[1];
	var newIntegerPart = [];

	for (var i = integerPart.length - 1, j = 1; i >= 0; i--, j++) {
		newIntegerPart.push(integerPart.charAt(i));

		if (j === 3 && i !== 0) {
			newIntegerPart.push(',');
			j = 1;
		}
	}

	return newIntegerPart.reverse().join('') + '.' + floatPart;
}

function deletePayment()
{
	$(this).parent().parent().remove();

	// Recalculte bill balance
	calculateBillBalance();

	var cards = $('input[name^="cards"]');
	var checks = $('input[name^="checks"]');
	var total = cards.length + checks.length;

	if (total === 0) {
		$('input[name="cash"]').removeAttr('readonly');
	}

	return false;
}

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