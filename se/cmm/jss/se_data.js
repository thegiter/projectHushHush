const seData = {};

(function() {
	const dr = .4;

	const sd = seData;

	sd.style = function(elm, defName, defValue, def) {
		switch (defName) {
			case 'tlomr':
				elm.className = 'tlomr-cnr';

				if (defValue >= 1) {
					elm.dataset.positive = '1';
				} else {
					elm.dataset.positive = '0';
				}

				break;
			case 'pc':
				elm.className = 'pc-cnr';

				if (defValue != 1) {
					elm.dataset.negative = 1;
				} else {
					elm.dataset.negative = 0;
				}

				break;
			case 'dwmoe':
			case 'abdr':
			case 'bpcpr':
			case 'niosi':
				defValue = defValue * 100+'%';

				break;
			case 'ivcpr':
				elm.className = 'cpivr-cnr';

				if (defValue > dr) {
					elm.dataset.indicator = 'buy';
				} else {
					elm.dataset.indicator = '';
				}

				defValue = defValue * 100+'%';

				break;
			case 'cpfptmr':
				elm.className = 'cpfptmr-cnr';

				if (defValue >= dr) {
					elm.dataset.indicator = 'sell';
				} else {
					elm.dataset.indicator = '';
				}
			case 'pow':
			case 'powm':
				defValue = defValue * 100+'%';

				break;
			default:
		}

		elm.textContent = defValue;
	};
})();
