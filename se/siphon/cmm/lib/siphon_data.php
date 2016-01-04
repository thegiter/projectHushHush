<?php
	defined('root') or die;
	
	if (!function_exists('curl_init')){ 
		die('CURL is not installed!');
	}
	
	//often file_get_contents is disabled, using this is as a workaround
	function curl_get_contents($ch, $url) {
		curl_setopt($ch, CURLOPT_URL, $url);
		
		return curl_exec($ch);
	}
	
	define('MAXRETRY', '5');
	define('MAXTIME', '30');
	
	function curl_multiRequest($data, $options = []) {
		// array of curl handles
		$chs_arr = [];
		// data to be returned
		$result = [];
	 
		// multi handle
		$cmh = curl_multi_init();
		 
		// loop through $data and create curl handles
		// then add them to the multi-handle
/*		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER,         0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
*/		
		foreach ($data as $id => $d) {
			$chs_arr[$id] = curl_init();
			
			$url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
			curl_setopt($chs_arr[$id], CURLOPT_URL,            $url);
			curl_setopt($chs_arr[$id], CURLOPT_HEADER,         0);
			curl_setopt($chs_arr[$id], CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($chs_arr[$id], CURLOPT_CONNECTTIMEOUT, 30); 
			curl_setopt($chs_arr[$id], CURLOPT_TIMEOUT, 30); //timeout in seconds
			
			curl_setopt($chs_arr[$id], CURLOPT_URL,            $url);
			
			// post?
			if (is_array($d) && !empty($d['post'])) {
				curl_setopt($chs_arr[$id], CURLOPT_POST,       1);
				curl_setopt($chs_arr[$id], CURLOPT_POSTFIELDS, $d['post']);
			}
		 
			// extra options?
			if (!empty($options)) {
				curl_setopt_array($chs_arr[$id], $options);
			}
			
			//$result[$id] = curl_exec($ch);
			curl_multi_add_handle($cmh, $chs_arr[$id]);
		}
		 
		// execute the handles
		$running = null;
		$starttime = microtime(true);

		do {
			if ($running > 0) {
				curl_multi_select($cmh);// Wait max 5 seconds 'till at least one of the curls shows activity
			}
			
			$c = 0;
			
			//do multi exec, if something went wrong, do again, until max reached
			do {
				$mrc = curl_multi_exec($cmh, $running);
				
				$c++;
			} while (($mrc == CURLM_CALL_MULTI_PERFORM) && ($c < MAXRETRY));
		} while (($running > 0) && ($mrc == CURLM_OK) && ((microtime(true) - $starttime) < MAXTIME));//if maxretry reached, mrc is still MULTI_PERFORM, so the entire loop is skipped
		//if nothing went wrong, but running is always true, (one / more webpage could not be fetched)
		//then we also skipped the entire loop on timeout
		
		// get content and remove handles
		foreach ($chs_arr as $id => $ch) {
			$result[$id] = curl_multi_getcontent($ch);
			curl_multi_remove_handle($cmh, $ch);
			curl_close($ch);//may be bugged
		}
		 
		// all done
		curl_multi_close($cmh);

//		curl_close($ch);//may be bugged
//		unset($ch);
		
		return $result;
	}
	
/*	function rolling_curl($urls, $callback, $custom_options = null) {
		// make sure the rolling window isn't greater than the # of urls
		$rolling_window = 5;
		$rolling_window = (sizeof($urls) < $rolling_window) ? sizeof($urls) : $rolling_window;

		$master = curl_multi_init();
		$curl_arr = array();

		// add additional curl options here
		$std_options = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 5
		];
		$options = ($custom_options) ? ($std_options + $custom_options) : $std_options;

		// start the first batch of requests
		for ($i = 0; $i < $rolling_window; $i++) {
			$ch = curl_init();
			$options[CURLOPT_URL] = $urls[$i];
			curl_setopt_array($ch, $options);
			curl_multi_add_handle($master, $ch);
		}

		do {
			while (($execrun = curl_multi_exec($master, $running)) == CURLM_CALL_MULTI_PERFORM);
			
			if ($execrun != CURLM_OK) {
				break;
			}
			
			// a request was just completed -- find out which one
			while ($done = curl_multi_info_read($master)) {
				$info = curl_getinfo($done['handle']);
				
				if ($info['http_code'] == 200)  {
					$output = curl_multi_getcontent($done['handle']);

					// request successful.  process output using the callback function.
					$callback($output);

					// start a new request (it's important to do this before removing the old one)
					$ch = curl_init();
					$options[CURLOPT_URL] = $urls[$i++];  // increment i
					curl_setopt_array($ch,$options);
					curl_multi_add_handle($master, $ch);

					// remove the curl handle that just completed
					curl_multi_remove_handle($master, $done['handle']);
				} else {
					// request failed.  add error handling.
				}
			}
		} while ($running);
	   
		curl_multi_close($master);
		return true;
	}
*/	
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
		if ($ni < 0) {
			$pigr = abs($pigr);
		}
		
		return $ni * $vir * $pigr / (1 + $dr);
	}
	
	function siphon_stock_def_CNY($ticker, $car, $cc) {
		$def = new stdClass;
		
		$def->car = $car;
		$def->cc = $cc;
		
		$dr = .2;//discount rate is the minimum profit rate to justify the investment
		$bdr = .03;//the betting discount rate for smaller profit yet larger risk, but potentially higher profit as well
		$def->dr = $dr;
		$mos = .7;//margin of safety
		$vir = 10;//value to income ratio		
		
		//guru focus's price update is too slow, we use reutors
		//parse ticker into reuters format
		preg_match('/([A-Z]{3,4})\:([a-zA-Z0-9]{3,6})/', $ticker, $tkr_matches);
		
		define('CNYIR', '0.012');
		define('ZARIR', '0.061');
		define('USDIR', '0.061');
		define('CNYMP', '200');
		define('ZARMP', '1000');
		define('USDMP', '1000');
		
		switch ($tkr_matches[1]) {
			case 'SHSE':
				$r_se = '.SS';
				$ir = CNYIR;
				$mp = CNYMP;//abitrary number of the max possible price
				
				break;
			case 'SZSE':
				$r_se = '.SZ';
				$ir = CNYIR;
				$mp = CNYMP;
				
				break;
			case 'JSE':
				$r_se = 'J.J';
				$ir = ZARIR;
				$mp = ZARMP;
				
				break;
			default:
				$r_se = '';
				$ir = USDIR;
				$mp = USDMP;
		}
		
		$tkr = $tkr_matches[2];
		
		$rqss = [
			'mc' => 'http://www.gurufocus.com/term/mktcap/'.$ticker.'/Market%2BCap/',
			'bps' => 'http://www.gurufocus.com/term/'.urlencode('Book Value Per Share').'/'.$ticker.'/Book%2BValue%2Bper%2BShare/',
			'so' => 'http://www.gurufocus.com/term/BS_share/'.$ticker.'/Shares%2BOutstanding%2B%2528EOP%2529/',
			'der' => 'http://www.gurufocus.com/term/deb2equity/'.$ticker.'/Debt%2Bto%2BEquity/',
			'ni' => 'http://www.gurufocus.com/term/'.urlencode('Net Income').'/'.$ticker.'/Net%2BIncome/',
			'ie' => 'http://www.gurufocus.com/term/InterestExpense/'.$ticker.'/Interest%2BExpense/',
			'roc' => 'http://www.gurufocus.com/term/ROC/'.$ticker.'/Return%2Bon%2BCapital/',
			'te' => 'http://www.gurufocus.com/term/'.urlencode('Total Equity').'/'.$ticker.'/Total%2BEquity/',
			'oi' => 'http://www.gurufocus.com/term/'.urlencode('Operating Income').'/'.$ticker.'/Operating%2BIncome/',
			'om' => 'http://www.gurufocus.com/term/operatingmargin/'.$ticker.'/Operating%2BMargin/',
			'wacc' => 'http://www.gurufocus.com/term/wacc/'.$ticker.'/Weighted%2BAverage%2BCost%2BOf%2BCapital%2B%2528WACC%2529/',
			'roe' => 'http://www.gurufocus.com/term/ROE/'.$ticker.'/Return%2Bon%2BEquity/',
			'pe' => 'http://www.gurufocus.com/term/pe/'.$ticker.'/P%252FE%2BRatio/',
			'pb' => 'http://www.gurufocus.com/term/pb/'.$ticker.'/P%252FB%2BRatio/',
			'nios' => 'http://www.gurufocus.com/term/'.urlencode('Net Issuance of Stock').'/'.$ticker.'/Net%2BIssuance%2Bof%2BStock/',
			'cp' => 'http://www.reuters.com/finance/stocks/overview?symbol='.$tkr.$r_se
		];
		
		$result = curl_multiRequest($rqss);
		
		$ctt = $result['mc'];

		preg_match('/data_value"\>(CN¥|$|.*ZAR\<\/span\> )(.+) Mil/', $ctt, $matches);

		if (!$matches) {
			return false;
		}
		
		$def->mc = str_replace(',', '', $matches[2]);

		$ctt =  $result['bps'];

		preg_match('/data_value"\>(CN¥|$|.*ZAR\<\/span\> )(.+) \(As of/', $ctt, $matches);
		
		$def->bps = str_replace(',', '', $matches[2]);
		
		$ctt = $result['so'];

		preg_match('/data_value"\>(.+) Mil/', $ctt, $matches);
		
		$def->so = str_replace(',', '', $matches[1]);

		$def->ce = $def->bps * $def->so;
		
		$ctt = $result['der'];
						
		preg_match('/data_value"\>(.+) \(As of/', $ctt, $matches);
		
		$def->der = str_replace(',', '', $matches[1]);

		$def->debt = $def->ce * $def->der;
		$def->cap = $def->debt + $def->ce;
		
		preg_match('/Annual Data[\s\S]+deb2equity[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->slyder = str_replace(',', '', $matches[2]);
		
		$ctt = $result['ni'];
						
		preg_match('/Annual Data[\s\S]+Net Income[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyni = str_replace(',', '', $matches[2]);
		
		preg_match('/data_value"\>(CN¥|$|.*ZAR\<\/span\> )(.+) Mil/', $ctt, $matches);
		
		$def->t12mni = str_replace(',', '', $matches[2]);
		
		$ctt = $result['ie'];
						
		preg_match('/Annual Data[\s\S]+Interest Expense[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyie = str_replace(',', '', $matches[2]);
		
		preg_match('/data_value"\>(CN¥|$|.*ZAR\<\/span\> )(.+) Mil/', $ctt, $matches);
		
		$def->t12mie = str_replace(',', '', $matches[2]);
		
		$def->lypii = $def->lyni - $def->lyie;
		
		$def->t12mpii = $def->t12mni - $def->t12mie;
		
		$ctt = $result['roc'];
						
		preg_match('/fiscal year[\s\S]+where[\s\S]+A\: (Dec|Mar|Jun|Sep|Feb)\.[\s\S]+A\: (Dec|Mar|Jun|Sep|Feb)\.[\s\S]+Long\-Term Debt[\s\S]+\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \+ \<\/td\>\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \+ \<\/td\>\<td\>(\-?\d+(\.\d+)?)\<\/td\>\<td\> \- \<\/td\>[\s\S]+for the \<strong\>quarter\<\/strong\> that ended/', $ctt, $matches);
		
		$def->lyltd = str_replace(',', '', $matches[3]);
		$def->lystd = str_replace(',', '', $matches[5]);
		
		$def->lyd = $def->lyltd + $def->lystd;
		
		$def->lye = str_replace(',', '', $matches[7]);
		
		$def->lycap = $def->lye + $def->lyd;
		
		preg_match('/data_value"\>(.+)\% \(As of/', $ctt, $matches);
		
		$lroc = str_replace(',', '', $matches[1]);
		
		/*$alroc = $lroc * (1 / (1 + ($lroc - (100 / $vrr)) / 100));
		
		$crr = 100 / $alroc;
		
		$vcr = $vrr / $crr;
		
		$ver = $vcr / (1 + $def->der);*/
		
		$ctt = $result['te'];
						
		preg_match('/Annual Data[\s\S]+Total Equity[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->slye = str_replace(',', '', $matches[2]);
		
		$def->slycap = (1 + $def->slyder) * $def->slye;
		
		$def->lypcr = $def->lypii / $def->slycap;
		
		$def->t12mpcr = $def->t12mpii / $def->lycap;
		
		$ctt = $result['oi'];
		
		preg_match('/Annual Data[\s\S]+Operating Income[\s\S]+\<strong\>(\<font[^>]*\>)?([^<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->lyoi = str_replace(',', '', $matches[2]);
		
		preg_match('/data_value"\>(CN¥|$|.*ZAR\<\/span\> )(.+) Mil/', $ctt, $matches);
		
		$def->t12moi = str_replace(',', '', $matches[2]);
		
		//measures the impact of operating income on net income
		if ($def->lyni == 0) {
			$oinir = 1;
			
			if ($def->lyoi < 0) {
				$oinir = -1;
			}
		} else {
			$oinir = $def->lyoi / $def->lyni;//1
		}
		
		$oinir = ($oinir > 1) ? 1 : $oinir;
		$oinir = ($oinir < -1) ? -1 : $oinir;
		
		$ctt = $result['om'];
		
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
		
		$ctt = $result['wacc'];
						
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

		$ctt = $result['roe'];
						
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
	
		$ctt = $result['pe'];
						
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
		
		$ctt = $result['pb'];
						
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
		
		$ctt = $result['nios'];
						
		preg_match('/Annual Data[\s\S]+Net Issuance of Stock[\s\S]+\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\<td\>\<strong\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/strong\>\<\/td\>\s*\<\/tr\>[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);
		
		$def->anios = (str_replace(',', '', $matches[8]) + str_replace(',', '', $matches[5]) + str_replace(',', '', $matches[2])) / 3;
		
		//in case so eop is 0, we assume an abstract value of 1 for calculation purposes
		//if there is anios, this would result in future price significantly lower than cp
		//this is fine, because we are gonna ignore this stock due to lack of data
		if ($def->so === 0) {
			$def->so = 1;
		}
		
		if ($def->t12moi > $def->t12mni) {
			$coinir = 1;
		} else if ($def->t12mni < 0) {
			$coinir = 0;
		} else {
			$coinir = $def->t12moi / $def->t12mni;
		}
		
		if ($def->lyni == 0) {
			$def->cigr = 1;
		} else {
			$def->cigr = $def->t12mni * $coinir / $def->lyni;
		}
		
		//value is current income worth + expectation of future income growth (positive or negative)
		//lyv is lyni + the current igr (assuming one was to predict the igr accurately last year)
		$cigr = ($def->lyni < 0) ? abs($def->cigr) : $def->cigr;
		
		$lyv = $def->lyni * $vir * $cigr / (1 + $dr);
		
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
		
		$def->niosi = ($def->so - $pso) / $def->so;
		
		if ($def->cpigr == 0) {
			$def->fpigr = 0;
		} else {
			$def->fpigr = projectedIgr($def->cpigr, $ar, $def->t12mni, $vir, $pso, $mp);
		}
		
		$fv = estimatedValue($def->t12mni, $vir, $def->fpigr, $dr);
		
		$def->fp = $fv / $pso;
	
		$def->fptm = $def->fp / (1 + $ir);
		
		if ($def->fpigr == 0) {
			$def->dwmoe = 0;
		} else if ($def->cigr == 0) {
			$def->dwmoe = 1 - 20 / $def->fpigr;
		} else {
			$def->dwmoe = 1 - 20 / $def->cigr / $def->fpigr;
		}
		
		$def->dwmoe = ($def->dwmoe < 0) ? 0 : $def->dwmoe;
		
		$adj = 1 - $def->dwmoe;
		
		if ($def->fpigr < 0) {
			$adj = abs($adj);
		}
		
		$afpigr = $def->fpigr * $adj;
		
		//fptm adjusted for margin of error
		//upward moe is same as dr, because we want to tolerate as little moe as possible
		//dr is the non greedy margin for selling (when above projection), 
		//and the selling point is where the moe ends and we stop tolerating price deviations
		//downward moe is dynamically calculate depending of different type of stock
		//more precisely depending on the standard deviation of the stock
		//but in this case, we are just using growth rate
		$def->afptm = estimatedValue($def->t12mni, $vir, $afpigr, $dr) / $pso / (1 + $ir);
		
		if ($afpigr > 1.2) {
			$def->lffptm = estimatedValue($def->t12mni, $vir, 1.2, $dr) / $pso / (1 + $ir);
		} else {
			$def->lffptm = $def->afptm;
		}
		
		$def->ep = ($def->fptm + $def->lffptm) / 2;
		
		$epea = $def->ep - $def->lffptm;
		
		$mosaa = (1 - $mos) * $epea;
		
		$def->bp = $def->lffptm + $mosaa;//the betting price, the price we are betting on
		
		if ($epea <= 0) {
			$def->abdr = $bdr;
		} else {
			$def->abdr = $bdr / $mos;
		}
		
		$ctt = $result['cp'];
		
		preg_match('/'.$tkr.$r_se.' on .+ Stock Exchange[\s\S]+\<span style\="font-size:[^"]+"\>[\D]+([\d\.\,]+)\<\/span\>\<span\>(CNY|ZAc|ZAX)\<\/span\>/', $ctt, $matches);

		$def->cp = str_replace(',', '', $matches[1]);
		
		if (($def->cp !== 0) && !$def->cp) {
			echo 'get current price failed';
		} else if ($r_se == 'J.J') {
			$def->cp = $def->cp / 100;
		}
		
		if ($def->bp <= 0) {
			$def->bpcpr = -1;
		} else {
			$def->bpcpr = ($def->bp - $def->cp) / $def->cp;
		}
		
		$def->iv = $def->prcv0g;
		
		$lower_p = $def->lffptm;
		
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
		
		if (($def->bpcpr > $def->abdr) && !($def->niosi >= .1)) {
			$def->advice = 'betting buy';
		}
		
		if (($def->ivcpr > $dr) && !($def->niosi >= .1)) {
			$def->advice = 'buy';
		}
		
		if ($def->cpfptmr >= ($dr - .02)) {
			$def->advice = 'be ready to sell';
		}
		
		if ($def->cpfptmr >= $dr || $cc <= 0) {
			$def->advice = 'sell';
		}
		
		return $def;
	}
?>