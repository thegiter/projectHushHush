var toTop_mBlur_lded = false;

var toTop_mBlur_cbs = [];

include('script', '/shared/mblur/jss/mblur.js', undefined, undefined, function() {
	toTop_mBlur_lded = true;
	
	toTop_mBlur_cbs.forEach(function(cb) {
		cb();
	});
});

function toTopModule(btnId, cnrElmOrId) {
	var cnr
	
	function cnrOnload() {
		function btnOnload() {
			var btn = document.getElementById(btnId);

			var sdwImg = btn.parentNode.getElementsByClassName('sdw-img')[0];
			
			var mBlur_play = function() {	//an empty function, so that when the motion blur module script is not loaded, the scroll still happens, but nothing for motion blur happens
			};
			var mBlur_stop = function() {	//an empty function, so that when the motion blur module script is not loaded, the scroll still happens, but nothing for motion blur happens
			};
			
			function mBlurOnload() {
				var mBlur = new mBlurModule(sdwImg, 'btm');
				
				mBlur_play = function() {
					mBlur.start();
				}
				
				mBlur_stop = function() {
					mBlur.stop();
				}
			}
			
			if (toTop_mBlur_lded) {
				mBlurOnload();
			}
			else {
				toTop_mBlur_cbs.push(mBlurOnload);
			}
			
			function toTop(evt) {
				if (cnr.scrollTop > 0) {		//scrollY for window, i.e window.scrollY, scrollTop for elements
					//the transit object arguments on instantiation, step function, start value, end value, duration.
					//scroll 1000px in 1000ms (1 sec), therefore 500px in 500ms, 1500px in 1500ms
					var scrollTrans = new transit(function(value) {
						cnr.scrollTop = value;
					}, cnr.scrollTop, 0, cnr.scrollTop);
					
					//the delta method must be assaigned
					scrollTrans.delta = scrollTrans.easeOut;
					
					scrollTrans.addEventListener('end', mBlur_stop);
					
					//scroll the shit out of it
					scrollTrans.play();
					
					//play the motionblur
					mBlur_play();
				}
			}
	
			btn.addEventListener('click', toTop, false);
		}
		
		//check if btn is loaded, the button is not necessarily placed inside the container,
		//thus when the container is loaded, we still have to check for the btn
		if (document.getElementById(btnId)) {
			btnOnload();
		}
		else {
			add_eleOnload(btnId, btnOnload);
		}
	}
	
	if (typeof (cnrElmOrId) == 'string') {	//is id, because id is a string value, else assume DOM element node
		checkElmOnload(cnrElmOrId, function() {
			cnr = document.getElementById(cnrElmOrId);
			
			cnrOnload();
		});
	}
	else {
		cnr = cnrElmOrId;
		
		cnrOnload();
	}
}