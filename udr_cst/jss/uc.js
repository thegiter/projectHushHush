imgPreload('/udr_cst/imgs/msg_arrow.gif');			// udr_cst may be called by any other files, use path relative to root

var val_tick_id = 'val-tick';
var msg_cnr_id = 'msg-cnr';
var jqmsgdiv = $('#'+msg_cnr_id);
var ipt_ure_id = 'ipt-ure';

window.addEventListener('load', function() {
	var msgdiv = document.getElementById(msg_cnr_id);
	var msg_cnr_opaTwn = new OpacityTween(msgdiv, Tween.regularEaseOut, 0, 100, 0.5);
	var msg_fadeOut_onFinished = new Object();
	
	msg_fadeOut_onFinished.onMotionFinished = function() {
		mdf_cls(msg_cnr_id, 'add', 'vsb-hid');
	};

	function msg_fadeOut() {
		document.getElementById(ipt_ure_id).removeEventListener('input', msg_fadeOut, false);
		
		msg_cnr_opaTwn.addListener(msg_fadeOut_onFinished);
		
		msg_cnr_opaTwn.continueTo(0, getStyle(msg_cnr_id, 'opacity'));
	}
	
	function showmsg(field, msg) {
		document.getElementById(val_tick_id).style.visibility = "hidden";
		document.getElementById('msg').textContent = msg;

		var jqfield= $('#'+field.id);
		var fieldospos = jqfield.offset();						//jq.offset() is used because its position relative to the body is needed
		var ostop = (msgdiv.offsetHeight-field.offsetHeight)/2;

		msgdiv.style.top = (fieldospos.top-ostop)+'px';
		msgdiv.style.left = (fieldospos.left+field.offsetWidth)+'px';
		
		mdf_cls(msg_cnr_id, 'remove', 'vsb-hid');				//use vsb-hid because dsp-non would make width and height 0
		
		msg_cnr_opaTwn.removeListener(msg_fadeOut_onFinished);
		
		msg_cnr_opaTwn.continueTo(100, (1-getStyle(msg_cnr_id, 'opacity'))*0.5);
		
		document.getElementById(ipt_ure_id).addEventListener('input', msg_fadeOut, false);
		
		return false;
	}
	
	function show_tick() {
		msg_cnr_opaTwn.continueTo(0, getStyle(msg_cnr_id, 'opacity'));
		
		document.getElementById(val_tick_id).style.visibility = "visible";
		
		return true;
	}

	function validate(field) {
		if (field.value.length > 0) {
			if ( field.value.charAt(0) == '.') {
				return showmsg(field, 'The local part of a email address can not start with a ".".');
			}
			
			var foundq = field.value.indexOf('"');
			var foundcountq = 0;
		
			while (foundq != -1) {
				foundcountq++;
				foundq = field.value.indexOf('"', foundq+1);
			}

			if (foundcountq%2 == 0) {	//% is the modulus operator which is used to find the remainder, this statement checks if foundcountq is an even number
				var sspos;
				var quote = '';
				var local = '.';
			
				if (foundcountq > 0) {
					sspos = field.value.lastIndexOf('"');					//position of last quotation mark
					quote = ' outside quotation marks';
					local = ' after the local part of the email address.';
				}
				else {
					sspos = 0;
				}
			
				var atpos = field.value.indexOf('@', sspos);	//position of @ after last quotation mark
			
				if (atpos == -1) {					
					return showmsg(field, 'Missing "@"'+local);
				}
				else{
					var local = field.value.substr(0, atpos);
				
					if (local.length == 0) {
						return showmsg(field, 'The local part of the email address can not be empty.');
					}
				
					var splittedl = local.split('"');
					var fbdcharsl = /[^\w!#\44%&'\52\53\55\57=\77\136`\173\174\175~\56]/;
					var cscdots = /\56{2,}/;
				
					if (local.length > 64) {
						return showmsg(field, 'The local part of the email address is too long. Maximum length of 64 characters allowed.');
					}

					for (i=0; i<foundcountq+1; i+=2) { //checks local part outside quotes
						if (fbdcharsl.test(splittedl[i])) {
							return showmsg(field, 'Forbidden character found'+quote+" in the local-part.");
						}
						if (cscdots.test(splittedl[i])) {
							return showmsg(field, 'Consecutive dots are not allowed'+quote+".");
						}
					}
				
					if (local.charAt(local.length-1) == '.') { //local part end dot check
						return showmsg(field, 'The local part of a email address can not end with a ".".');
					}
				
					var host = field.value.substr(atpos+1);

					if (host.length == 0) {
						return showmsg(field, 'Domain can not be empty.');
					}
				
					var foundd = host.indexOf('.');
				
					if (foundd == -1) {
						return showmsg(field, 'Invalid domain. Missing ".".');
					}
					else {
						var foundcountd = 0;
		
						while (foundd != -1) {
							foundcountd++;
							foundd = host.indexOf('.', foundd+1);
						}
				
						var splittedh = host.split('.');
					
						for (i=0; i<=foundcountd; i++) {
							if (splittedh[i] == '') {
								return showmsg(field, 'Empty lable is not allowed, thus a dot can not be the first or last character of a domain or next to each other.');
							}
						
							if (splittedh[i].length > 63) {
								return showmsg(field, 'The lable is too long. Maximum length of 63 characters allowed.');
							}
						
							var fbdcharsh = /[^a-z\d\55]/i;
						
							if (fbdcharsh.test(splittedh[i])) {
								return showmsg(field, 'Forbidden character found in domain.');
							}
						
							if ((splittedh[i].charAt(0) == '-') || (splittedh[i].charAt(splittedh[i].length-1) == '-')) {
								return showmsg(field, 'Lable can not start or end with a "-".');
							}
						}
					}
				}
			}
			else {
				return showmsg(field, 'Quotation mark not ended, email address not complete.');
			}
			
			return show_tick();
		}
		else {
			document.getElementById(val_tick_id).style.visibility = "hidden";
			
			return 'empty';
		}
	}
	
	function subscribe() {
		var emailfield = document.getElementById(ipt_ure_id);
		var submsgp = document.getElementById('sub-msg');

		if (validate(emailfield) == 'empty') {
			submsgp.innerHTML = "It's OK, I don't have an email, too.";
			
			return false;
		}
		else if (!validate(emailfield)) {
			submsgp.innerHTML = "Whatever, I don't have an email, anyway.";
			
			emailfield.focus();
			
			return false;
		}
		
		submsgp.innerHTML = "Seriously? You want to subscribe? What is wrong with you?";
		
		return false;
	}

	document.getElementById(ipt_ure_id).addEventListener('blur', function() {
		validate(this);
	}, false);
	document.getElementById('sub-frm').addEventListener('submit', function(evt) {
		subscribe();
		
		evt.preventDefault(); // event.returnValue = false; for MSIE.
	}, false);
}, false);