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