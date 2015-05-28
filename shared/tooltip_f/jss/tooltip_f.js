function tooltipFModule() {
}

tooltipFModule.elm = document.createElement('div');

tooltipFModule.elm.classList.add('disabled', 'dsp-non', 'opa-0', 'tooltip-f', 'tooltip-f-wh-trans', 'zi-hvr');

tooltipFModule.elm.addEventListener('transitionend', function(evt) {
	if (this.classList.contains('opa-0')) {
		this.classList.add('dsp-non');
	} else {
		if (this.classList.contains('tooltip-f-wh-trans')) {
			this.classList.add('tooltip-f-h-trans');
			this.classList.remove('tooltip-f-wh-trans');
		} else {
			this.classList.remove('tooltip-f-h-trans');
		
			tooltipFModule.trans.play();
		}
	}
});

domReadyDo(function() {
	document.getElementsByTagName('body')[0].appendChild(tooltipFModule.elm);
});

tooltipFModule.trans = new transit(function(value) {
	tooltipFModule.elm.style.borderImage = 'repeating-linear-gradient(135deg, hsla(186, 87%, 80%, .9) '+value+'%, hsla(186, 87%, 40%, .3) '+(value + 100)+'%, hsla(186, 87%, 80%, .9) '+(value + 200)+'%) 1';
}, 0, 200, 10000);

tooltipFModule.trans.delta = tooltipFModule.trans.quadEaseInOut;

tooltipFModule.trans.addEventListener('end', function() {
	tooltipFModule.trans.play();
});

tooltipFModule.mouseLeave = function(evt) {
	elm = tooltipFModule.elm;
	
	elm.classList.add('tooltip-f-wh-trans', 'opa-0');
	elm.classList.remove('tooltip-f-h-trans');
	
	tooltipFModule.trans.pause();
};

tooltipFModule.mouseEnter = function(evt) {
	var tFM = tooltipFModule;
	
	if (tFM.elm.textContent != this.tooltipF.tooltip) {
		tFM.elm.textContent = this.tooltipF.tooltip;
	}
	
	tFM.elm.classList.remove('dsp-non');
	
	tFM.elm.offsetHeight;
	
	tFM.elm.classList.remove('opa-0');
};

tooltipFModule.mouseMove = function(evt) {
	var tFM = tooltipFModule;
	var os = .5 * getRemPx();//offset, .5rem
	
	follow_mouse(evt, tFM.elm, os, os, 'above', 'left', true, 'btm', 'rgt');
}

tooltipFModule.addTo = function(elm, tooltip) {
	elm.tooltipF = {};
	
	elm.tooltipF.tooltip = tooltip;
	
	optMseMveLsnr.add(elm, this.mouseMove);
	
	elm.addEventListener('mouseleave', this.mouseLeave);
	elm.addEventListener('mouseenter', this.mouseEnter);
};