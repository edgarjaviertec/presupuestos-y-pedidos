$(document).ready(function () {
	new Cleave('input.money', {
		numeral: true,
		onlyPositive: true
	});
});
