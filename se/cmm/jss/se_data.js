var seData = {};

(function() {
	var dr = .2;
	var mos = -.45;
	
	var sd = seData;
	
	sd.style = function(elm, defName, defValue) {
		switch (defName) {
			case 'tlomr':
				elm.className = 'tlomr-cnr';
			
				if (defValue >= 0) {
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
			case 'cpivr':
				elm.className = 'cpivr-cnr';
				
				if (defValue < mos) {
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
				defValue = defValue * 100+'%';
				
				break;
			default:
		}
		
		elm.textContent = defValue;
	};
})();