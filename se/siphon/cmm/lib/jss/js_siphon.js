function js_siphoned(tkr, car, cc, ir) {
	var def = {};

	return Promise.all([shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/mktcap/'+tkr+'/Market%2BCap/').then(function(xhr) {
		def.mc = parseInt(/data_value"\>CN¥(.+) Mil/.exec(xhr.responseText)[1].replace(/,/g, ''));
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/Book Value Per Share/'+tkr+'/Book%2BValue%2Bper%2BShare/').then(function(xhr) {
		def.bps = parseFloat(/data_value"\>CN¥(.+) \(As of/.exec(xhr.responseText)[1].replace(/,/g, ''));
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/BS_share/'+tkr+'/Shares%2BOutstanding%2B%2528EOP%2529/').then(function(xhr) {
		def.so = parseInt(/data_value"\>(.+) Mil/.exec(xhr.responseText)[1].replace(/,/g, ''));
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/deb2equity/'+tkr+'/Debt%2Bto%2BEquity/').then(function(xhr) {
		def.der = parseFloat(/data_value"\>(.+) \(As of/.exec(xhr.responseText)[1].replace(/,/g, ''));
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/Net Income/'+tkr+'/Net%2BIncome/').then(function(xhr) {
		def.lyni = parseInt(/Annual Data[\s\S]+Net Income[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/.exec(xhr.responseText)[2].replace(/,/g, ''));
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/InterestExpense/'+tkr+'/Interest%2BExpense/').then(function(xhr) {
		def.lyie = parseInt(/Annual Data[\s\S]+Interest Expense[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/.exec(xhr.responseText)[2].replace(/,/g, ''));
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/ROC/'+tkr+'/Return%2Bon%2BCapital/').then(function(xhr) {
		var matches = /fiscal year[\s\S]+where[\s\S]+A\: Dec\.[\s\S]+A\: Dec\.[\s\S]+Long\-Term Debt[\s\S]+\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \+ \<\/td\>\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \+ \<\/td\>\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \- \<\/td\>[\s\S]+for the \<strong\>quarter\<\/strong\> that ended/.exec(xhr.response);

		def.lyltd = parseInt(matches[1].replace(/,/g, ''));
		def.lystd = parseInt(matches[3].replace(/,/g, ''));
		def.lye = parseInt(matches[5].replace(/,/g, ''));
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/operatingmargin/'+tkr+'/Operating%2BMargin/').then(function(xhr) {
		var matches = /Annual Data[\s\S]+Operating Margin[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/.exec(xhr.response);

		def.lyom = parseFloat(matches[8].replace(/,/g, ''));
		def.slyom = parseFloat(matches[5].replace(/,/g, ''));
		def.tlyom = parseFloat(matches[2].replace(/,/g, ''));
		
		//in case om was 0
		def.slyom = (def.slyom == 0) ? 1 : def.slyom;
		def.tlyom = (def.tlyom == 0) ? 1 : def.tlyom;
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/wacc/'+tkr+'/Weighted%2BAverage%2BCost%2BOf%2BCapital%2B%2528WACC%2529/').then(function(xhr) {
		var matches = /Cost of Debt =.* ([^=]+)\%\./.exec(xhr.response);
		
		//cost of debt can be invalid sometimes, due to company paying interest when there was no debt
		//we can't just say cost of debt is 0 in this case, but without cost of debt, we can not calculate the proper interest expense
		//we will have to skip the stock by setting cost of debt to very high
		if (!matches) {
			//check if no cost of debt
			matches = /Cost of Debt \=[^\=]*\=\%\./.exec(xhr.response);
			
			if (!matches) {
				def.wacodr = 9999.9999;
			} else {
				//error;
				def.wacodr = 'cost of debt error';
			}
		} else {
			def.wacodr = parseFloat(matches[1].replace(/,/g, '')) / 100;
		}
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/ROE/'+tkr+'/Return%2Bon%2BEquity/').then(function(xhr) {
		var matches = /Annual Data[\s\S]+ROE[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/.exec(xhr.response);

		def.lyroe = parseFloat(matches[8].replace(/,/g, ''));
		def.slyroe = parseFloat(matches[5].replace(/,/g, ''));
		def.tlyroe = parseFloat(matches[2].replace(/,/g, ''));
		
		//in case roe was 0
		def.slyroe = (def.slyroe == 0) ? 1 : def.slyroe;
		def.tlyroe = (def.tlyroe == 0) ? 1 : def.tlyroe;
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/pe/'+tkr+'/P%252FE%2BRatio/').then(function(xhr) {
		var matches = /data_value"\>(.+) \(As of/.exec(xhr.response);
		
		if (!matches) {
			//check if no pe ratio, due to negative earnings
			matches = /data_value"\>\(As of/.exec(xhr.response);
			
			if (!matches) {
				def.lper = 999.9999;
			} else {
				//error;
				def.lper = 'pe ratio error';
			}
		} else {
			def.lper = parseFloat(matches[1].replace(/,/g, ''));
		}

		matches = /Annual Data[\s\S]+pe[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/.exec(xhr.response);

		def.aper = (parseFloat(matches[8].replace(/,/g, '')) + parseFloat(matches[5].replace(/,/g, '')) + parseFloat(matches[2].replace(/,/g, ''))) / 3;
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/pb/'+tkr+'/P%252FB%2BRatio/').then(function(xhr) {
		var matches = /data_value"\>(.+) \(As of/.exec(xhr.response);
		
		if (!matches) {
			//check if no pb ratio, due to too much liability and negative book value
			matches = /data_value"\>\(As of/.exec(xhr.response);
			
			if (!matches) {
				def.lpbr = 999.9999;
			} else {
				//error;
				def.lpbr = 'pb ratio error';
			}
		} else {
			def.lpbr = parseFloat(matches[1].replace(/,/g, ''));
		}
		
		var matches = /Annual Data[\s\S]+pb[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/.exec(xhr.response);

		def.apbr = (parseFloat(matches[8].replace(/,/g, '')) + parseFloat(matches[5].replace(/,/g, '')) + parseFloat(matches[2].replace(/,/g, ''))) / 3;
	}), shpsCmm.createAjax('GET', 'http://www.gurufocus.com/term/Net Issuance of Stock/'+tkr+'/Net%2BIssuance%2Bof%2BStock/').then(function(xhr) {
		var matches = /Annual Data[\s\S]+Net Issuance of Stock[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/.exec(xhr.response);

		def.anios = (parseFloat(matches[8].replace(/,/g, '')) + parseFloat(matches[5].replace(/,/g, '')) + parseFloat(matches[2].replace(/,/g, ''))) / 3;
	})]).then(function(){
		def.ce = def.bps * def.so;

		def.debt = def.ce * def.der;
		def.cap = def.debt + def.ce;
		
		def.lypii = def.lyni - def.lyie;

		def.lyd = def.lyltd + def.lystd;

		def.lycap = def.lye + def.lyd;
		def.lypcr = def.lypii / def.lycap;
		
		def.aomg = ((def.lyom - def.slyom) / Math.abs(def.slyom) + (def.slyom - def.tlyom) / Math.abs(def.tlyom)) / 2;

		def.apcr = def.lypcr * (1 + def.aomg);
		def.cpii = def.cap * def.apcr;
		
		def.interest = def.debt * def.wacodr;
		def.fi = def.cpii - def.interest;

		def.aroeg = ((def.lyroe - def.slyroe) / Math.abs(def.slyroe) + (def.slyroe - def.tlyroe) / Math.abs(def.tlyroe)) / 2;
		
		def.afi = def.fi * (1 + def.aroeg);
		def.fe = def.ce + def.afi;
		
		def.gtlp = def.lper / car * (def.lpbr / car);
		def.lpgc = (def.gtlp > 22.5) ? 0 : 1;
		
		def.gtap = def.aper / car * (def.apbr / car);
		def.apgc = (def.gtap > 22.5) ? 0 : 1;
		
		def.pc = def.lpgc * def.apgc * cc;
		
		//in case so eop is 0, we assume an abstract value of 1 for calculation purposes
		//if there is anios, this would result in future price significantly lower than cp
		//this is fine, because we are gonna ignore this stock due to lack of data
		if (def.so == 0) {
			def.so = 1;
		}
		
		def.fp = def.pc * (def.fe / (def.so + def.anios)) * def.apbr;
		
		def.fptm = def.fp / (1 + ir);
		
		def.prcv = def.ce * def.apbr * cc * def.apgc / def.so;
		
		var dr = .2;//discount rate is the minimum profit rate to justify the investment
		
		def.iv = def.fptm / (1 + dr);//iv is how much below the fptm in order to get the profit specified by discount rate
		
		var cp = def.mc / def.so;
		
		//cpiv ratio is a non greedy ratio to buy in to get the max safe margin
		//which is 25%, meaning iv to cp must be 25% of iv
		//by setting it to 25%, we make sure we buyin as low as possible so we are safe
		//it is non greedy because we are not risking at buying at higher price
		//unless iv is 0
		if (def.iv == 0) {
			def.cpivr = 1;
		} else {
			def.cpivr = (cp - def.iv) / Math.abs(def.iv);
		}
		
		//cpcv ratio on the other hand is a non greedy ratio to sell at a lower price
		//we are making less profit thus non greedy
		//it reflects how much profit is made from cv to cp compared to cv
		if (def.prcv == 0) {
			def.cpcvr = 1;
		} else {
			def.cpcvr = (cp - def.prcv) / Math.abs(def.prcv);
		}
		
		def.pow = (22.5 - def.gtap / 2) / 22.5;
		
		def.advice = 'hold';
		
		if (def.cpivr < -.25) {
			def.advice = 'buy';
		}
		
		if (def.cpcvr >= dr) {
			def.advice = 'sell';
		}
		
		return def;
	});
}