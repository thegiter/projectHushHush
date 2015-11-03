shpsCmm.domReady().then(function() {
	var cir = .012;
	
	document.getElementById('cir-cnr').textContent = cir * 100+'%';
	
	var tickIpt = document.getElementById('tick-ipt');
	var carIpt = document.getElementById('car-ipt');
	var ccIpt = document.getElementById('cc-ipt-true');
	var btn = document.getElementById('run-btn');
	var msgCnr = document.getElementById('msg-cnr');
	
	var mcCnr = document.getElementById('mc-cnr');
	var mc;
	
	var bpsCnr = document.getElementById('bps-cnr');
	var bps;
	var soCnr = document.getElementById('so-cnr');
	var so;
	
	var ceCnr = document.getElementById('ce-cnr');
	var ce;
	
	var derCnr = document.getElementById('der-cnr');
	var der;
	
	var debtCnr = document.getElementById('debt-cnr');
	var debt;
	var capCnr = document.getElementById('cap-cnr');
	var cap;
	
	var lyniCnr = document.getElementById('lyni-cnr');
	var lyni;
	var lyieCnr = document.getElementById('lyie-cnr');
	var lyie;
	
	var lypiiCnr = document.getElementById('lypii-cnr');
	var lypii;
	
	var lyltdCnr = document.getElementById('lyltd-cnr');
	var lyltd;
	var lystdCnr = document.getElementById('lystd-cnr');
	var lystd;
	var lyeCnr = document.getElementById('lye-cnr');
	var lye;
	
	var lydCnr = document.getElementById('lyd-cnr');
	var lyd;
	var lycapCnr = document.getElementById('lycap-cnr');
	var lyd;
	var lypcrCnr = document.getElementById('lypcr-cnr');
	var lypcr;
	
	var lyomCnr = document.getElementById('lyom-cnr');
	var lyom;
	var slyomCnr = document.getElementById('slyom-cnr');
	var slyom;
	var tlyomCnr = document.getElementById('tlyom-cnr');
	var tlyom;
	
	var aomgCnr = document.getElementById('aomg-cnr');
	var aomg;
	var apcrCnr = document.getElementById('apcr-cnr');
	var apcr;
	var cpiiCnr = document.getElementById('cpii-cnr');
	var cpii;
	
	var wacodrCnr = document.getElementById('wacodr-cnr');
	var wacodr;
	
	var intCnr = document.getElementById('int-cnr');
	var interest;
	var fiCnr = document.getElementById('fi-cnr');
	var fi;
	
	var lyroeCnr = document.getElementById('lyroe-cnr');
	var lyroe;
	var slyroeCnr = document.getElementById('slyroe-cnr');
	var slyroe;
	var tlyroeCnr = document.getElementById('tlyroe-cnr');
	var tlyroe;
	
	var aroegCnr = document.getElementById('aroeg-cnr');
	var aroeg;
	var afiCnr = document.getElementById('afi-cnr');
	var afi;
	var feCnr = document.getElementById('fe-cnr');
	var fe;
	
	var lperCnr = document.getElementById('lper-cnr');
	var lper;
	var lpbrCnr = document.getElementById('lpbr-cnr');
	var lpbr;
	
	var gtlpCnr = document.getElementById('gtlp-cnr');
	var gtlp;
	var lpgcCnr = document.getElementById('lpgc-cnr');
	var lpgc;
	
	var aperCnr = document.getElementById('aper-cnr');
	var aper;
	var apbrCnr = document.getElementById('apbr-cnr');
	var apbr;
	
	var gtapCnr = document.getElementById('gtap-cnr');
	var gtap;
	var apgcCnr = document.getElementById('apgc-cnr');
	var apgc;
	
	var pcCnr = document.getElementById('pc-cnr');
	var pc;
	
	var aniosCnr = document.getElementById('anios-cnr');
	var anios;
	
	var fpCnr = document.getElementById('fp-cnr');
	var fp;
	var fptmCnr = document.getElementById('fptm-cnr');
	var fptm;
	
	var prcvCnr = document.getElementById('prcv-cnr');
	var prcv;
	
	var ivCnr = document.getElementById('iv-cnr');
	var iv;
	
	var cpivrCnr = document.getElementById('cpivr-cnr');
	var cpivr;
	
	function run() {
		msgCnr.textContent = 'Running...';
		msgCnr.dataset.state = 'running';
		
		var ticker = tickIpt.value;
		var car = carIpt.value;
		var cc = ccIpt.checked ? 1 : 0;
		
		Promise.all([shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/mktcap/'+ticker+'/Market%2BCap/').then(function(xhr) {
			mcCnr.textContent = mc = parseInt(/data_value"\>CN¥(.+) Mil/.exec(xhr.responseText)[1].replace(/,/g, ''));
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/Book Value Per Share/'+ticker+'/Book%2BValue%2Bper%2BShare/').then(function(xhr) {
			bpsCnr.textContent = bps = parseFloat(/data_value"\>CN¥(.+) \(As of/.exec(xhr.responseText)[1].replace(/,/g, ''));
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/BS_share/'+ticker+'/Shares%2BOutstanding%2B%2528EOP%2529/').then(function(xhr) {
			soCnr.textContent = so = parseInt(/data_value"\>(.+) Mil/.exec(xhr.responseText)[1].replace(/,/g, ''));
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/deb2equity/'+ticker+'/Debt%2Bto%2BEquity/').then(function(xhr) {
			derCnr.textContent = der = parseFloat(/data_value"\>(.+) \(As of/.exec(xhr.responseText)[1].replace(/,/g, ''));
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/Net Income/'+ticker+'/Net%2BIncome/').then(function(xhr) {
			lyniCnr.textContent = lyni = parseInt(/Annual Data[^]+Net Income[^]+\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[^]+Quarterly Data/.exec(xhr.responseText)[2].replace(/,/g, ''));
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/InterestExpense/'+ticker+'/Interest%2BExpense/').then(function(xhr) {
			lyieCnr.textContent = lyie = parseInt(/Annual Data[^]+Interest Expense[^]+\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[^]+Quarterly Data/.exec(xhr.responseText)[2].replace(/,/g, ''));
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/ROC/'+ticker+'/Return%2Bon%2BCapital/').then(function(xhr) {
			var matches = /fiscal year[^]+where[^]+\<div[^]+\/div\>[^]+A: Dec\.[^]+Long\-Term Debt[^]+tr\>[^]+=.+\<td\>(.+)\<\/td.*\+.+\<td>(.+)\<\/td.*\+.*\<td\>(.*)\<\/td.*\-[^]+for the \<strong\>quarter\<\/strong\> that ended/.exec(xhr.response);

			lyltdCnr.textContent = lyltd = parseInt(matches[1].replace(/,/g, ''));
			lystdCnr.textContent = lystd = parseInt(matches[2].replace(/,/g, ''));
			lyeCnr.textContent = lye = parseInt(matches[3].replace(/,/g, ''));
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/operatingmargin/'+ticker+'/Operating%2BMargin/').then(function(xhr) {
			var matches = /Annual Data[^]+Operating Margin[^]+\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<\/tr\>[^]+Quarterly Data/.exec(xhr.response);

			lyomCnr.textContent = lyom = parseFloat(matches[8].replace(/,/g, ''));
			slyomCnr.textContent = slyom = parseFloat(matches[5].replace(/,/g, ''));
			tlyomCnr.textContent = tlyom = parseFloat(matches[2].replace(/,/g, ''));
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/wacc/'+ticker+'/Weighted%2BAverage%2BCost%2BOf%2BCapital%2B%2528WACC%2529/').then(function(xhr) {
			wacodrCnr.textContent = wacodr = parseFloat(/Cost of Debt =.* ([^=]+)\%\./.exec(xhr.response)[1].replace(/,/g, '')) / 100;
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/ROE/'+ticker+'/Return%2Bon%2BEquity/').then(function(xhr) {
			var matches = /Annual Data[^]+ROE[^]+\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[^]+Quarterly Data/.exec(xhr.response);

			lyroeCnr.textContent = lyroe = parseFloat(matches[8].replace(/,/g, ''));
			slyroeCnr.textContent = slyroe = parseFloat(matches[5].replace(/,/g, ''));
			tlyroeCnr.textContent = tlyroe = parseFloat(matches[2].replace(/,/g, ''));
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/pe/'+ticker+'/P%252FE%2BRatio/').then(function(xhr) {
			lperCnr.textContent = lper = parseFloat(/data_value"\>(.+) \(As of/.exec(xhr.response)[1].replace(/,/g, ''));
			
			var matches = /Annual Data[^]+pe[^]+\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[^]+Quarterly Data/.exec(xhr.response);

			aperCnr.textContent = aper = (parseFloat(matches[8].replace(/,/g, '')) + parseFloat(matches[5].replace(/,/g, '')) + parseFloat(matches[2].replace(/,/g, ''))) / 3;
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/pb/'+ticker+'/P%252FB%2BRatio/').then(function(xhr) {
			lpbrCnr.textContent = lpbr = parseFloat(/data_value"\>(.+) \(As of/.exec(xhr.response)[1].replace(/,/g, ''));
			
			var matches = /Annual Data[^]+pb[^]+\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[^]+Quarterly Data/.exec(xhr.response);

			apbrCnr.textContent = apbr = (parseFloat(matches[8].replace(/,/g, '')) + parseFloat(matches[5].replace(/,/g, '')) + parseFloat(matches[2].replace(/,/g, ''))) / 3;
		}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/Net Issuance of Stock/'+ticker+'/Net%2BIssuance%2Bof%2BStock/').then(function(xhr) {
			var matches = /Annual Data[^]+Net Issuance of Stock[^]+\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font.*\>)?(.+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[^]+Quarterly Data/.exec(xhr.response);

			aniosCnr.textContent = anios = (parseFloat(matches[8].replace(/,/g, '')) + parseFloat(matches[5].replace(/,/g, '')) + parseFloat(matches[2].replace(/,/g, ''))) / 3;
		})]).then(function(){
			ceCnr.textContent = ce = bps * so;
			
			debtCnr.textContent = debt = ce * der;
			capCnr.textContent = cap = debt + ce;
			
			lypiiCnr.textContent = lypii = lyni - lyie;
			
			lydCnr.textContent = lyd = lyltd + lystd;
			lycapCnr.textContent = lycap = lye + lyd;
			lypcrCnr.textContent = lypcr = lypii / lycap;
			
			aomgCnr.textContent = aomg = ((lyom - slyom) / Math.abs(slyom) + (slyom - tlyom) / Math.abs(tlyom)) / 2;
			
			if (aomg >= 0) {
				aomgCnr.dataset.positive = '1';
			} else {
				aomgCnr.dataset.positive = '0';
			}
			
			apcrCnr.textContent = apcr = lypcr * (1 + aomg);
			cpiiCnr.textContent = cpii = cap * apcr;
			
			intCnr.textContent = interest = debt * wacodr;
			fiCnr.textContent = fi = cpii - interest;
			
			aroegCnr.textContent = aroeg = ((lyroe - slyroe) / Math.abs(slyroe) + (slyroe - tlyroe) / Math.abs(tlyroe)) / 2;
			
			afiCnr.textContent = afi = fi * (1 + aroeg);
			feCnr.textContent = fe = ce + afi;
			
			gtlpCnr.textContent = gtlp = lper / car * (lpbr / car);
			lpgcCnr.textContent = lpgc = (gtlp > 22.5) ? 0 : 1;
			
			gtapCnr.textContent = gtap = aper / car * (apbr / car);
			apgcCnr.textContent = apgc = (gtap > 22.5) ? 0 : 1;
			
			pcCnr.textContent = pc = lpgc * apgc * cc;
			
			if (pc != 1) {
				pcCnr.dataset.negative = 1;
			} else {
				pcCnr.dataset.negative = 0;
			}
			
			fpCnr.textContent = fp = pc * (fe / (so + anios)) * apbr;
			
			fptmCnr.textContent = fptm = fp / (1 + cir);
			
			prcvCnr.textContent = prcv = ce * apbr * cc * apgc / so;
			
			ivCnr.textContent = iv = fptm * .75;
			
			var cp = mc / so;
			
			cpivrCnr.textContent = ((cpivr = (cp - iv) / cp) * 100)+'%';
			
			if (cpivr <= -.25) {
				cpivrCnr.dataset.indicator = 'buy';
			} else if (cpivr > .25) {
				cpivrCnr.dataset.indicator = 'sell';
			} else {
				cpivrCnr.dataset.indicator = 'hold';
			}
			
			msgCnr.textContent = 'Done!';
			msgCnr.dataset.state = 'done';
		});
	}
	
	btn.addEventListener('click', run);
});