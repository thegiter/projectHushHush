<?php
	defined('root') or die;

	if (!function_exists('curl_init')){
		die('CURL is not installed!');
	}

	ini_set('max_execution_time', 180);

	class seCurl {
		const MAXRETRY = 5;
		const MAXTIME = 170;

		//often file_get_contents is disabled, using this is as a workaround
		static function getCtts($url) {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL,            $url);
			curl_setopt($ch, CURLOPT_HEADER,         0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($ch, CURLOPT_TIMEOUT,		 30); //timeout in seconds

			$data = curl_exec($ch);

			curl_close($ch);

			return $data;
		}

		static function multiRequest($data, $options = []) {
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
				curl_setopt($chs_arr[$id], CURLOPT_CONNECTTIMEOUT, self::MAXTIME);
				curl_setopt($chs_arr[$id], CURLOPT_TIMEOUT, self::MAXTIME); //timeout in seconds

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
				} while (($mrc == CURLM_CALL_MULTI_PERFORM) && ($c < self::MAXRETRY));
			} while (($running > 0) && ($mrc == CURLM_OK) && ((microtime(true) - $starttime) < self::MAXTIME));//if maxretry reached, mrc is still MULTI_PERFORM, so the entire loop is skipped
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
	}

	class seCalc {
		public static function getOinir($oi, $ni) {
			if ($oi > $ni) {
				$oinir = 1;
			} else if ($ni <= 0) {
				$oinir = 0;
			} else {
				$oinir = $oi / $ni;
			}

			return $oinir;
		}

		//projected income
		public static function pjtIcm($oinir, $pcr, $tlomr, $cap, $debt, $wacodr) {//everything is current data
			$result = new stdClass;

			//projected pc ratio
			$ppcr = $pcr * $oinir * $tlomr;//0.35 * .96 = .336

			//projected pre interest income
			$ppii = $cap * $ppcr;//1581156 * .336 = 531268

			//projected interest
			$pjtInterest = $debt * $wacodr;//550090 * .54 = 297048
			$result->pjtInterest = $pjtInterest;

			//projected income
			$pi = $ppii - $pjtInterest;//531268 - 297048 = 234220

			//om adjusted income
			if ($pi < 0) {
				$omai = $pi * abs($tlomr);
			} else {
				$omai = $pi * $tlomr;//234220 * .917 = 214813
			}

			$result->pjtIcm = ($pi + $omai) / 2;

			return $result;
		}
	}

	class seAnalyze {
		//discount rate is the minimum profit rate to justify the investment, which is a rather large percentage
		//not because we are greedy, but because we want a large safty net
		const DR = .4;
		const BDR = .03;//the betting discount rate for smaller profit yet larger risk, but potentially higher profit as well
		const B_ALLO = .25;//the target allocation for betting
		const MOS = .8;//margin of safety
		const VIR = 10;//value to income ratio
		const MIN_GROWTH_HARD = .9;
		const MIN_GROWTH = .5;//minimum growth ratio required to buy for om roe roc fpigr
		const MIN_GROWTH_REFINE = .7;
		const ROTA_RANK_PASS = 40;//percent
		const ROTA_RANK_PASS_PPLR = 90;//percent
		const ROTE_PASS = 20;//percent
		const MIN_T12MNI = 100;
		const T12MNI_PPLR = 10000;
		const PPLR_PCT_STEP_DOWN = .8;
		const PPLR_PCT_STEP_UP = 1.2;
		const MAX_P = 1.4;//max premium using standard premium for top stocks
		const MAX_D = .1;//max discount
		const TTL_GLB_RANK = 4000;

		const NI_SZE_STD = 20000;//mil(15oz * crt 6yr avg gold price)
		const RETURN_STD = 20;//pct
		const RETURN_GRW_STD = 1;
		const ROTA_RANK_STD = 60;

		const CNYIR = 0.02;//10%
		const ZARIR = 0.07;
		const USDIR = 0.01;

		const CNYMP = 200;
		const ZARMP = 1000;
		const USDMP = 3600;

		private static $usFirstQuarter = ['Jan', 'Feb', 'Mar', 'Apr'];

		private static $firstQuarter;

		private static $fullTkr = '';
		private static $tkr = '';
		private static $se = '';
		private static $guruFullTkr = '';

		private static $car = 1;
		private static $cc = 1;

		private static $mp;
		private static $ir;
		private static $increment;

		private static $rSe = '';
		private static $cpUrl = '';
		private static $cpHtml = '';

		private static $def;

		//normalized trailing vs last
		//last must always be positive (>0)
		private static function normTl($t, $l, $avg) {
			$tl = $t / $l;//2.08 / 16.8 = .123

			//normalize trailing vs last
			//prob = trailing vs avg
			$diff = abs($t - $avg);//10

			$avg_size = abs($avg);//12

			//the higher the ratio, the less probability it is true
			//1 - 10 / (10 + 2)
			//1 - 10 / 12
			//1 - .83
			//.16
			$prob = 1 - $diff / ($diff + $avg_size / 6);

			//1 + (.123 - 1) * .16
			//1 + -.877 * .16
			//1 + -.14
			//.86
			return 1 + ($tl - 1) * $prob;
		}

		private static function pjtIgr($cigr, $ar, $ni, $so) {
			//adjusted income growth rate
			//we say that igr is not sustainable, if ar is declining
			//so we confirm the gr with ar
			$aigr = ($cigr + $ar) / 2;//.85

			if ($so <= 0) {
				$p0g = 0;
			} else {
				$p0g = $ni * self::VIR / $so;
			}

			//price growth potential, conteracts the omg or roeg
			$pgpr = (self::$mp - $p0g) / self::$mp;

			$aigr *= $pgpr;

			//deviation and volatility calculation here

			//if om is already high, growth potential is also less

			//$def->igr = ($def->igr < 1) ? 1 : $def->igr;

			if ($aigr > 20) {
				$aigr = 0;
			}

			return $aigr;
		}

		private static function estimatedValueIcm($ni, $pigr, $dda, $capE) {
			if ($ni < 0) {
				$pigr = abs($pigr);
			}

			$ev = ($ni + $dda + $capE) * $pigr * self::VIR / (1 + self::DR);

			//a company's value can be negative if it loses money each year
			//however, for stock valuation it is fine to assume the value is 0, because stock price can not be negative
			return max(0, $ev);
		}

		//current equity, current ni, projected igr
		//current ni and pjt igr gives pjc income
		//net income is already after debt payment, so the estimatedValue is after paying debt
		private static function estimatedValueE($ce, $ni, $pigr) {
			$result = new stdClass;

			if ($ni < 0) {
				$pigr = abs($pigr);
			}

			$result->fe = $ce + $ni * $pigr;

			$result->ev = max(0, $result->fe / (1 + self::DR));

			//a company's value can be negative, if it has too much debt and is losing money
			//however, for stock valuation it is fine to assume the value is 0, because stock price can not be negative
			return $result;
		}

		//profitability popularity adjustment
		private static function calcPpAdj($ni) {
			if ($ni <= 0) {
				$profitadj = 0;
			} else {
				$profitadj = pow($ni / self::NI_SZE_STD * .65 + self::$def->tlomr / self::RETURN_GRW_STD * .1 + self::$def->tlroer / self::RETURN_GRW_STD * .1 + self::$def->tlrocr / self::RETURN_GRW_STD * .1 + self::$def->rotaRank / self::ROTA_RANK_STD * .05, 2);
			}
			//end profitability adj

			$ppadj = $profitadj * .9 + self::$def->popadj * .1;

			if ($ppadj > 1) {
				$ppadj_ovrAmt = ($ppadj - 1);

				//$ppadj = (1 - ($ppadj_ovrAmt / ($ppadj_ovrAmt + 2))) * $ppadj_ovrAmt + 1;
				//$ppadj = pow($ppadj_ovrAmt, $ppadj_ovrAmt) * ($ppadj_ovrAmt / 7) + 1;
				$ppadj = $ppadj_ovrAmt / ($ppadj_ovrAmt + 1) * $ppadj_ovrAmt + 1;
			}

			return $ppadj;
		}

		private static function calcBetting($pf, $ep, $pc) {
			$result = new stdClass;

			$result->bp = $pf;
			$result->abdr = self::BDR;

			if (($ep > 0) && ($ep > $pf)) {//lower floor fptm
				$lastBp = $pf;

				for ($bp = $pf; $bp < $ep; $bp += self::$increment) {
					if ($bp != 0) {
						$mos = ($ep - $bp) / ($ep - $pf);

						$wa = self::BDR / $mos;

						$cost = $bp / (1 + $wa);

						$la = ($cost - $pf) / $cost;

						//probability of going up
						$p_up = 1 - ($cost - $pf) / ($ep - $pf) * .5;

						if ($p_up > 1) {
							$p_up = 1;
						} else if ($p_up < 0) {
							$p_up = 0;
						}

						//probability of reaching bp
						$p = (1 - ($bp - $cost) / ($pc - $cost)) * $p_up;

						$allo = ($p - (1 - $p) / ($wa / $la)) / 2;
						$abdr = $wa;

						if ($allo < self::B_ALLO) {
							$result->bp = $lastBp;
							$result->abdr = $abdr;

							break;
						}

						$lastBp = $bp;
					}
				}
			}

			return $result;
		}

		private static function getCp() {
			preg_match('/ on (Nasdaq|NASDAQ|.+ Stock Exchange)[\s\S]+\<span style\="font-size:[^"]+"\>\s+([\d\.\,]+)\<\/span\>\<span\>(CNY|HKD|ZAc|ZAX|USD)\<\/span\>[\s\S]+\<span\>52\-wk High\<\/span\>[\s\S]+class\="sectionQuoteDetailHigh"\>.+;([\d\.\,]+)\<\/span\>[\s\S]+\<span\>52\-wk Low\<\/span\>[\s\S]+class\="sectionQuoteDetailLow">.+;([\d\.\,]+)\<\/span\>/', self::$cpHtml, $matches);

			$cp = str_replace(',', '', $matches[2]);
			$high = str_replace(',', '', $matches[4]);
			$low = str_replace(',', '', $matches[5]);

			if (($cp !== 0) && !$cp) {
				return 'get current price failed: '.self::$cpHtml;
			} else if (($high !== 0) && !$high) {
				return 'get 52-wk high failed: '.self::$cpHtml;
			} else if (($low !== 0) && !$low) {
				return 'get 52-wk low failed: '.self::$cpHtml;
			} else if (self::$rSe == 'J.J') {
				$cp /= 100;
				$high /= 100;
				$low /= 100;
			}

			$result = new stdClass;

			$result->cp = $cp;
			$result->high = $high;
			$result->low = $low;

			return $result;
		}

		private static function getDef_siphon() {
			//siphon and set up def
			$rqss = [
				'mc' => 'https://www.gurufocus.com/term/mktcap/'.self::$guruFullTkr.'/Market-Cap-(M)/',
				'bps' => 'https://www.gurufocus.com/term/Book+Value+Per+Share/'.self::$guruFullTkr.'/Book-Value-per-Share/',
				'so' => 'https://www.gurufocus.com/term/BS_share/'.self::$guruFullTkr.'/Shares-Outstanding-(EOP)/',
				'der' => 'https://www.gurufocus.com/term/deb2equity/'.self::$guruFullTkr.'/Debt-to-Equity/',
				'ni' => 'https://www.gurufocus.com/term/Net+Income/'.self::$guruFullTkr.'/Net-Income/',
				'ie' => 'https://www.gurufocus.com/term/InterestExpense/'.self::$guruFullTkr.'/Interest-Expense/',
				'roc' => 'https://www.gurufocus.com/term/ROC/'.self::$guruFullTkr.'/ROC-Percentage/',
				'te' => 'https://www.gurufocus.com/term/TotalEquityGrossMinorityInterest/'.self::$guruFullTkr.'/Total-Equity/',
				'oi' => 'https://www.gurufocus.com/term/Operating+Income/'.self::$guruFullTkr.'/Operating-Income/',
				'om' => 'https://www.gurufocus.com/term/operatingmargin/'.self::$guruFullTkr.'/Operating-Margin-Percentage/',
				'wacc' => 'https://www.gurufocus.com/term/wacc/'.self::$guruFullTkr.'/WACC-Percentage/',
				'roe' => 'https://www.gurufocus.com/term/ROE/'.self::$guruFullTkr.'/ROE-Percentage/',
				'rota' => 'https://www.gurufocus.com/term/ROTA/'.self::$guruFullTkr.'/Return-on-Tangible-Asset/',
				'rote' => 'https://www.gurufocus.com/term/ROTE/'.self::$guruFullTkr.'/Return-on-Tangible-Equity/',
				'pe' => 'https://www.gurufocus.com/term/pe/'.self::$guruFullTkr.'/PE-Ratio/',
				'pb' => 'https://www.gurufocus.com/term/pb/'.self::$guruFullTkr.'/PB-Ratio/',
				'nios' => 'https://www.gurufocus.com/term/Net+Issuance+of+Stock/'.self::$guruFullTkr.'/Net-Issuance-of-Stock/',
				'dda' => 'https://www.gurufocus.com/term/CF_DDA/'.self::$guruFullTkr.'/Depreciation,-Depletion-and-Amortization/',
				'capE' => 'https://www.gurufocus.com/term/Cash+Flow_CPEX/'.self::$guruFullTkr.'/Capital-Expenditure/',
				'cCapE' => 'https://www.gurufocus.com/term/ChangeInWorkingCapital/'.self::$guruFullTkr.'/Change-In-Working-Capital/',
				'cp' => self::$cpUrl
			];

			$result = seCurl::multiRequest($rqss);

			$ctt = $result['mc'];

			preg_match('/Market Cap \(M\)\: (CN¥|\$|.*ZAR\<\/span\> |.*USD\<\/span\> )(.+) Mil *\(As of/', $ctt, $matches);

			if (!$matches) {
				return 'no mc: '.$ctt;
			}

			self::$def->mc = str_replace(',', '', $matches[2]);

			if (self::$def->mc == 0) {
				return 'no mc: mc is 0';
			}

			$ctt =  $result['bps'];

			preg_match('/Book Value per Share\: (CN¥|\$|.*ZAR\<\/span\> |.*USD\<\/span\> )(.+) \(As of/', $ctt, $matches);

			if (!$matches) {
				return 'no bps';
			}

			self::$def->bps = str_replace(',', '', $matches[2]);

			$ctt = $result['so'];

			preg_match('/Shares Outstanding \(EOP\)\: (.+) Mil/', $ctt, $matches);

			if (!$matches) {
				return 'no so';
			}

			self::$def->so = str_replace(',', '', $matches[1]);

			self::$def->ce = self::$def->bps * self::$def->so;

			$ctt = $result['der'];

			preg_match('/Debt-to-Equity\: (.+) \(As of/', $ctt, $matches);

			if (!$matches) {
				return 'no der';
			}

			self::$def->der = str_replace(',', '', $matches[1]);

			if (self::$def->der == 'N/A') {
				return 'no der';
			}

			self::$def->debt = self::$def->ce * self::$def->der;
			self::$def->cap = self::$def->debt + self::$def->ce;

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no slyder';
			}

			self::$def->slyder = str_replace(',', '', $matches[count($matches) - 2][2]);

			$ctt = $result['ni'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no ni';
			}

			self::$def->lyni = str_replace(',', '', end($matches)[2]);

			$matchCnt = count($matches);

			//get sum of last 4 years ni later we add them to avg ly ni to get get historical 5 year avg
			$l2yni = str_replace(',', '', $matches[$matchCnt - 2][2]) + str_replace(',', '', $matches[$matchCnt - 3][2]);
			$l3yni = str_replace(',', '', $matches[$matchCnt - 2][2]) + str_replace(',', '', $matches[$matchCnt - 3][2]) + str_replace(',', '', $matches[$matchCnt - 4][2]);
			$l4yni = str_replace(',', '', $matches[$matchCnt - 2][2]) + str_replace(',', '', $matches[$matchCnt - 3][2]) + str_replace(',', '', $matches[$matchCnt - 4][2]) + str_replace(',', '', $matches[$matchCnt - 5][2]);

			//gurufocus does not update net income to the current year,
			//we add up the quaterly data to get trailing net income
			preg_match('/Quarterly Data[\s\S]+Calculation/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if ($matches) {
				//add up 4 quarters
				$matchCnt = count($matches);

				self::$def->t12mni = str_replace(',', '', $matches[$matchCnt - 1][2]) + str_replace(',', '', $matches[$matchCnt - 2][2]) + str_replace(',', '', $matches[$matchCnt - 3][2]) + str_replace(',', '', $matches[$matchCnt - 4][2]);
			} else {
				//check for semi-annual data
				preg_match('/Semi-Annual Data[\s\S]+Calculation/', $ctt, $matches);

				$tmpMatch = $matches[0];

				preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

				if ($matches) {
					$matchCnt = count($matches);

					self::$def->t12mni = str_replace(',', '', $matches[$matchCnt - 1][2]) + str_replace(',', '', $matches[$matchCnt - 2][2]);
				} else {
					return 'no trailing ni';
				}
			}

			$avglyni = (self::$def->lyni + self::$def->t12mni) / 2;

			self::$def->l3yavgni = ($avglyni + $l2yni) / 3;
			self::$def->sl3yavgni = $l3yni / 3;
			self::$def->l5yavgni = ($avglyni + $l4yni) / 5;

			$ctt = $result['ie'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no ie';
			}

			self::$def->lyie = str_replace(',', '', end($matches)[2]);

			preg_match('/Interest Expense\: (CN¥|\$|.*ZAR\<\/span\> |.*USD\<\/span\> )(.+) Mil/', $ctt, $matches);

			if (!$matches) {
				return 'no t12mie';
			}

			self::$def->t12mie = str_replace(',', '', $matches[2]);

			self::$def->lypii = self::$def->lyni - self::$def->lyie;

			self::$def->t12mpii = self::$def->t12mni - self::$def->t12mie;

			$ctt = $result['roc'];

			preg_match('/fiscal year[\s\S]+for the \<strong\>quarter\<\/strong\> that ended/', $ctt, $matches);

			preg_match('/Long\-Term Debt[\s\S]+\<td\>([\-.\d]+)\<\/td\>\<td\> \+ \<\/td\>\<td\>([\-.\d]+)\<\/td\>\<td\> \+ \<\/td\>\<td\>[\-.\d]+\<\/td\>\<td\> \+ \<\/td\>\<td\>([\-.\d]+)\<\/td\>\<td\> \- \<\/td\>/', $matches[0], $matches);

			if (!$matches) {
				return 'no roc ltd std';
			}

			self::$def->lyltd = str_replace(',', '', $matches[1]);
			self::$def->lystd = str_replace(',', '', $matches[2]);

			self::$def->lyd = self::$def->lyltd + self::$def->lystd;

			self::$def->lye = str_replace(',', '', $matches[3]);

			self::$def->lycap = self::$def->lye + self::$def->lyd;

			preg_match('/ROC \%\: (.+)\% +\(As of/', $ctt, $matches);

			if (!$matches) {
				return 'no roc';
			}

			$lroc = str_replace(',', '', $matches[1]);

			/*$alroc = $lroc * (1 / (1 + ($lroc - (100 / $vrr)) / 100));

			$crr = 100 / $alroc;

			$vcr = $vrr / $crr;

			$ver = $vcr / (1 + $def->der);*/

			//match annual
			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$annualMatch = $matches[0];

			//match annual names
			preg_match_all('/\<td\>(\<font[^\>]*\>)?([A-Z][a-z]{2}\d{2})(\<\/font\>)?\<\/td\>/', $annualMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no roc annual names';
			}

			$lyName = str_replace(',', '', end($matches)[2]);

			//match trailing
			preg_match('/(Quarterly|Semi-Annual) Data[\s\S]+Calculation/', $ctt, $matches);

			$trailMatch = $matches[0];

			//match trailing name
			preg_match_all('/\<td\>(\<font[^\>]*\>)?([A-Z][a-z]{2}\d{2})(\<\/font\>)?\<\/td\>/', $trailMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no roc trailing names';
			}

			$tyName = str_replace(',', '', end($matches)[2]);

			//$isFirstQuarter = in_array(date('M'), self::$firstQuarter);
			$isFirstQuarter = $lyName == $tyName;

			//shift year data by 1 if is $firstQuarter
			$yrShift = ($isFirstQuarter == true) ? 1 : 0;

			//match annual figures
			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $annualMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no roc annual data';
			}

			$matchCnt = count($matches) - $yrShift;

			self::$def->lyroc = str_replace(',', '', $matches[$matchCnt - 1][2]);
			self::$def->slyroc = str_replace(',', '', $matches[$matchCnt - 2][2]);
			self::$def->tlyroc = str_replace(',', '', $matches[$matchCnt - 3][2]);

			$l3yAvgRoc = (self::$def->lyroc + self::$def->slyroc + self::$def->tlyroc) / 3;

			//in case roc was 0
			self::$def->slyroc = (self::$def->slyroc == 0) ? 1 : self::$def->slyroc;
			self::$def->tlyroc = (self::$def->tlyroc == 0) ? 1 : self::$def->tlyroc;

			$lytlrocr = self::$def->lyroc / self::$def->slyroc;

			self::$def->arocg = ((self::$def->lyroc - self::$def->slyroc) / abs(self::$def->slyroc) + (self::$def->slyroc - self::$def->tlyroc) / abs(self::$def->tlyroc)) / 2;

			//chk for semi annual
			preg_match('/Semi-Annual Data[\s\S]+Calculation/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if ($matches) {
				//add 2 semi annual
				$matchCnt = count($matches);

				self::$def->t12maroc = str_replace(',', '', $matches[$matchCnt - 1][2]);
				self::$def->lt12maroc = str_replace(',', '', $matches[$matchCnt - 2][2]);

				$at12maroc = (self::$def->t12maroc + self::$def->lt12maroc) / 2;
			} else {
				//chk for quarter
				preg_match('/Quarterly Data[\s\S]+Calculation/', $ctt, $matches);

				$tmpMatch = $matches[0];

				preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

				if (!$matches) {
					return 'no roc quarterly / semi annual data';
				}

				//add 4 quaters
				$matchCnt = count($matches);

				self::$def->t12maroc = str_replace(',', '', $matches[$matchCnt - 1][2]);
				self::$def->lt12maroc = str_replace(',', '', $matches[$matchCnt - 2][2]);
				self::$def->slt12maroc = str_replace(',', '', $matches[$matchCnt - 3][2]);
				self::$def->tlt12maroc = str_replace(',', '', $matches[$matchCnt - 4][2]);

				$at12maroc = (self::$def->t12maroc + self::$def->lt12maroc + self::$def->slt12maroc + self::$def->tlt12maroc) / 4;
			}

			self::$def->t12maroc = $at12maroc;

			//in case was 0
			if (self::$def->lyroc <= 0) {
				if (self::$def->t12maroc == 0 && self::$def->lyroc == 0) {
					self::$def->tlrocr = self::MIN_GROWTH_HARD + .01;
				} else {
					self::$def->tlrocr = 0;
				}
			} else {
				self::$def->tlrocr = normTl(self::$def->t12maroc, self::$def->lyroc, $l3yAvgRoc);
			}

			$ctt = $result['te'];

			preg_match('/quarter[\s\S]+Q\: [\s\S]+Q\: [\s\S]+\<td\>([\-.\d]+)\<\/td\>\<td\> ?\- ?\<\/td\>\<td\>([\-.\d]+|N\/A)\<\/td\>[\s\S]+Related Terms\<\/p\>/', $ctt, $matches);

			if (!$matches) {
				return 'no te ta tl';
			}

			//current assets and liabilities
			self::$def->ca = str_replace(',', '', $matches[1]);
			self::$def->cl = str_replace(',', '', $matches[2]);

			if (self::$def->cl == 'N/A') {
				self::$def->cl = 0;
			}

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no te';
			}

			self::$def->slye = str_replace(',', '', $matches[count($matches) - 2][2]);

			self::$def->slycap = (1 + self::$def->slyder) * self::$def->slye;

			self::$def->lypcr = self::$def->lypii / self::$def->slycap;

			self::$def->t12mpcr = self::$def->t12mpii / self::$def->lycap;

			$ctt = $result['oi'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no oi';
			}

			self::$def->lyoi = str_replace(',', '', $matches[count($matches) - 1][2]);

			if (self::$def->lyoi == 0) {
				self::$def->lyoi = self::$def->lyni;
			}

			preg_match('/Operating Income\: (CN¥|\$|.*ZAR\<\/span\> |.*USD\<\/span\> )(.+) Mil/', $ctt, $matches);

			self::$def->t12moi = str_replace(',', '', $matches[2]);

			if (self::$def->t12moi == 0) {
				self::$def->t12moi = self::$def->t12mni;
			}

			//measures the impact of operating income on net income
			$lyoinir = seCalc::getOinir(self::$def->lyoi, self::$def->lyni);

			$coinir = seCalc::getOinir(self::$def->t12moi, self::$def->t12mni);

			//adjusted last year ni
			$alyni = self::$def->lyni * $lyoinir;

			$at12mni = self::$def->t12mni * $coinir;
			self::$def->at12mni = $at12mni;

			self::$def->adjl3yavgni = self::$def->l3yavgni * $coinir;
			self::$def->adjsl3yavgni = self::$def->sl3yavgni * $lyoinir;
			self::$def->al5yavgni = self::$def->l5yavgni * $coinir;

			$ctt = $result['om'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no om';
			}

			self::$def->lyom = str_replace(',', '', $matches[count($matches) - 1][2]);
			self::$def->slyom = str_replace(',', '', $matches[count($matches) - 2][2]);
			self::$def->tlyom = str_replace(',', '', $matches[count($matches) - 3][2]);

			//in case om was 0
			if (self::$def->slyom <= 0 || self::$def->tlyom <= 0) {
				if (self::$def->lyom == 0 && self::$def->slyom == 0 && self::$def->tlyom == 0) {
					self::$def->aomg = self::MIN_GROWTH_HARD + .01;
				} else {
					self::$def->aomg = 0;
				}
			} else {
				self::$def->aomg = (self::$def->lyom / self::$def->slyom + self::$def->slyom / self::$def->tlyom) / 2;
			}

			if (self::$def->slyom <= 0) {
				$lytlomr = 0;
			} else {
				$lytlomr = self::$def->lyom / self::$def->slyom;
			}

			if ($isFirstQuarter) {
				self::$def->t12maom = self::$def->lyom;
			} else {
				preg_match('/(Quarterly|Semi-Annual) Data[\s\S]+Calculation/', $ctt, $matches);

				$tmpMatch = $matches[0];

				preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

				if (!$matches) {
					return 'no t12mom';
				}

				$len = count($matches);

				self::$def->t12maom = str_replace(',', '', $matches[$len - 1][2]);
				self::$def->lt12maom = str_replace(',', '', $matches[$len - 2][2]);
				self::$def->slt12maom = str_replace(',', '', $matches[$len - 3][2]);
				self::$def->tlt12maom = str_replace(',', '', $matches[$len - 4][2]);

				$at12maom = (self::$def->t12maom + self::$def->lt12maom + self::$def->slt12maom + self::$def->tlt12maom) / 4;

				self::$def->t12maom = min($at12maom, self::$def->t12maom);
			}

			//in case om was 0
			if (self::$def->lyom <= 0) {
				if (self::$def->lyom == 0 && self::$def->t12maom == 0) {
					self::$def->tlomr = self::MIN_GROWTH_HARD + .01;
				} else {
					self::$def->tlomr = 0;
				}
			} else {
				self::$def->tlomr = self::$def->t12maom / self::$def->lyom;
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
					self::$def->wacodr = 9999.9999;
				} else {
					//error;
					die('cost of debt error');
				}
			} else {
				self::$def->wacodr = str_replace(',', '', $matches[1]) / 100;
			}

			$ctt = $result['roe'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no roe';
			}

			$len = count($matches);

			self::$def->lyroe = str_replace(',', '', $matches[$len - 1][2]);
			self::$def->slyroe = str_replace(',', '', $matches[$len - 2][2]);
			self::$def->tlyroe = str_replace(',', '', $matches[$len - 3][2]);

			self::$def->aroe = (self::$def->lyroe + self::$def->slyroe + self::$def->tlyroe) / 3;

			//in case roe was 0
			self::$def->slyroe = (self::$def->slyroe == 0) ? 1 : self::$def->slyroe;
			self::$def->tlyroe = (self::$def->tlyroe == 0) ? 1 : self::$def->tlyroe;

			$lytlroer = self::$def->lyroe / self::$def->slyroe;

			self::$def->aroeg = ((self::$def->lyroe - self::$def->slyroe) / abs(self::$def->slyroe) + (self::$def->slyroe - self::$def->tlyroe) / abs(self::$def->tlyroe)) / 2;

			if ($isFirstQuarter) {
				self::$def->t12maroe = self::$def->lyroe;
			} else {
				preg_match('/(Quarterly|Semi-Annual) Data[\s\S]+Calculation/', $ctt, $matches);

				$tmpMatch = $matches[0];

				preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

				if (!$matches) {
					return 'no t12mroe';
				}

				$len = count($matches);

				self::$def->t12maroe = str_replace(',', '', $matches[$len - 1][2]);
				self::$def->lt12maroe = str_replace(',', '', $matches[$len - 2][2]);
				self::$def->slt12maroe = str_replace(',', '', $matches[$len - 3][2]);
				self::$def->tlt12maroe = str_replace(',', '', $matches[$len - 4][2]);

				$at12maroe = (self::$def->t12maroe + self::$def->lt12maroe + self::$def->slt12maroe + self::$def->tlt12maroe) / 4;

				self::$def->t12maroe = $at12maroe;
			}

			//in case roe was 0
			if (self::$def->lyroe <= 0) {
				if (self::$def->lyroe == 0 && self::$def->t12maroe == 0) {
					self::$def->tlroer = self::MIN_GROWTH_HARD + .01;
				} else {
					self::$def->tlroer = 0;
				}
			} else {
				self::$def->tlroer = self::$def->t12maroe / self::$def->lyroe;

				//normalize trailing vs last
				$tlroe_diff = abs(self::$def->t12maroe - self::$def->lyroe);
				//the higher the ratio, the less probability it is true
				$tlroe_prob = 1 - $tlroe_diff / ($tlroe_diff + self::$def->lyroe);

				self::$def->tlroer = 1 + (self::$def->tlroer - 1) * $tlroe_prob;
			}

			//project income using last year's data
			//except for wacodr, wacodr is assumed to be unchanged
			$pcv = seCalc::pjtIcm($lyoinir, self::$def->lypcr, $lytlomr, self::$def->lycap, self::$def->lyd, self::$def->wacodr);

			self::$def->pci = $pcv->pjtIcm;

			if ((self::$def->pci <= 0) || ($at12mni < 0)) {
				self::$def->pa = 0;
			} else {
				self::$def->pa = $at12mni / self::$def->pci;
			}

			//using current data
			$pfv = seCalc::pjtIcm($coinir, self::$def->t12mpcr, self::$def->tlomr, self::$def->cap, self::$def->debt, self::$def->wacodr);

			self::$def->pfi = $pfv->pjtIcm;

			self::$def->apfi = self::$def->pfi * self::$def->pa * $coinir;

			//equity + liability (debt) = total assets
			//return on equity is not reliable if there is a lot of liabilities
			//return on assets is more accurate of how the company is doing
			$ctt = $result['rota'];

			preg_match('/is ranked[\s\S]+(lower|higher)[\s\S]+\<strong\>(\d+)%\<\/strong\> of the[\s\S]+Companies/', $ctt, $matches);

			if (!$matches) {
				self::$def->rotaRank = self::ROTA_RANK_PASS;
			} else {
				self::$def->rotaRank = str_replace(',', '', $matches[2]);

				if ($matches[1] == 'lower') {
					self::$def->rotaRank = 100 - self::$def->rotaRank;
				} else {
					self::$def->rotaRank++;
				}
			}

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no rota';
			}

			$len = count($matches);

			$lyrota = str_replace(',', '', $matches[$len - 1][2]);
			$slyrota = str_replace(',', '', $matches[$len - 2][2]);
			$tlyrota = str_replace(',', '', $matches[$len - 3][2]);
			$frlyrota = str_replace(',', '', $matches[$len - 4][2]);
			$filyrota = str_replace(',', '', $matches[$len - 5][2]);

			//self::$def->arote = (str_replace(',', '', $matches[2]) + str_replace(',', '', $matches[5]) + str_replace(',', '', $matches[8]) + str_replace(',', '', $matches[11]) + str_replace(',', '', $matches[14]) + str_replace(',', '', $matches[17]) + str_replace(',', '', $matches[20]) + str_replace(',', '', $matches[23]) + str_replace(',', '', $matches[26]) + str_replace(',', '', $matches[29])) / 10;
			self::$def->arota = ($lyrota + $slyrota + $tlyrota) / 3;

			//check for consistency, the difference of the highest and lowest must not exceed 50%
			$lowestrota = min($lyrota, $slyrota, $tlyrota);

			if (max($lyrota, $slyrota, $tlyrota) - $lowestrota >= 30 || $lowestrota <= 0) {
				self::$def->rotaRank = 0;
			}

			//normalize return on tangible assets
			if (self::$def->cl == 0) {
				self::$def->normArota = self::$def->arota;
			} else {
				$lar = self::$def->cl / self::$def->ca;//liabilities to assets
				$ear = 1 - $lar;//equity to assets

				$ilr = (-self::$def->t12mie) / self::$def->cl;//interest expense to liabilities

				if ($ilr == 0) {
					$normLar = 0;
				} else {
					$normAdj = .01 / $ilr;

					$normLar = $lar / $normAdj;
				}

				$normAr = $normLar + $ear;

				self::$def->normArota = self::$def->arota / $normAr;
			}
			//end normalization

			$ctt = $result['rote'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no rote';
			}

			$len = count($matches);

			$lyrote = str_replace(',', '', $matches[$len - 1][2]);
			$slyrote = str_replace(',', '', $matches[$len - 2][2]);
			$tlyrote = str_replace(',', '', $matches[$len - 3][2]);
			$frlyrote = str_replace(',', '', $matches[$len - 4][2]);
			$filyrote = str_replace(',', '', $matches[$len - 5][2]);

			//check for consistency, the difference of the highest and lowest must not exceed 50%
			$lowestrote = min($lyrote, $slyrote, $tlyrote, $frlyrote, $filyrote);

			if (((max($lyrote, $slyrote, $tlyrote, $frlyrote, $filyrote) - $lowestrote) >= 50) || ($lowestrote <= 0)) {
				self::$def->arote = 0;
			} else {
				self::$def->arote = ($lyrote + $slyrote + $tlyrote + $frlyrote + $filyrote) / 5;
			}

			$ctt = $result['pe'];

			preg_match('/PE Ratio\: ([^\(]+) \(As of/', $ctt, $matches);

			if (!$matches) {
				//check if no pe ratio, due to negative earnings
				preg_match('/PE Ratio\:\(As of/', $ctt, $matches);

				if (!$matches) {
					self::$def->lper = 999.9999;
				} else {
					//error;
					return 'pe ratio error';
				}
			} else {
				self::$def->lper = str_replace(',', '', $matches[1]);
			}

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			$len = count($matches);

			self::$def->aper = (str_replace(',', '', $matches[$len - 1][2]) + str_replace(',', '', $matches[$len - 2][2]) + str_replace(',', '', $matches[$len - 3][2])) / 3;

			$ctt = $result['pb'];

			preg_match('/PB Ratio\: ([^\(]+) \(As of/', $ctt, $matches);

			if (!$matches) {
				//check if no pb ratio, due to too much liability and negative book value
				preg_match('/PB Ratio\:\(As of/', $ctt, $matches);

				if (!$matches) {
					self::$def->lpbr = 999.9999;
				} else {
					//error;
					return 'pb ratio error';
				}
			} else {
				self::$def->lpbr = str_replace(',', '', $matches[1]);
			}

			preg_match('/Annual Data[\s\S]+PB Ratio[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			$len = count($matches);

			self::$def->apbr = (str_replace(',', '', $matches[$len - 1][2]) + str_replace(',', '', $matches[$len - 2][2]) + str_replace(',', '', $matches[$len - 3][2])) / 3;

			//adjusted latest pe / pb ratio
			$alper = (self::$def->lper <= 0) ? 999.9999 : self::$def->lper;
			$alpbr = (self::$def->lpbr <= 0) ? 999.9999 : self::$def->lpbr;

			//graham test of latest price
			self::$def->gtlp = $alper * $alpbr / self::$car;
			//latest price graham condition
			self::$def->lpgc = (self::$def->gtlp > 22.5) ? 0 : 1;

			//adjusted average pe / pb ratio
			$aaper = (self::$def->aper <= 0) ? 999.9999 : self::$def->aper;
			$aapbr = (self::$def->apbr <= 0) ? 999.9999 : self::$def->apbr;

			self::$def->gtap = $aaper * $aapbr / self::$car;
			self::$def->apgc = (self::$def->gtap > 22.5) ? 0 : 1;

			self::$def->pc = self::$def->lpgc * self::$cc;

			self::$cpHtml = $result['cp'];

			$cpResult = self::getCp();

			if (is_string($cpResult)) {
				return $cpResult;
			}

			self::$def->cpResult = $cpResult;

			$ctt = $result['nios'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no nios';
			}

			$len = count($matches);

			self::$def->anios = (str_replace(',', '', $matches[$len - 1][2]) + str_replace(',', '', $matches[$len - 2][2]) + str_replace(',', '', $matches[$len - 3][2])) / 3 / $cpResult->cp;

			//in case so eop is 0, we assume an abstract value of 1 for calculation purposes
			//if there is anios, this would result in future price significantly lower than cp
			//this is fine, because we are gonna ignore this stock due to lack of data
			if (self::$def->so == 0) {
				//self::$def->so = 1;
				return 'no so';
			}

			if (self::$def->adjsl3yavgni == 0) {
				self::$def->cigr = 1;
			} else {
				self::$def->cigr = self::$def->adjl3yavgni / self::$def->adjsl3yavgni;
			}

			if (self::$def->adjsl3yavgni < 0) {
				self::$def->cigr = abs(self::$def->cigr);
			}

			//value is the worth of (current income + expectation of future income growth (positive or negative))
			//+ d & d & amortization + expected capital expenditure
			//lyv is lyni + the current igr + lydda + current capE (assuming one was to predict the igr and capE accurately last year)
			$ctt = $result['dda'];//depreciation depletion and amortization

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no dda';
			}

			self::$def->lydda = str_replace(',', '', end($matches)[2]);

			preg_match('/Depreciation, Depletion and Amortization\: (CN¥|\$|.*ZAR\<\/span\> |.*USD\<\/span\> )(.+) Mil/', $ctt, $matches);

			if (!$matches) {
				return 'no t12mdda';
			}

			self::$def->t12mdda = str_replace(',', '', $matches[2]);

			$ctt = $result['capE'];

			preg_match('/Capital Expenditure\: (CN¥|\$|.*ZAR\<\/span\> |.*USD\<\/span\> )(.+) Mil/', $ctt, $matches);

			if (!$matches) {
				return 'no t12mcapE';
			}

			self::$def->t12mcapE = str_replace(',', '', $matches[2]);

			$lyvIcm = (self::$def->adjsl3yavgni + self::$def->lydda + self::$def->t12mcapE) * self::$def->cigr * self::VIR / (1 + self::DR);

			//alternative valuation is equity + expected income (assuming expecation is accurate)
			$lyvE = (self::$def->lye + self::$def->adjl3yavgni) / (1 + self::DR);

			//last year value price is then last year value / by expected (this year's) so
			self::$def->prlyvIcm = ($lyvIcm + $lyvE) / self::$def->so;
			//self::$def->prlyvE = $lyvE / self::$def->so;

			//self::$def->prlyv = min(self::$def->prlyvIcm, self::$def->prlyvE) / (1 + self::$ir);
			self::$def->prlyv = self::$def->prlyvIcm / (1 + self::$ir);

			//thus, cv is t12mni (current ni) + the expected igr of the future + current dda + future capE
			//(although we do not know what the igr will be in the future)
			//thus, we use the current igr, but adjust it with a few factors
			//and future capE = current capE - current change in capE
			$ar_arr = [];

			if (self::$def->lyom != 0 || self::$def->t12maom != 0) {
				$ar_arr[] = self::$def->tlomr;//adds 1 elm to end of arr
			}

			if (self::$def->lyroe != 0 || self::$def->t12maroe != 0) {
				$ar_arr[] = self::$def->tlroer;//adds 1 elm to end of arr
			}

			if (self::$def->t12maroc != 0 || self::$def->lyroc != 0) {
				$ar_arr[] = self::$def->tlrocr;//adds 1 elm to end of arr
			}

			$ar = min($ar_arr);
/*
			if ((self::$def->tlroer == 0) && (self::$def->tlrocr != 0)) {
				$ar = min(self::$def->tlomr, self::$def->tlrocr);
			} else if ((self::$def->tlrocr == 0) && (self::$def->tlroer != 0)) {
				$ar = min(self::$def->tlomr, self::$def->tlroer);
			} else if ((self::$def->tlrocr == 0) && (self::$def->tlroer == 0)) {
				$ar = self::$def->tlomr;
			}
*/
			self::$def->cpigr = self::pjtIgr(self::$def->cigr, $ar, self::$def->adjl3yavgni, self::$def->so);

			$ctt = $result['cCapE'];

			preg_match('/Quarterly Data[\s\S]+Change In Working Capital[\s\S]+Calculation/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			//the t12m is actually average t12m, but name not changed due to laziness
			if ($matches) {
				//add up 8 quarters, divide by 2, 2 year annualized
				//only 5 data allowed for non logged in version
				$len = count($matches);

				self::$def->t12mcCapE = (str_replace(',', '', $matches[$len - 1][2]) + str_replace(',', '', $matches[$len - 2][2]) + str_replace(',', '', $matches[$len - 3][2]) + str_replace(',', '', $matches[$len - 4][2]) + str_replace(',', '', $matches[$len - 5][2])) / 5 * 4;
			} else {
				//check for semi-annual data
				preg_match('/Semi-Annual Data[\s\S]+Change In Working Capital[\s\S]+Calculation/', $ctt, $matches);

				$tmpMatch = $matches[0];

				preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

				if ($matches) {
					$len = count($matches);

					self::$def->t12mcCapE = (str_replace(',', '', $matches[$len - 1][2]) + str_replace(',', '', $matches[$len - 2][2]) + str_replace(',', '', $matches[$len - 3][2]) + str_replace(',', '', $matches[$len - 4][2])) / 2;
				} else {
					return 'no quaterly semi annually cCapE';
				}
			}

			$cpcapE = self::$def->t12mcapE - self::$def->t12mcCapE;//crt proected

			$cvIcm = self::estimatedValueIcm(self::$def->adjl3yavgni, self::$def->cpigr, self::$def->t12mdda, $cpcapE);

			//alternative current value is equity + projected income
			$ecv = self::estimatedValueE(self::$def->ce, self::$def->adjl3yavgni, self::$def->cpigr);
			$cvE = $ecv->ev;
			$feE = $ecv->fe;

			//projected so
			//probability
			$iosPCash = 0;

			//if issuance
			if (self::$def->anios > 0) {
				//if return stays the same (1), then there may be no need for more issuance, so issusance is 0 (1 - 1)
				//if return increases, issuance may turn into buybacks
				//if return reduces, there may be more issuances
				$iosPCash = (1 - self::$def->tlrocr) * self::$def->anios;
			} else if (self::$def->anios < 0) {//if buyback
				//the iosPCash is directly in proportion to return change
				$iosPCash = self::$def->anios / (self::$def->so - self::$def->anios) * self::$def->tlrocr * self::$def->so;
			}

			$iosPAmt = 0;

			//if still issuance
			if ($iosPCash > 0) {
				//the more issuance, the less likely of probability of issuance
				$iosPAmt = 1 - $iosPCash / ($iosPCash + self::$def->so);
			} else if ($iosPCash < 0) {//if still buyback
				//the more buyback, the less likely of it happening
				//if buyback is greater than shares outstanding, it might turn into an issuance
				//to maintain a certain shares oustanding number
				$iosPAmt = 1 - abs($iosPCash) / self::$def->so;
			}

			$pios = $iosPAmt * $iosPCash;

			if ((-$pios) / self::$def->so > .2) {
				$pso = self::$def->so;
			} else {
				$pso = self::$def->so + $pios;
			}

			if ($pso <= 0) {
				self::$def->prcvIcm = 0;
				self::$def->prcv0gIcm = 0;
				//self::$def->prcvE = 0;
				//self::$def->prcv0gE = 0;
			} else {
				self::$def->prcvIcm = ($cvIcm + $cvE) / $pso;
				self::$def->prcv0gIcm = (self::$def->adjl3yavgni * self::VIR + self::$def->ce + self::$def->adjl3yavgni) / (1 + self::DR) / $pso;

				//self::$def->prcvE = $cvE / $pso;
				//self::$def->prcv0gE = (self::$def->ce + $at12mni) / (1 + self::DR) / $pso;
			}

			self::$def->prcv = self::$def->prcvIcm / (1 + self::$ir);
			self::$def->prcv0g = self::$def->prcv0gIcm / (1 + self::$ir);

			self::$def->niosi = (self::$def->so - $pso) / self::$def->so;

			if (self::$def->cpigr === 0) {
				self::$def->fpigr = 0;
			} else {
				self::$def->fpigr = self::pjtIgr(self::$def->cpigr, $ar, self::$def->adjl3yavgni, $pso);
			}

			$fvIcm = self::estimatedValueIcm(self::$def->adjl3yavgni, self::$def->fpigr, self::$def->t12mdda, $cpcapE);
			$efv = self::estimatedValueE($feE, self::$def->adjl3yavgni, self::$def->fpigr);
			$fvE = $efv->ev;

			self::$def->fpIcm = ($fvIcm + $fvE) / $pso;
			//self::$def->fpE = $fvE / $pso;

			self::$def->fp = self::$def->fpIcm / (1 + self::$ir);

			self::$def->fptm = self::$def->fp / (1 + self::$ir);

			if (self::$def->fpigr == 0) {
				self::$def->dwmoe = 0;
			} else if (self::$def->cigr == 0) {
				self::$def->dwmoe = 1 - 20 / self::$def->fpigr;
			} else {
				self::$def->dwmoe = 1 - 20 / self::$def->cigr / self::$def->fpigr;
			}

			if (self::$def->dwmoe < 0) {
				self::$def->dwmoe = 0;
			}

			$adj = 1 - self::$def->dwmoe;

			if (self::$def->fpigr < 0) {
				$adj = abs($adj);
			}

			$afpigr = self::$def->fpigr * $adj;

			//fptm adjusted for margin of error
			//upward moe is same as dr, because we want to tolerate as little moe as possible
			//dr is the non greedy margin for selling (when above projection),
			//and the selling point is where the moe ends and we stop tolerating price deviations
			//downward moe is dynamically calculated depending of different type of stock
			//more precisely depending on the standard deviation of the stock
			//but in this case, we are just using growth rate
			$aefv = self::estimatedValueE($feE, self::$def->adjl3yavgni, $afpigr);//adj esti fv
			self::$def->afptmIcm = (self::estimatedValueIcm(self::$def->adjl3yavgni, $afpigr, self::$def->t12mdda, $cpcapE) + $aefv->ev) / $pso;
			//self::$def->afptmE =  $aefv->ev / $pso;

			self::$def->afptm = self::$def->afptmIcm / (1 + self::$ir) / (1 + self::$ir);

			//price floor calculation assumes the worst case senario
			$lfar = ($ar > 1) ? 1 : $ar;

			if (self::$def->cigr > 1) {
				$lfcpigr = self::pjtIgr(1, $lfar, self::$def->adjl3yavgni, self::$def->so);
			} else {
				$lfcpigr = self::$def->cpigr;
			}

			//$lfecv = self::estimatedValueE(self::$def->ce, $at12mni, $lfcpigr);
			//$lffeE = $ecv->fe;

			$lfpso = max($pso, self::$def->so);

			if ($lfcpigr == 0) {
				$lffpigr = 0;
			} else if ($lfcpigr > 1) {
				$lffpigr = self::pjtIgr(1, $lfar, self::$def->adjl3yavgni, $lfpso);
			} else {
				$lffpigr = self::pjtIgr($lfcpigr, $lfar, self::$def->adjl3yavgni, $lfpso);
			}

			if ($lffpigr > 1) {
				$lffpigr = 1;
			}

			if (($lffpigr == 0) || (self::$def->cigr == 0)) {
				$lfdwmoe = 0;
			} else {
				$lfdwmoe = 1 - 20 / self::$def->cigr / $lffpigr;
			}

			if ($lfdwmoe < 0) {
				$lfdwmoe = 0;
			}

			$lfadj = 1 - $lfdwmoe;

			if ($lffpigr < 0) {
				$lfadj = abs($lfadj);
			}

			$lfafpigr = $lffpigr * $lfadj;

			$lfaefv = self::estimatedValueE(self::$def->ce, self::$def->adjl3yavgni, $lfafpigr);
			self::$def->lffptmIcm = (self::estimatedValueIcm(self::$def->adjl3yavgni, $lfafpigr, self::$def->t12mdda, $cpcapE) + $lfaefv->ev) / $lfpso;
			//self::$def->lffptmE = $lfaefv->ev / $lfpso;

			self::$def->lffptm = self::$def->lffptmIcm / (1 + self::$ir) / (1 + self::$ir);
			//end price floor calculation

			//premium or discount adjustment
			//p o d
	/*		self::$def->pdadj = self::$def->rotaRank / self::ROTA_RANK_PASS;

			//popularity
			$rotaPplr = min(1, self::$def->rotaRank / self::ROTA_RANK_PASS_PPLR);

			if ($at12mni <= 0) {
				self::$def->pplradj = 0;
			} else {
				$numDgtsMin = floor(log(self::T12MNI_PPLR, 10) + 1);//5
				$numDgtsNi = floor(log($at12mni, 10) + 1);//1

				$numDgtsDiff = $numDgtsMin - $numDgtsNi;//4

				$niPplrPctTop = self::PPLR_PCT_STEP_UP;
				$niPplrPctBtm = 1;
				$tmpPct = self::PPLR_PCT_STEP_DOWN;

				if ($numDgtsDiff > 0) {
					self::$def->pplradj = 1;

		//			while ($numDgtsDiff > 0) {
		//				$niPplrPctTop = $niPplrPctBtm;//1, .8, .32

		//				$niPplrPctBtm *= $tmpPct;//.8, .32, .064

		//				$tmpPct /= 2;//.8, .4, .2

		//				$numDgtsDiff--;//2, 1, 0
		//			}
				} else {
					$tmpPct = self::PPLR_PCT_STEP_UP;

					while ($numDgtsDiff < 0) {//assuming -2
						$niPplrPctBtm = $niPplrPctTop;//1.2, 2.88

						$tmpPct *= 2;//2.4, 4.8

						$niPplrPctTop *= $tmpPct;//2.88, 13.824

						$numDgtsDiff++;//-1, 0
					}

					$niPplrBtm = pow(10, $numDgtsNi - 1);//1
					$niPplrPct = (($at12mni - $niPplrBtm) / ($niPplrBtm * 9) + $rotaPplr) / 2 * ($niPplrPctTop - $niPplrPctBtm);//(100 / 900 + .94) / 2 * .288 = .151

					self::$def->pplradj = $niPplrPctBtm + $niPplrPct;//.343
				}
			}

			$ppadj = self::$def->pdadj * self::$def->pplradj;*/

			self::$def->popadj = pow(1 - self::$def->glbRank / self::TTL_GLB_RANK, 2) * (self::MAX_P - self::MAX_D) + self::MAX_D;

			//profitability adjustment
			//we will have 2 adj, 1 forward looking and 1 historical
			//the lower one will be used for price floor, the higher for price ceiling
			self::$def->flppadj = self::calcPpAdj(self::$def->adjl3yavgni * self::$def->fpigr / (1 + self::$ir) / (1 + self::$ir));
			self::$def->histppadj = self::calcPpAdj(self::$def->al5yavgni);

			$ppadj_high = max(self::$def->flppadj, self::$def->histppadj);
			$ppadj_low = min(self::$def->flppadj, self::$def->histppadj);
			//end profitability adj

			self::$def->prcv0g *= $ppadj_high;

			//price ceiling
			if ($ppadj_high > 1) {
				self::$def->fp *= $ppadj_high;
				self::$def->fptm *= $ppadj_high;
				self::$def->afptm *= $ppadj_high;
			}

			//price floor
			self::$def->lffptm *= $ppadj_low;
			//end premium or discount adjutment

			self::$def->ep = (self::$def->fptm + self::$def->lffptm) / 2;

			$bettingCalc = self::calcBetting(self::$def->lffptm, self::$def->ep, self::$def->fptm);
			self::$def->bp = $bettingCalc->bp;
			self::$def->abdr = $bettingCalc->abdr;
		}

		private static function getDef_db() {
			//establish connection
			require_once root.'se/cmm/lib/db.php';

			$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

			if ($mysqli->connect_error) {
				return 'Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error;
			} else {//then excute sql query
				//get all defs from the se table
				if (!$result = $mysqli->query('SELECT * FROM '.strtolower(self::$se).'_defs WHERE tkr='.self::$tkr)) {
					return 'get data from '.self::$se.' defs error: '.$mysqli->error;
				} else {
					if ($result->num_rows <= 0) {
						return 'tkr not found';
					}

					//testing without data_seek(0);
					$tbl_arr = $result->fetch_assoc();

					foreach ($tbl_arr as $def_name => $value) {
						self::$def->{$def_name} = $value;
					}
				}
			}
		}

		private static function get_r_us_tkr() {
			$tkr = self::$tkr;

			preg_match('/([A-Z]+)\.([A-Z]+)/', self::$tkr, $subTkr_matches);

			if ($subTkr_matches) {
				$tkr = $subTkr_matches[1].strtolower($subTkr_matches[2]);
			}

			return $tkr;
		}

		static function getStockDef($fullTkr, $car, $cc, $glbrank, $refresh) {
			self::$def = new stdClass;//new instance of a class can not be assigned to properties on declaration.

			self::$fullTkr = $fullTkr;
			self::$guruFullTkr = $fullTkr;

			self::$car = $car;
			self::$cc = $cc;
			self::$def->car = $car;
			self::$def->cc = $cc;
			self::$def->glbRank = $glbrank;

			self::$def->dr = self::DR;

			//guru focus's price update is too slow, we use reutors
			//parse ticker into reuters format
			preg_match('/([A-Za-z]+)\:([a-zA-Z0-9\.]+)/', $fullTkr, $tkr_matches);

			self::$tkr = $tkr_matches[2];
			self::$se = $tkr_matches[1];

			$r_tkr = self::$tkr;

			switch (self::$se) {
				case 'SHSE':
					self::$rSe = '.SS';
					self::$ir = self::CNYIR;
					self::$mp = self::CNYMP;//abitrary number of the max possible price
					self::$increment = .001;

					break;
				case 'SZSE':
					self::$rSe = '.SZ';
					self::$ir = self::CNYIR;
					self::$mp = self::CNYMP;
					self::$increment = .001;

					break;
				case 'JSE':
					self::$rSe = 'J.J';
					self::$ir = self::ZARIR;
					self::$mp = self::ZARMP;
					self::$increment = .01;

					break;
				case 'NYSE':
					self::$guruFullTkr = self::$tkr;

					$r_tkr = self::get_r_us_tkr();

					self::$rSe = '.N';
					self::$ir = self::USDIR;
					self::$mp = self::USDMP;
					self::$increment = .01;
					self::$firstQuarter = self::$usFirstQuarter;

					break;
				case 'Nasdaq':
					self::$guruFullTkr = self::$tkr;

					$r_tkr = self::get_r_us_tkr();

					self::$rSe = '.O';
					self::$ir = self::USDIR;
					self::$mp = self::USDMP;
					self::$increment = .01;
					self::$firstQuarter = self::$usFirstQuarter;

					break;
				default:
			}

			self::$cpUrl = 'https://www.reuters.com/finance/stocks/chart/'.$r_tkr.self::$rSe;

			//cal data either from siphon or from db, depend on refresh
			if ($refresh) {
				//get from siphon, include cp
				$err = self::getDef_siphon();

				if ($err != null) {
					return $err.' cpUrl: '.self::$cpUrl;
				}
			} else {
				//get from db, then get cp
				$err = self::getDef_db();

				if ($err != null) {
					return $err;
				}

				//get cp
				self::$cpHtml = seCurl::getCtts(self::$cpUrl);

				$cpResult = self::getCp();

				if (is_string($cpResult)) {
					return $cpResult;
				}

				self::$def->cpResult = $cpResult;
			}

			self::$def->cp = self::$def->cpResult->cp;
			self::$def->high = self::$def->cpResult->high;
			self::$def->low = self::$def->cpResult->low;

			if ((self::$def->bp <= 0) || (self::$def->cp <= 0)) {
				self::$def->bpcpr = -1;
			} else {
				self::$def->bpcpr = (self::$def->bp - self::$def->cp) / self::$def->cp;
			}

			self::$def->iv = self::$def->prcv0g;

			//ivcpr ratio is a non greedy ratio to buy in to get the dr
			//unless iv is 0
			if ((self::$def->lffptm <= 0) || (self::$def->cp <= 0)) {
				self::$def->ivcpr = -1;
			} else {
				self::$def->ivcpr = (self::$def->lffptm - self::$def->cp) / self::$def->cp;
			}

			//cpfptmr ratio on the other hand is a non greedy ratio to sell at a lower price
			//we are making less profit thus non greedy
			//it reflects how much profit is made from fptm to cp compared to fptm
			if (self::$def->fptm == 0) {
				self::$def->cpfptmr = 1;
			} else {
				self::$def->cpfptmr = (self::$def->cp - self::$def->fptm) / abs(self::$def->fptm);
			}

			$ppTop = (self::$def->cp - self::$def->low) / (self::$def->high - self::$def->low);
			$ppBtm = 1 - $ppTop;

			//pow graham
			$powG = (22.5 - self::$def->gtap / 2) / 22.5;

			//price percentage to the power of 3 is to adjust 80% to 50%, because 50% indicates gambling percentage
			//and 80% is the minimum price percentage equivalent of gambling percentage
			//pow value
			self::$def->pow = ($powG + pow($ppBtm, 3)) / 2;//** is the exponentiation operator, ie. to the power of, (not supported in old php versions)
			//pow momentum
			self::$def->powm = ($powG + pow($ppTop, 3)) / 2;

			self::$def->advice = 'hold';

			// && (self::$def->fpigr > self::MIN_GROWTH_REFINE)

			if ((self::$def->at12mni > self::MIN_T12MNI) && (self::$def->aomg > self::MIN_GROWTH_HARD) && (self::$def->tlomr > self::MIN_GROWTH) && (self::$def->tlroer > self::MIN_GROWTH) && (self::$def->tlrocr > self::MIN_GROWTH) && (self::$def->cpigr > self::MIN_GROWTH_REFINE) && (self::$def->niosi <= .2)) {
				if ((self::$def->bpcpr > self::$def->abdr) && (self::$def->ppadj > 1)) {
					self::$def->advice = 'betting buy';
				}

				if (self::$def->ivcpr > self::DR) {
					self::$def->advice = 'buy';
				}
			}

			if (self::$def->rotaRank > 0 && self::$def->arote > 0) {
				if (self::$def->cpfptmr >= (self::DR - .02)) {
					self::$def->advice = 'be ready to sell';
				}

				if (self::$def->cpfptmr >= self::DR || $cc <= 0) {
					self::$def->advice = 'sell';
				}
			}

			return self::$def;
		}
	}
?>
