﻿<?php
	defined('root') or die;
	
	//often file_get_contents is disabled, using this is as a workaround
	function curl_get_contents($url) {
		if (!function_exists('curl_init')){ 
			die('CURL is not installed!');
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
	
	function siphon_stock_def_CNY($ticker, $car, $cc, $ir) {
		$dr = .2;//discount rate is the minimum profit rate to justify the investment
		$mos = -.45;//margin of safety
		$vir = 10;//value to income ratio		
				
		$ctt = curl_get_contents('http://www.gurufocus.com/term/mktcap/'.$ticker.'/Market%2BCap/');

		preg_match('/data_value"\>(CN¥|$)(.+) Mil/', $ctt, $matches);
		
		$def = new stdClass;

		$def->mc = str_replace(',', '', $matches[2]);
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/'.urlencode('Book Value Per Share').'/'.$ticker.'/Book%2BValue%2Bper%2BShare/');
						
		preg_match('/data_value"\>(CN¥|$)(.+) \(As of/', $ctt, $matches);
		
		$def->bps = str_replace(',', '', $matches[2]);
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/BS_share/'.$ticker.'/Shares%2BOutstanding%2B%2528EOP%2529/');
						
		preg_match('/data_value"\>(.+) Mil/', $ctt, $matches);
		
		$def->so = str_replace(',', '', $matches[1]);

		$def->ce = $def->bps * $def->so;
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/deb2equity/'.$ticker.'/Debt%2Bto%2BEquity/');
						
		preg_match('/data_value"\>(.+) \(As of/', $ctt, $matches);
		
		$def->der = str_replace(',', '', $matches[1]);

		$def->debt = $def->ce * $def->der;
		$def->cap = $def->debt + $def->ce;
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/'.urlencode('Net Income').'/'.$ticker.'/Net%2BIncome/');
						
		preg_match('/Annual Data[\s\S]+Net Income[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyni = str_replace(',', '', $matches[2]);
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/InterestExpense/'.$ticker.'/Interest%2BExpense/');
						
		preg_match('/Annual Data[\s\S]+Interest Expense[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyie = str_replace(',', '', $matches[2]);
		
		$def->lypii = $def->lyni - $def->lyie;
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/ROC/'.$ticker.'/Return%2Bon%2BCapital/');
						
		preg_match('/fiscal year[\s\S]+where[\s\S]+A\: Dec\.[\s\S]+A\: Dec\.[\s\S]+Long\-Term Debt[\s\S]+\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \+ \<\/td\>\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \+ \<\/td\>\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \- \<\/td\>[\s\S]+for the \<strong\>quarter\<\/strong\> that ended/', $ctt, $matches);
		
		$def->lyltd = str_replace(',', '', $matches[1]);
		$def->lystd = str_replace(',', '', $matches[3]);
		
		$def->lyd = $def->lyltd + $def->lystd;
		
		$def->lye = str_replace(',', '', $matches[5]);
		
		$def->lycap = $def->lye + $def->lyd;
		$def->lypcr = $def->lypii / $def->lycap;
		
		$capgr = $def->cap / $def->lycap;
		
		preg_match('/data_value"\>(.+)\% \(As of/', $ctt, $matches);
		
		$lroc = str_replace(',', '', $matches[1]);
		
		/*$alroc = $lroc * (1 / (1 + ($lroc - (100 / $vrr)) / 100));
		
		$crr = 100 / $alroc;
		
		$vcr = $vrr / $crr;
		
		$ver = $vcr / (1 + $def->der);*/
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/operatingmargin/'.$ticker.'/Operating%2BMargin/');

		preg_match('/fiscal year[\s\S]+Operating Income.+\(A\: Dec.[\s\S]+\<td\>\=\<\/td\>\<td\>(\-?\d+(\.\d+)?)\<\/td\>[\s\S]+for the \<strong\>quarter\<\/strong\> that ended/', $ctt, $matches);
		
		$def->lyoi = str_replace(',', '', $matches[1]);
		
		//measures the impact of operating income on net income
		$oinir = $def->lyoi / $def->lyni;
		
		$oinir = ($oinir > 1) ? 1 : $oinir;
		$oinir = ($oinir < -1) ? -1 : $oinir;
		
		preg_match('/Annual Data[\s\S]+Operating Margin[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyom = str_replace(',', '', $matches[8]);
		$def->slyom = str_replace(',', '', $matches[5]);
		$def->tlyom = str_replace(',', '', $matches[2]);
		
		//in case om was 0
		$def->slyom = ($def->slyom == 0) ? 1 : $def->slyom;
		$def->tlyom = ($def->tlyom == 0) ? 1 : $def->tlyom;
		
		$def->aomg = (($def->lyom - $def->slyom) / abs($def->slyom) + ($def->slyom - $def->tlyom) / abs($def->tlyom)) / 2;

		preg_match('/(Quarterly|Semi-Annual) Data[\s\S]+Operating Margin[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<\/tr\>/', $ctt, $matches);
		
		$def->t12maom = str_replace(',', '', $matches[12]);
		$def->lt12maom = str_replace(',', '', $matches[9]);
		$def->slt12maom = str_replace(',', '', $matches[6]);
		$def->tlt12maom = str_replace(',', '', $matches[3]);
		
		$at12maom = ($def->t12maom + $def->lt12maom + $def->slt12maom + $def->tlt12maom) / 4;
		
		$lower_aom = ($at12maom < $def->t12maom) ? $at12maom : $def->t12maom;
		
		$def->t12maom = $lower_aom;
		
		//in case om was 0
		if ($def->lyom <= 0) {
			$def->tlomr = 0;
		} else {
			$def->tlomr = $lower_aom / $def->lyom - $capgr;
		}
		
		$def->apcr = $def->lypcr * (($def->tlomr - 1) * $oinir + 1);
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
		
		$def->t12maroe = $lower_aroe;
		
		//in case om was 0
		if ($def->lyroe <= 0) {
			$def->tlroer = 0;
		} else {
			$def->tlroer = $lower_aroe / $def->lyroe;
		}
		
		$roeai = $def->fi * $def->tlroer;
		
		$def->afi = ($def->fi + $roeai) / 2;
		
		$fe = $def->ce + $def->afi;
		
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
		$def->alpbr = $def->lpbr;
		
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
		
		$cv = $def->lyni * $vir / (1 + $dr) * $car;
		$fv = $def->afi * $vir / (1 + $dr) * $car;
		
		$def->fp = ($fv / ($def->so + $def->anios));
		
		$def->fptm = $def->fp / (1 + $ir);
		
		$def->prcv = $cv / $def->so;
		
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
		
		if ($def->cpivr < $mos) {
			$def->advice = 'buy';
		}
		
		if ($def->cpfptmr >= $dr || $cc <= 0) {
			$def->advice = 'sell';
		}
		
		return $def;
	}
?>