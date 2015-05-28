//elm is the shadow image element direction only supports 4 directions
function mBlurModule(elm, direction) {
	this.elm = elm;
	this.cnr = this.elm.parentNode;
	
	this.direction = direction;
	
	var ttlDur = 500; //ms, 0.5sec
	
	//10%, say total duration is 100 secs, then every 10 secs, a new shadow image is drawn, so for the total duration (100 secs), 10 shadow images are drawn and used to create a single motion blur effect
	//this is because the opacity for 1 is 10%, therefore we need 10 shadow images to complete 100% and create the motion blur
	this.intDur = ttlDur * 0.1;
	
	this.clones = [];
}

mBlurModule.prototype.start = function() {
	var cnt = 1;

	var obj = this;		//because in setInterval, this refers to the window, so we store this in a variable
	
	this.intId = setInterval(function() {
		var clone = obj.clones[cnt] = obj.elm.cloneNode(true);
	
		obj.cnr.appendChild(clone);
		
		var transName = 'y';
		
		if (obj.direction == 'lft' || obj.direction == 'rgt') {
			transName = 'x';
		}
		
		clone.style.transform = 'translate'+transName+'('+cnt+'%)';
		
		mdf_cls(clone, 'remove', 'dsp-non');
		
		if (cnt >= 10) {
			clearInterval(obj.intId);
			
			return;
		}
		
		cnt++;
	},	this.intDur);
};

mBlurModule.prototype.stop = function() {
	clearInterval(this.intId);
	
	var cnt = 10;
	
	var obj = this;
	
	this.intId = setInterval(function() {
		obj.cnr.removeChild(obj.clones[cnt]);
		
		if (cnt <= 1) {
			clearInterval(obj.intId);
			
			return;
		}
		
		cnt--;
	}, this.intDur);
};