$(function() {

	$("#fecha, #fecha2").datepicker({
		dateFormat:'yy-mm-dd'
	});

	/*** All text inputs with .date class are going to have datepicker ***/
	$('input[type="text"].date').datepicker({
		dateFormat:'yy-mm-dd'
	});


});