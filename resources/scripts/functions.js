function isNumber(n)
{
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function validPrecision(n)
{
	numberParts = n.toString().split('.');

	if (numberParts.length === 2 && numberParts[1].length > 2) {
		return false;
	}

	return true;
}

function getMoneyFormat(money)
{
	money = Math.round(money * 100) / 100;
	var moneyString = money.toString();
	var moneyStringParts = moneyString.split('.');

	if (moneyStringParts.length === 1) {
		moneyStringParts[1] = '00';
	} else if (moneyStringParts[1].length === 1) {
		moneyStringParts[1] += '0';
	}

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
	var total = parseFloat($('input[name="billTotal"]').val());
	var cash = $('input[name="cash"]').val();
		cash = !isNumber(cash) || cash === '' ? 0.0 : parseFloat(cash);

	if (cash < 0.0) {
		cash = 0.0;
	}

	if (!validPrecision(cash)) {
		cash = 0.0;
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

	if (Math.abs(balance) < 0.01) {
		change = 0;
		balance = 0;
	} else if (balance < 0.0) {
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