<?php
	defined('root') or die;
	
	if (!function_exists('curl_init')){ 
		die('CURL is not installed!');
	}
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	//often file_get_contents is disabled, using this is as a workaround
	function curl_get_contents($url) {
		global $ch;
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		return curl_exec($ch);
	}
	
	function projectedIncome($oinir, $opcr, $tlomr, $cap, $debt, $wacodr, $tlroer) {
		$result = new stdClass;

		$ppcr = $opcr * $oinir * $tlomr;//0.35 * .96 = .336

		$ppii = $cap * $ppcr;//1581156 * .336 = 531268
		
		$pinterest = $debt * $wacodr;//550090 * .54 = 297048
		$result->pinterest = $pinterest;
		$pi = $ppii - $pinterest;//531268 - 297048 = 234220
		
		$proer = $tlomr;
		
		if ($pi < 0) {
			$roeai = $pi * abs($proer);
		} else {
			$roeai = $pi * $proer;//234220 * .917 = 214813
		}
		
		$result->pi = ($pi + $roeai) / 2;
		
		return $result;
	}
	
	function projectedIgr($cigr, $ar, $ni, $vir, $so, $mp) {
		$aigr = $cigr + $ar - 1;
		
		$p0g = $ni * $vir / $so;
		
		//price growth potential, conteracts the omg or roeg
		$pgpr = ($mp - $p0g) / $mp;
		
		$aigr *= $pgpr;
		
		//deviation and volatility calculation here
		
		//if om is already high, growth potential is also less
		
		//$def->igr = ($def->igr < 1) ? 1 : $def->igr;
		
		if ($aigr > 20) {
			$aigr = 0;
		}
		
		return $aigr;
	}
	
	function estimatedValue($ni, $vir, $pigr, $dr) {
		return $ni * $vir * $pigr / (1 + $dr);
	}
	
	function siphon_stock_def_CNY($ticker, $car, $cc, $ir) {
		$dr = .2;//discount rate is the minimum profit rate to justify the investment
		//$mos = -.45;//margin of safety
		$vir = 10;//value to income ratio		
		$mp = 200;//abitrary number of the max possible price
		
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
		
		preg_match('/Annual Data[\s\S]+deb2equity[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->slyder = str_replace(',', '', $matches[2]);
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/'.urlencode('Net Income').'/'.$ticker.'/Net%2BIncome/');
						
		preg_match('/Annual Data[\s\S]+Net Income[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyni = str_replace(',', '', $matches[2]);
		
		preg_match('/data_value"\>(CN¥|$)(.+) Mil/', $ctt, $matches);
		
		$def->t12mni = str_replace(',', '', $matches[2]);
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/InterestExpense/'.$ticker.'/Interest%2BExpense/');
						
		preg_match('/Annual Data[\s\S]+Interest Expense[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyie = str_replace(',', '', $matches[2]);
		
		preg_match('/data_value"\>(CN¥|$)(.+) Mil/', $ctt, $matches);
		
		$def->t12mie = str_replace(',', '', $matches[2]);
		
		$def->lypii = $def->lyni - $def->lyie;
		
		$def->t12mpii = $def->t12mni - $def->t12mie;
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/ROC/'.$ticker.'/Return%2Bon%2BCapital/');
						
		preg_match('/fiscal year[\s\S]+where[\s\S]+A\: Dec\.[\s\S]+A\: Dec\.[\s\S]+Long\-Term Debt[\s\S]+\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \+ \<\/td\>\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \+ \<\/td\>\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \- \<\/td\>[\s\S]+for the \<strong\>quarter\<\/strong\> that ended/', $ctt, $matches);
		
		$def->lyltd = str_replace(',', '', $matches[1]);
		$def->lystd = str_replace(',', '', $matches[3]);
		
		$def->lyd = $def->lyltd + $def->lystd;
		
		$def->lye = str_replace(',', '', $matches[5]);
		
		$def->lycap = $def->lye + $def->lyd;
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/'.urlencode('Total Equity').'/'.$ticker.'/Total%2BEquity/');
						
		preg_match('/Annual Data[\s\S]+Total Equity[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->slye = str_replace(',', '', $matches[2]);
		
		$def->slycap = (1 + $def->slyder) * $def->slye;
		
		$def->lypcr = $def->lypii / $def->slycap;
		
		$def->t12mpcr = $def->t12mpii / $def->lycap;
		
		preg_match('/data_value"\>(.+)\% \(As of/', $ctt, $matches);
		
		$lroc = str_replace(',', '', $matches[1]);
		
		/*$alroc = $lroc * (1 / (1 + ($lroc - (100 / $vrr)) / 100));
		
		$crr = 100 / $alroc;
		
		$vcr = $vrr / $crr;
		
		$ver = $vcr / (1 + $def->der);*/
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/'.urlencode('Operating Income').'/'.$ticker.'/Operating%2BIncome/');
		
		preg_match('/Annual Data[\s\S]+Operating Income[\s\S]+\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyoi = str_replace(',', '', $matches[2]);
		
		preg_match('/data_value"\>(CN¥|$)(.+) Mil/', $ctt, $matches);
		
		$def->t12moi = str_replace(',', '', $matches[2]);
		
		//measures the impact of operating income on net income
		$oinir = $def->lyoi / $def->lyni;//1
		$coinir = $def->t12moi / $def->t12mni;
		
		$oinir = ($oinir > 1) ? 1 : $oinir;
		$oinir = ($oinir < -1) ? -1 : $oinir;
		$coinir = ($coinir > 1) ? 1 : $coinir;
		$coinir = ($coinir < -1) ? -1 : $coinir;
		
		$ctt = curl_get_contents('http://www.gurufocus.com/term/operatingmargin/'.$ticker.'/Operating%2BMargin/');
		
		preg_match('/Annual Data[\s\S]+Operating Margin[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyom = str_replace(',', '', $matches[8]);
		$def->slyom = str_replace(',', '', $matches[5]);
		$def->tlyom = str_replace(',', '', $matches[2]);
		
		//in case om was 0
		$def->slyom = ($def->slyom == 0) ? 1 : $def->slyom;
		$def->tlyom = ($def->tlyom == 0) ? 1 : $def->tlyom;
		
		$lytlomr = $def->lyom / $def->slyom;
		
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
			$def->tlomr = $lower_aom / $def->lyom;
		}
		
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

		$ctt = curl_get_contents('http://www.gurufocus.com/term/ROE/'.$ticker.'/Return%2Bon%2BEquity/');
						
		preg_match('/Annual Data[\s\S]+ROE[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyroe = str_replace(',', '', $matches[8]);
		$def->slyroe = str_replace(',', '', $matches[5]);
		$def->tlyroe = str_replace(',', '', $matches[2]);
		
		$def->aroe = ($def->lyroe + $def->slyroe + $def->tlyroe) / 3;
		
		//in case roe was 0
		$def->slyroe = ($def->slyroe == 0) ? 1 : $def->slyroe;
		$def->tlyroe = ($def->tlyroe == 0) ? 1 : $def->tlyroe;
		
		$lytlroer = $def->lyroe / $def->slyroe;
		
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
		
		$pcv = projectedIncome($oinir, $def->lypcr, $lytlomr, $def->lycap, $def->lyd, $def->wacodr, $lytlroer);
	
		$def->pci = $pcv->pi;

		if (($def->pci <= 0) || ($def->t12mni < 0)) {
			$def->pa = 0;
		} else {
			$def->pa = $def->t12mni / $def->pci;
		}
		
		$pfv = projectedIncome($oinir, $def->t12mpcr, $def->tlomr, $def->cap, $def->debt, $def->wacodr, $def->tlroer);
	
		$def->pfi = $pfv->pi;

		$def->apfi = $def->pfi * $def->pa * $oinir;
	
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
		
		$coinir = $def->t12moi / $def->t12mni;
		
		$coinir = ($coinir > 1) ? 1 : $coinir;
		$coinir = ($oinir < -1) ? -1 : $coinir;
		
		if ($def->lyni == 0) {
			$def->cigr = 1;
		} else {
			$def->cigr = $def->t12mni * $coinir / $def->lyni;
		}
		
		//value is current income worth + expectation of future income growth (positive or negative)
		//lyv is lyni + the current igr (assuming one was to predict the igr accurately last year)
		$lyv = $def->lyni * $vir * $def->cigr / (1 + $dr);
		
		$def->prlyv = $lyv / $def->so;
		
		//thus, cv is t12mni (current ni) + the expected igr of the future (although we do not know what the igr will be in the future)
		//thus, we use the current igr, but adjust it with a few factors
		$lower_ar = ($def->tlomr < $def->tlroer) ? $def->tlomr : $def->tlroer;
			
		$ar = $lower_ar;
			
		//$ar = ($lower_ar < 1) ? $lower_ar : 1;
		
		$def->cpigr = projectedIgr($def->cigr, $ar, $def->t12mni, $vir, $def->so, $mp);
		
		$cv = estimatedValue($def->t12mni, $vir, $def->cpigr, $dr);
		
		$def->prcv = $cv / $def->so;

		$def->prcv0g = $def->t12mni * $vir * $coinir / (1 + $dr) / $def->so;
		
		$pso = $def->so + $def->anios;
		
		if ($def->cpigr == 0) {
			$def->fpigr = 0;
		} else {
			$def->fpigr = projectedIgr($def->cpigr, $ar, $def->apfi, $vir, $pso, $mp);
		}
		
		$fv = estimatedValue($def->apfi, $vir, $def->fpigr, $dr);
		
		$def->fp = $fv / $pso;
	
		$def->fptm = $def->fp / (1 + $ir);
		
		$def->dwmoe = 1 - 20 / $def->cigr / $def->fpigr;
		
		$def->dwmoe = ($def->dwmoe < 0) ? 0 : $def->dwmoe;
		
		$afpigr = $def->fpigr * (1 - $def->dwmoe);
		
		//fptm adjusted for margin of error
		//upward moe is same as dr, because we want to tolerate as little moe as possible
		//dr is the non greedy margin for selling (when above projection), 
		//and the selling point is where the moe ends and we stop tolerating price deviations
		//downward moe is dynamically calculate depending of different type of stock
		//more precisely depending on the standard deviation of the stock
		//but in this case, we are just using growth rate
		$def->afptm = estimatedValue($def->apfi, $vir, $afpigr, $dr) / $pso / (1 + $ir);
		
		//guru focus's price update is too slow, we use reutors
		//parse ticker into reuters format
		preg_match('/([A-Z]{4})\:(\d{6})/', $ticker, $tkr_matches);
		
		switch ($tkr_matches[1]) {
			case 'SHSE':
				$r_se = 'SS';
				
				break;
			case 'SZSE':
				$r_se = 'SZ';
				
				break;
			default:
				echo 'invalid SE, rueters conversion failed.';
		}
		
		$tkr = $tkr_matches[2];
		
		$ctt = curl_get_contents('http://www.reuters.com/finance/stocks/overview?symbol='.$tkr.'.'.$r_se);
		
		preg_match('/'.$tkr.'\.'.$r_se.' on .+ Stock Exchange[\s\S]+\<span style\="font-size:[^"]+"\>[\D]+([\d\.]+)\<\/span\>\<span\>CNY\<\/span\>/', $ctt, $matches);

		//$def->cp = $matches[0];
		$def->cp = str_replace(',', '', $matches[1]);
		
		if (($def->cp !== 0) && !$def->cp) {
			echo 'get current price failed';
		}
		
		$def->iv = $def->prcv0g;
		
		$lower_p = ($def->iv < $def->afptm) ? $def->iv : $def->afptm;
		
		//ivcpr ratio is a non greedy ratio to buy in to get the dr
		//unless iv is 0
		if ($lower_p <= 0) {
			$def->ivcpr = -1;
		} else {
			$def->ivcpr = ($lower_p - $def->cp) / $def->cp;
		}
		
		//cpfptmr ratio on the other hand is a non greedy ratio to sell at a lower price
		//we are making less profit thus non greedy
		//it reflects how much profit is made from fptm to cp compared to fptm
		if ($def->fptm == 0) {
			$def->cpfptmr = 1;
		} else {
			$def->cpfptmr = ($def->cp - $def->fptm) / abs($def->fptm);
		}
		
		$def->pow = (22.5 - $def->gtap / 2) / 22.5;
		
		$def->advice = 'hold';
		
		if ($def->afptmcpr > $dr) {
			$def->advice = 'buy';
		}
		
		if ($def->cpfptmr >= ($dr - .02)) {
			$def->advice = 'be ready to sell';
		}
		
		if ($def->cpfptmr >= $dr || $cc <= 0) {
			$def->advice = 'sell';
		}
		
		global $ch;
		
		curl_close($ch);//may be bugged
		unset($GLOBALS['ch']);//ch defined in siphon_data.php global space
		
		return $def;
	}
?>