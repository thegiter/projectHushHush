<?php
	echo 'mc: ';
	
	$bps = 3.17;
	
	echo '
	bps eop: '.$bps;
	
	$so = 93.5;

	echo '
	so eop: '.$so;
	
	$ce = $bps * $so;
	
	echo '
	equity eop: '.$ce;
	
	$der = 0;

	$debt = $ce * $der;
	$cap = $debt + $ce;
	
	$lyni = 139.5;
	
	$lyie = -28.7;
	
	$lypii = $lyni - $lyie;
	
	$lyltd = 0;
	$lystd = 0;
	
	$lyd = 0;
	
	$lye = 296.8;
	
	$lycap = $lye + $lyd;
	$lypcr = $lypii / $lycap;
	
	
	
	$apb = 25.35 * (1 / (1 + 15.35 / 100)) / 10;
	
	echo '
	avr pb: '.$apb;
	
	$lyom = 49.12;
	
	$t12maom = 35.69;
	$at12maom = 23.095;
	
	$lower_aom = ($at12maom < $t12maom) ? $at12maom : $t12maom;
	
	$tlomr = ($lower_aom - $lyom) / 100;
	
	$def->apcr = $def->lypcr * (1 + $def->tlomr);
	$def->cpii = $def->cap * $def->apcr;
	
	$ctt = curl_get_contents('http://www.gurufocus.com/term/wacc/'.$ticker.'/Weighted%2BAverage%2BCost%2BOf%2BCapital%2B%2528WACC%2529/');
					
	preg_match('/Cost of Debt \=.* ([^\=]+)\%\./', $ctt, $matches);
	
	//cost of debt can be invalid sometimes, due to company paying interest when there was no debt
	//we can't just say cost of debt is 0 in this case, but without cost of debt, we can not calculate the proper interest expense
	//we will have to skip the stock by setting cost of debt to very high
	if (!$matches) {
		//check if no cost of debt
		preg_match('/Cost of Debt \=[^\=]*\=\%\./', $ctt, $matches);
		
		if (!$matches) {
			$def->wacodr = 9999.9999;
		} else {
			//error;
			die('cost of debt error');
		}
	} else {
		$def->wacodr = str_replace(',', '', $matches[1]) / 100;
	}
	
	$def->interest = $def->debt * $def->wacodr;
	$def->fi = $def->cpii - $def->interest;

	$ctt = curl_get_contents('http://www.gurufocus.com/term/ROE/'.$ticker.'/Return%2Bon%2BEquity/');
					
	preg_match('/Annual Data[\s\S]+ROE[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
	
	$def->lyroe = str_replace(',', '', $matches[8]);
	$def->slyroe = str_replace(',', '', $matches[5]);
	$def->tlyroe = str_replace(',', '', $matches[2]);
	
	$def->aroe = ($def->lyroe + $def->slyroe + $def->tlyroe) / 3;
	
	//in case roe was 0
	$def->slyroe = ($def->slyroe == 0) ? 1 : $def->slyroe;
	$def->tlyroe = ($def->tlyroe == 0) ? 1 : $def->tlyroe;
	
	$def->aroeg = (($def->lyroe - $def->slyroe) / abs($def->slyroe) + ($def->slyroe - $def->tlyroe) / abs($def->tlyroe)) / 2;
	
	preg_match('/(Quarterly|Semi-Annual) Data[\s\S]+ROE[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>/', $ctt, $matches);
	
	$def->t12maroe = str_replace(',', '', $matches[12]);
	$def->lt12maroe = str_replace(',', '', $matches[9]);
	$def->slt12maroe = str_replace(',', '', $matches[6]);
	$def->tlt12maroe = str_replace(',', '', $matches[3]);
	
	$at12maroe = ($def->t12maroe + $def->lt12maroe + $def->slt12maroe + $def->tlt12maroe) / 4;
	
	$lower_aroe = ($at12maroe < $def->t12maroe) ? $at12maroe : $def->t12maroe;
	
	//in case om was 0
	$def->lyroe = ($def->lyroe == 0) ? 1 : $def->lyroe;
	
	$def->tlroer = ($lower_aroe - $def->lyroe) / abs($def->lyroe);
	
	$def->afi = $def->fi * (1 + $def->tlroer);
	$def->fe = $def->ce + $def->afi;
	
	$ctt = curl_get_contents('http://www.gurufocus.com/term/pe/'.$ticker.'/P%252FE%2BRatio/');
					
	preg_match('/data_value"\>([^\(]+) \(As of/', $ctt, $matches);
	
	if (!$matches) {
		//check if no pe ratio, due to negative earnings
		preg_match('/data_value"\>\(As of/', $ctt, $matches);
		
		if (!$matches) {
			$def->lper = 999.9999;
		} else {
			//error;
			die('pe ratio error');
		}
	} else {
		$def->lper = str_replace(',', '', $matches[1]);
	}
	
	preg_match('/Annual Data[\s\S]+pe[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
	
	$def->aper = (str_replace(',', '', $matches[8]) + str_replace(',', '', $matches[5]) + str_replace(',', '', $matches[2])) / 3;
	
	$ctt = curl_get_contents('http://www.gurufocus.com/term/pb/'.$ticker.'/P%252FB%2BRatio/');
					
	preg_match('/data_value"\>([^\(]+) \(As of/', $ctt, $matches);
	
	if (!$matches) {
		//check if no pb ratio, due to too much liability and negative book value
		preg_match('/data_value"\>\(As of/', $ctt, $matches);
		
		if (!$matches) {
			$def->lpbr = 999.9999;
		} else {
			//error;
			die('pb ratio error');
		}
	} else {
		$def->lpbr = str_replace(',', '', $matches[1]);
	}

	preg_match('/Annual Data[\s\S]+pb[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
	
	$def->apbr = (str_replace(',', '', $matches[8]) + str_replace(',', '', $matches[5]) + str_replace(',', '', $matches[2])) / 3;
	
	$alper = $def->lper;
	$def->alpbr = $def->lpbr / $def->lpba;
	
	$alper = ($alper < 0) ? 999.9999 : $alper;
	$def->alpbr = ($def->alpbr < 0) ? 999.9999 : $def->alpbr;
	
	$def->gtlp = $alper * $def->alpbr / $car;
	$def->lpgc = ($def->gtlp > 22.5) ? 0 : 1;
	
	$aaper = $def->aper;
	$def->aapbr = $def->apbr;

	$aaper = ($aaper < 0) ? 999.9999 : $aaper;
	$def->aapbr = ($def->aapbr < 0) ? 999.9999 : $def->aapbr;
	
	$def->gtap = $aaper * $def->aapbr / $car;
	$def->apgc = ($def->gtap > 22.5) ? 0 : 1;
	
	$def->pc = $def->lpgc * $cc;
	
	$ctt = curl_get_contents('http://www.gurufocus.com/term/'.urlencode('Net Issuance of Stock').'/'.$ticker.'/Net%2BIssuance%2Bof%2BStock/');
					
	preg_match('/Annual Data[\s\S]+Net Issuance of Stock[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
	
	$def->anios = (str_replace(',', '', $matches[8]) + str_replace(',', '', $matches[5]) + str_replace(',', '', $matches[2])) / 3;
	
	//in case so eop is 0, we assume an abstract value of 1 for calculation purposes
	//if there is anios, this would result in future price significantly lower than cp
	//this is fine, because we are gonna ignore this stock due to lack of data
	if ($def->so == 0) {
		$def->so = 1;
	}
	
	$def->fp = $def->pc * ($def->fe / ($def->so + $def->anios)) * ($def->lpba * (1 + $def->tlroer));
	
	$def->fptm = $def->fp / (1 + $ir);
	
	$def->prcv = $def->pc * $def->bps * $def->lpba;
	
	$dr = .2;//discount rate is the minimum profit rate to justify the investment
	
	$def->iv = $def->fptm / (1 + $dr);//iv is how much below the fptm in order to get the profit specified by discount rate
	
	preg_match('/\<font\s+class\=\"stock_header_price\"\>\<img[^\>]+\>.*\>([^\<\%]+)\<\/font\>/', $ctt, $matches);

	$def->cp = str_replace(',', '', $matches[1]);
	
	//cpiv ratio is a non greedy ratio to buy in to get the max safe margin
	//which is 25%, meaning iv to cp must be 25% of iv
	//by setting it to 25%, we make sure we buyin as low as possible so we are safe
	//it is non greedy because we are not risking at buying at higher price
	//unless iv is 0
	if ($def->iv == 0) {
		$def->cpivr = 1;
	} else {
		$def->cpivr = ($def->cp - $def->iv) / abs($def->iv);
	}
	
	//cpcv ratio on the other hand is a non greedy ratio to sell at a lower price
	//we are making less profit thus non greedy
	//it reflects how much profit is made from cv to cp compared to cv
	if ($def->fptm == 0) {
		$def->cpfptmr = 1;
	} else {
		$def->cpfptmr = ($def->cp - $def->fptm) / abs($def->fptm);
	}
	
	$def->pow = (22.5 - $def->gtap / 2) / 22.5;
	
	$def->advice = 'hold';
	
	if ($def->cpivr < -.25) {
		$def->advice = 'buy';
	}
	
	if ($def->cpfptmr >= $dr) {
		$def->advice = 'sell';
	}
?>