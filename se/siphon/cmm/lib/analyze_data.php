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
		//normalized trailing vs last
		//last must always be positive (>0)
		public static function calcNormTl($t, $l, $avg) {
			if ($l == 0) {
				$tl = 1;
			} else {
				$tl = $t / $l;//2.08 / 16.8 = .123
			}

			//normalize trailing vs last
			//prob = trailing vs avg
			$diff = abs($t - $avg);//10

			$avg_size = abs($avg);//12

			//the higher the ratio, the less probability it is true
			//1 - 10 / (10 + 2)
			//1 - 10 / 12
			//1 - .83
			//.16
			//12% is the avg annual growth rate
			//2000 mil is the avg ni, which is about 10% of std
			//which in a normal distribution, means 50% of the companies make 13% and 2000 mil
			//our formula here isn't calculating probability using normal distribution
			//but because PHP is not a statistics language, this is the closest thing
			//also, in this case, it's less about probability but more about how achievable the change rate is
			//however, in doing so, we are striving for average and we will always get average
			//that means we will miss out on the outliers (those truely exceptional growth companies)
			//to combat this, we set a limit on the size to std
			//if size at std, then it follows the avg growth probablity
			//the higher the size, the less probability
			//the lower the size, the more probable
			$prob = 1;

			//1 + (.123 - 1) * .16
			//1 + -.877 * .16
			//1 + -.14
			//.86
			return 1 + ($tl - 1) * $prob;
		}

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

		public static function getYrShift($annualMatch, $trailMatch) {
			//match annual names
			preg_match_all('/\<td\>(\<font[^\>]*\>)?([A-Z][a-z]{2}\d{2})(\<\/font\>)?\<\/td\>/', $annualMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no annual names';
			}

			$lyName = str_replace(',', '', end($matches)[2]);

			//match trailing name
			preg_match_all('/\<td\>(\<font[^\>]*\>)?([A-Z][a-z]{2}\d{2})(\<\/font\>)?\<\/td\>/', $trailMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no trailing names';
			}

			$tyName = str_replace(',', '', end($matches)[2]);

			//$isFirstQuarter = in_array(date('M'), self::$firstQuarter);
			//shift year data by 1 if is $firstQuarter
			return ($lyName == $tyName) ? 1 : 0;
		}

		public static function getCp_mw($cpHtml) {
			preg_match('/\"price\"\:\"([\d.,]+)\"/', $cpHtml, $matches);

			$cp = str_replace(',', '', $matches[1]);

			preg_match('/([\d.,]+)\<\/span\>[^\/]+52 Week Range\<\/span\>[\s\S]+([\d.,]+)\<\/span\>/U', $cpHtml, $matches);

			$low = str_replace(',', '', $matches[1]);
			$high = str_replace(',', '', $matches[2]);

			$result = new stdClass;

			$result->cp = $cp;
			$result->low = $low;
			$result->high = $high;

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

		const ROTA_RANK_PASS = 40;//percent
		const ROTA_RANK_PASS_PPLR = 90;//percent
		const ROTE_PASS = 20;//percent

		const T12MNI_PPLR = 10000;
		const PPLR_PCT_STEP_DOWN = .8;
		const PPLR_PCT_STEP_UP = 1.2;
		const MAX_P = 1.4;//max premium using standard premium for top stocks
		const MAX_D = .1;//max discount
		const TTL_GLB_RANK = 4000;

		const MIN_GROWTH_HARD = .9;
		const MIN_GROWTH = .5;//minimum growth ratio required to buy for om roe roc fpigr
		const MIN_GROWTH_REFINE = .7;
		const MIN_T12MNI = 100;

		const NI_SZE_STD = 10000;//mil(7.5oz * crt 6yr avg gold price)
		const RETURN_STD = 20;//pct
		const RETURN_GRW_STD = 1;
		const ROTA_RANK_STD = 60;

		const CNYIR = 0.02;//10%
		const ZARIR = 0.07;
		const USDIR = 0.01;

		//risk free rate is the safest rate - inflation
		//which is the 3 month treasury for US
		//if risk free rate is negative, it's set to 0
		const RFR_USD = 0;//.0011 - .01
		const RFR_CNY = 0;
		const RFR_ZAR = 0;

		const CNYMP = 200;
		const ZARMP = 1000;
		const USDMP = 3600;

		const TGT_NI_USD = 60000;
		const TGT_NI_CNY = 390000;
		const TGT_NI_ZAR = 900000;

		const TGT_ICM_GR = .06;

		//private static $usFirstQuarter = ['Jan', 'Feb', 'Mar', 'Apr'];

		//private static $firstQuarter;

		private static $fullTkr = '';
		private static $tkr = '';
		private static $se = '';
		private static $guruFullTkr = '';

		private static $car = 1;
		private static $cc = 1;

		private static $mp;
		private static $ir;
		private static $rfr;
		private static $tgtNi;
		private static $increment;

		private static $rSe = '';
		private static $cpUrl = '';
		private static $cpUrl_stk = '';
		private static $cpUrl_fnd = '';
		private static $cpHtml = '';
		private static $cpHtml_stk = '';
		private static $cpHtml_fnd = '';

		private static $def;

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

			$aigr = 1 + ($aigr - 1) * $pgpr;

			//ni growth potential
			//set to 12% as 12% is the avg
			//then modify it based on the tailing size compared to std
			$expGr = .12 + max((self::NI_SZE_STD - $ni) / $ni, 0) + 1;

			$nigpr = 1 - $aigr / ($aigr + $expGr);

			$aigr = 1 + ($aigr - 1) * $nigpr;

			//deviation and volatility calculation here

			//if om is already high, growth potential is also less

			//$def->igr = ($def->igr < 1) ? 1 : $def->igr;

			if ($aigr > 20) {
				$aigr = 0;
			}

			return $aigr;
		}

		private static function estimatedValueIcm($ni, $pigr, $dda, $capE) {
			//the value estimate for is future nominal value
			//so we want to calc future nominal net icm first

			//if ni and pigr are both negative, then the previous ni was positive
			//which means we want to set pigr to positive so it keeps fni negative
			//if ni is positive but pigr is negative
			//then we want keep pigr negative so we do nothing
			if ($ni < 0) {
				$pigr = abs($pigr);
			}

			$pigr -= 1;

			//dda is money spent many years ago but wasn't reported
			//it's now being reported but no actual cash was spent
			//so on average it should reflect a more real income from owning the business
			//as some of your income is used to payback the cost from years ago
			//so if you own business for the long term, on average you would incur these cost too
			//and therefore we do not add dda back to icm

			//cap expenditure is the money spent up front
			//but not reported in the year
			//instead, it is reported through dda over the years
			//this results in the ni to be more stable and close to the average
			//rather than having big spikes due to spending
			//therefore we do not want to add the add the dda back
			//and remove capE (by adding the negative value)
			$fnni = $ni * (1 + $pigr);

			//we assume there is a fixed ratio between capE and icm
			//because the more you make the more you can spend
			//and therefore the gr for ni is the same for icm

			//once we have the future nominal icm
			//we need to bring the future nominal icm to target icm
			//because before the target icm, the company can have any growth rate
			//once reach the target icm, we can start using the target growth
			//which is important because target growth rate must be less that discount rate
			//in order for the limit to converge to a number
			$dr = 1 / self::VIR;
			$rfr = self::$rfr + self::$ir;

			$fNomIcm = $fnni;
			$icmGr = self::TGT_ICM_GR;

			if ($fNomIcm > 0) {
				//if pigr is negative, then this simply lowers the valuation further
				//for price floor this would be 0 if igr was higher than 1
				$icmGr = min($icmGr, $pigr);

				if ($fNomIcm < self::$tgtNi && $pigr > $icmGr) {
					//calc how many years it would take to get to target icm
					//then apply the formula to tgt icm
					//then adjust by inflation rate for all those years to get present value

					//if ni is negative fni would also be negative
					//if ni is positive but pigr is negative, then fni is also negative
					//if fni is positive, then both ni and pigr must be positive



					//number of years required to reach target ni with an annual growth rate
					//of adjPigr
					$years = log(self::$tgtNi / $fNomIcm) / log(1 + $pigr - self::$ir);//11

					//discount f nom icm by nbr of years
					//so we want to discount growth stocks a little higher due to the higher risk
					//but we also don't want to discount too much
					//because growth stocks are generally valued higher
					//secondly we already heavily toned down the growth rate to be safe
					//this gr only appears for price ceiling
					//this is the key differenciator between price floor and price ceiling
					//since we already have a price floor, which will be discounted to be safe
					//for price ceiling we only need a reasonable but optimistic valuation
					$grDiff = $pigr - $icmGr;

					//igr has to be higher than target for this block to execute
					//dr increase by a max of 1%
					$fNomIcm = self::$tgtNi / pow(1 + $rfr + $dr + .01 * ($grDiff / ($grDiff + $icmGr)), $years);//23000
				}
			} else {
				//if f nom icm is negative
				//then we want to use the higher as growth rate
				$icmGr = max($icmGr, abs($pigr));

				//we also want to make sure the dr is higher than $icmGr
				//but lower the dr the more gr there is, because f nom icm is negative
				//that mean we want to increase the negative valuation by reducing dr
				$dr = $dr / ($icmGr + $dr) * .01 + $dr;
			}

			//icm / (risk free rate + inflation rate + discout rate - icm gr)
			return $fNomIcm / ($rfr + $dr - $icmGr);
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

			$result->ev = $result->fe;

			return $result;
		}

		private static function estimateDiscountedPrice($ni, $dda, $capE, $pigr, $ce, $pso) {
			$rst = new stdClass;

			$ev_e = self::estimatedValueE($ce, $ni, $pigr);

			$rst->fe = $ev_e->fe;

			$rst->ev_icm = self::estimatedValueIcm($ni, $pigr, $dda, $capE);

			//a company's value can be negative if it loses money each year
			//however, for stock valuation it is fine to assume the value is 0, because stock price can not be negative

			//the equity value is mostly needed for business operations
			//which means the company can not liquidate it and distribute to shareholder
			//furthermore, the equity value will decrease due to depreciation
			//there maybe some part of equity value or cash that is excess and can be distributed
			//but it is really hard to say, for each company, how much of that equity cash is available to shareholder
			//and therefore it is best to not include equity value in valuation
			$rst->edp = max($rst->ev_icm, 0) / $pso;

			return $rst;
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
				$ppadj_ovrAmt = ($ppadj - 1) / 4.375;//((6^2 - 1) / (2^2 - 1))
				//$ppadj_ovrAmt = ($ppadj - 1) / 11.7;
				//$ppadj_ovrAmt = ($ppadj - 1);

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
			//reuters
			/*preg_match('/\"last\"\:\"([\d.,]+)\"/', self::$cpHtml, $matches);

			$cp = str_replace(',', '', $matches[1]);

			//don't need to escape . in character class
			preg_match('/\"fiftytwo_wk_high\"\:\"([\d.,]+)\"/', self::$cpHtml, $matches);

			$high = str_replace(',', '', $matches[1]);

			preg_match('/\"fiftytwo_wk_low\"\:\"([\d.,]+)\"/', self::$cpHtml, $matches);

			$low = str_replace(',', '', $matches[1]);*/

			//market watch
			//try stk html first
			$url = self::$cpUrl_stk;
			$html = self::$cpHtml_stk;
			$cpR = seCalc::getCp_mw($html);
			$cp = $cpR->cp;

			//if failed, try fnd html
			if (($cp !== 0) && !$cp) {
				$url = self::$cpUrl_fnd;
				$html = self::$cpHtml_fnd;
				$cpR = seCalc::getCp_mw($html);
				$cp = $cpR->cp;
			}

			$h = $cpR->high;
			$l = $cpR->low;

			if (($cp !== 0) && !$cp) {
				return 'get current price failed: '.$html.' cpUrl: '.$url;
			} else if (($h !== 0) && !$h) {
				return 'get 52-wk high failed: '.$html.' cpUrl: '.$url;
			} else if (($l !== 0) && !$l) {
				return 'get 52-wk low failed: '.$html.' cpUrl: '.$url;
			} else if (self::$rSe == 'J.J') {
				$cp /= 100;
				$h /= 100;
				$l /= 100;
			}

			$result = new stdClass;

			$result->cp = $cp;
			$result->low = $l;
			$result->high = $h;

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
				'ios' => 'https://www.gurufocus.com/term/Issuance_of_Stock/'.self::$guruFullTkr.'/Issuance-of-Stock/',
				'ros' => 'https://www.gurufocus.com/term/Repurchase_of_Stock/'.self::$guruFullTkr.'/Repurchase-of-Stock/',
				'dda' => 'https://www.gurufocus.com/term/CF_DDA/'.self::$guruFullTkr.'/Depreciation,-Depletion-and-Amortization/',
				'capE' => 'https://www.gurufocus.com/term/Cash+Flow_CPEX/'.self::$guruFullTkr.'/Capital-Expenditure/',
				'cCapE' => 'https://www.gurufocus.com/term/ChangeInWorkingCapital/'.self::$guruFullTkr.'/Change-In-Working-Capital/',
				'cp_stk' => self::$cpUrl_stk,
				'cp_fnd' => self::$cpUrl_fnd
			];

			$result = seCurl::multiRequest($rqss);

			$ctt = $result['mc'];

			preg_match('/\: (CN¥|\$|.*ZAR\<\/span\> |.*USD)(.+) Mil *\(As of/', $ctt, $matches);

			if (!$matches) {
				return 'no mc: '.$ctt;
			}

			self::$def->mc = str_replace(',', '', $matches[2]);

			if (self::$def->mc == 0) {
				return 'no mc: mc is 0';
			}

			$ctt =  $result['bps'];

			preg_match('/\: (CN¥|\$|.*ZAR\<\/span\> |.*USD)(.+) \(As of/', $ctt, $matches);

			if (!$matches) {
				return 'no bps';
			}

			self::$def->bps = str_replace(',', '', $matches[2]);

			$ctt = $result['so'];

			preg_match('/\: ([^\:]+) Mil *\(As of/', $ctt, $matches);

			if (!$matches) {
				return 'no so';
			}

			self::$def->so = str_replace(',', '', $matches[1]);

			self::$def->ce = self::$def->bps * self::$def->so;

			$ctt = $result['der'];

			preg_match('/\: ([^\:]+) \(As of/', $ctt, $matches);

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

			self::$def->lyder = str_replace(',', '', $matches[count($matches) - 1][2]);
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
			$sl3yni = str_replace(',', '', $matches[$matchCnt - 3][2]) + str_replace(',', '', $matches[$matchCnt - 4][2]) + str_replace(',', '', $matches[$matchCnt - 5][2]);

			//gurufocus does not update net income to the current year,
			//we add up the quaterly data to get trailing net income
			//U makes regex ungreedy, so it matches first Calculation instead of last
			preg_match('/Quarterly Data[\s\S]+Calculation/U', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if ($matches) {
				//add up 4 quarters
				$matchCnt = count($matches);

				self::$def->t12mni = str_replace(',', '', $matches[$matchCnt - 1][2]) + str_replace(',', '', $matches[$matchCnt - 2][2]) + str_replace(',', '', $matches[$matchCnt - 3][2]) + str_replace(',', '', $matches[$matchCnt - 4][2]);
			} else {
				//check for semi-annual data
				preg_match('/Semi-Annual Data[\s\S]+Calculation/U', $ctt, $matches);

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
			self::$def->tl3yAvgNi = $sl3yni / 3;
			self::$def->l5yavgni = ($avglyni + $l4yni) / 5;

			$ctt = $result['ie'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no ie';
			}

			self::$def->lyie = str_replace(',', '', end($matches)[2]);

			preg_match('/\: (CN¥|\$|.*ZAR\<\/span\> |.*USD)(.+) Mil *\(TTM As of/', $ctt, $matches);

			if (!$matches) {
				return 'no t12mie';
			}

			self::$def->t12mie = str_replace(',', '', $matches[2]);

			self::$def->lypii = self::$def->lyni - self::$def->lyie;

			self::$def->t12mpii = self::$def->t12mni - self::$def->t12mie;

			$ctt = $result['roc'];

			preg_match('/\: ([^\:]+)\% +\(As of/', $ctt, $matches);

			if (!$matches) {
				$lyRoc_pre = 0;
				$fifthLyRoc_pre = 0;

				self::$def->lyroc = 0;
				self::$def->slyroc = 0;
				self::$def->tlyroc = 0;
				self::$def->flyroc = 0;

				$trailMatch = '';
			} else {
				//$lroc = str_replace(',', '', $matches[1]);

				/*$alroc = $lroc * (1 / (1 + ($lroc - (100 / $vrr)) / 100));

				$crr = 100 / $alroc;

				$vcr = $vrr / $crr;

				$ver = $vcr / (1 + $def->der);*/

				//match annual
				preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

				$annualMatch = $matches[0];

				//match trailing
				preg_match('/(Quarterly|Semi-Annual) Data[\s\S]+Calculation/U', $ctt, $matches);

				$trailMatch = $matches[0];

				$yrShift = seCalc::getYrShift($annualMatch, $trailMatch);

				if (is_string($yrShift)) {
					return 'roc err: '.$yrShift;
				}

				//match annual figures
				preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $annualMatch, $matches, PREG_SET_ORDER);

				if (!$matches) {
					return 'no roc annual data';
				}

				$matchCnt_pre = count($matches);
				$matchCnt = $matchCnt_pre - $yrShift;

				$lyRoc_pre = floatval(str_replace(',', '', $matches[$matchCnt_pre - 1][2]));
				$fifthLyRoc_pre = floatval(str_replace(',', '', $matches[$matchCnt_pre - 5][2]));

				self::$def->lyroc = floatval(str_replace(',', '', $matches[$matchCnt - 1][2]));
				self::$def->slyroc = floatval(str_replace(',', '', $matches[$matchCnt - 2][2]));
				self::$def->tlyroc = floatval(str_replace(',', '', $matches[$matchCnt - 3][2]));
				self::$def->flyroc = floatval(str_replace(',', '', $matches[$matchCnt - 4][2]));
			}

			$l3yAvgRoc = (self::$def->lyroc + self::$def->slyroc + self::$def->tlyroc) / 3;
			$sl3yAvgRoc = (self::$def->slyroc + self::$def->tlyroc + self::$def->flyroc) / 3;

			if ($yrShift == 0) {
				$latestYrRoc = self::$def->lyroc;
				$sl2yRoc = self::$def->slyroc + self::$def->tlyroc;
				self::$def->sl3yAvgRoc = $sl3yAvgRoc;
				self::$def->tl3yAvgRoc = (self::$def->tlyroc + self::$def->flyroc + $fifthLyRoc_pre) / 3;
			} else {
				$latestYrRoc = $lyRoc_pre;
				$sl2yRoc = self::$def->lyroc + self::$def->slyroc;
				self::$def->sl3yAvgRoc = $l3yAvgRoc;
				self::$def->tl3yAvgRoc = $sl3yAvgRoc;
			}

			//in case roc was 0
			self::$def->slyroc = (self::$def->slyroc == 0) ? 1 : self::$def->slyroc;
			self::$def->tlyroc = (self::$def->tlyroc == 0) ? 1 : self::$def->tlyroc;

			$lytlrocr = self::$def->lyroc / self::$def->slyroc;

			self::$def->arocg = ((self::$def->lyroc - self::$def->slyroc) / abs(self::$def->slyroc) + (self::$def->slyroc - self::$def->tlyroc) / abs(self::$def->tlyroc)) / 2;

			//chk for trailing data
			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $trailMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				self::$def->t12maroc = 0;
				self::$def->lt12maroc = 0;
				self::$def->slt12maroc = 0;
				self::$def->tlt12maroc = 0;
			} else {
				$matchCnt = count($matches);

				self::$def->t12maroc = floatval(str_replace(',', '', $matches[$matchCnt - 1][2]));
				self::$def->lt12maroc = floatval(str_replace(',', '', $matches[$matchCnt - 2][2]));
				self::$def->slt12maroc = floatval(str_replace(',', '', $matches[$matchCnt - 3][2]));
				self::$def->tlt12maroc = floatval(str_replace(',', '', $matches[$matchCnt - 4][2]));
			}

			$at12maroc = (self::$def->t12maroc + self::$def->lt12maroc + self::$def->slt12maroc + self::$def->tlt12maroc) / 4;

			self::$def->t12maroc = ($at12maroc + $latestYrRoc) / 2;

			self::$def->crt3yAvgRoc = (self::$def->t12maroc + $sl2yRoc) / 3;

			//in case was 0
			if (self::$def->sl3yAvgRoc <= 0) {
				if (self::$def->crt3yAvgRoc == 0 && self::$def->sl3yAvgRoc == 0) {
					self::$def->tlrocr = self::MIN_GROWTH_HARD + .01;
				} else {
					self::$def->tlrocr = 0;
				}
			} else {
				self::$def->tlrocr = (seCalc::calcNormTl(self::$def->sl3yAvgRoc, self::$def->tl3yAvgRoc, self::$def->tl3yAvgRoc) + seCalc::calcNormTl(self::$def->crt3yAvgRoc, self::$def->sl3yAvgRoc, $l3yAvgRoc)) / 2;
			}

			$ctt = $result['te'];

			preg_match('/quarter[\s\S]+Q\: [\s\S]+Q\: [\s\S]+\<td\>([\-.\d]+)\<\/td\>\<td\> ?\- ?\<\/td\>\<td\>([\-.\d]+|N\/A)\<\/td\>[\s\S]+currency.\<\/p\>/', $ctt, $matches);

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

			self::$def->lye = str_replace(',', '', $matches[count($matches) - 1][2]);
			self::$def->slye = str_replace(',', '', $matches[count($matches) - 2][2]);

			self::$def->lyd = self::$def->lyder * self::$def->lye;

			self::$def->lycap = self::$def->lyd + self::$def->lye;
			self::$def->slycap = (1 + self::$def->slyder) * self::$def->slye;

			if (self::$def->slycap == 0) {
				self::$def->lypcr = 1;
			} else {
				self::$def->lypcr = self::$def->lypii / self::$def->slycap;
			}

			//disable future pre int icm calc if there is no data on ly cap
			//by setting t12mpcr to 0, future pre in icm calc will be 0 too
			if (self::$def->lycap == 0) {
				self::$def->t12mpcr = 0;
			} else {
				self::$def->t12mpcr = self::$def->t12mpii / self::$def->lycap;
			}

			$ctt = $result['oi'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				self::$def->lyoi = 0;
				self::$def->t12moi = 0;
				self::$def->l3yAvgoi = 0;
				self::$def->sl3yAvgOi = 0;
				self::$def->tl3yAvgOi = 0;
				self::$def->l5yAvgOi = 0;
			} else {
				self::$def->lyoi = str_replace(',', '', end($matches)[2]);

				$matchCnt = count($matches);

				//get sum of last 4 years ni later we add them to avg ly ni to get get historical 5 year avg
				$l2yOi = str_replace(',', '', $matches[$matchCnt - 2][2]) + str_replace(',', '', $matches[$matchCnt - 3][2]);
				$l3yOi = str_replace(',', '', $matches[$matchCnt - 2][2]) + str_replace(',', '', $matches[$matchCnt - 3][2]) + str_replace(',', '', $matches[$matchCnt - 4][2]);
				$sl3yOi = str_replace(',', '', $matches[$matchCnt - 3][2]) + str_replace(',', '', $matches[$matchCnt - 4][2]) + str_replace(',', '', $matches[$matchCnt - 5][2]);
				$l4yOi = str_replace(',', '', $matches[$matchCnt - 2][2]) + str_replace(',', '', $matches[$matchCnt - 3][2]) + str_replace(',', '', $matches[$matchCnt - 4][2]) + str_replace(',', '', $matches[$matchCnt - 5][2]);

				//we add up the quaterly data to get trailing operating income
				//U makes regex ungreedy, so it matches first Calculation instead of last
				preg_match('/Quarterly Data[\s\S]+Calculation/U', $ctt, $matches);

				$tmpMatch = $matches[0];

				preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

				if ($matches) {
					//add up 4 quarters
					$matchCnt = count($matches);

					self::$def->t12moi = str_replace(',', '', $matches[$matchCnt - 1][2]) + str_replace(',', '', $matches[$matchCnt - 2][2]) + str_replace(',', '', $matches[$matchCnt - 3][2]) + str_replace(',', '', $matches[$matchCnt - 4][2]);
				} else {
					//check for semi-annual data
					preg_match('/Semi-Annual Data[\s\S]+Calculation/U', $ctt, $matches);

					$tmpMatch = $matches[0];

					preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

					if ($matches) {
						$matchCnt = count($matches);

						self::$def->t12moi = str_replace(',', '', $matches[$matchCnt - 1][2]) + str_replace(',', '', $matches[$matchCnt - 2][2]);
					} else {
						self::$def->t12moi = 0;
					}
				}

				$avgLyOi = (self::$def->lyoi + self::$def->t12moi) / 2;

				self::$def->l3yAvgoi = ($avgLyOi + $l2yOi) / 3;
				self::$def->sl3yAvgOi = $l3yOi / 3;
				self::$def->tl3yAvgOi = $sl3yOi / 3;
				self::$def->l5yAvgOi = ($avgLyOi + $l4yOi) / 5;
			}

			if (self::$def->lyoi == 0) {
				self::$def->lyoi = self::$def->lyni;
			}

			if (self::$def->t12moi == 0) {
				self::$def->t12moi = self::$def->t12mni;
			}

			if (self::$def->l3yAvgOi == 0) {
				self::$def->l3yAvgOi = self::$def->l3yavgni;
			}

			if (self::$def->sl3yAvgOi == 0) {
				self::$def->sl3yAvgOi = self::$def->sl3yavgni;
			}

			if (self::$def->tl3yAvgOi == 0) {
				self::$def->tl3yAvgOi = self::$def->tl3yAvgNi;
			}

			if (self::$def->l5yAvgOi == 0) {
				self::$def->l5yAvgOi = self::$def->l5yavgni;
			}

			//measures the impact of operating income on net income
			$lyoinir = seCalc::getOinir(self::$def->lyoi, self::$def->lyni);
			//adjusted last year ni
			$alyni = self::$def->lyni * $lyoinir;

			$coinir = seCalc::getOinir(self::$def->t12moi, self::$def->t12mni);
			$at12mni = self::$def->t12mni * $coinir;
			self::$def->at12mni = $at12mni;

			$l3yAvgOinir = seCalc::getOinir(self::$def->l3yAvgOi, self::$def->l3yavgni);
			self::$def->adjl3yavgni = self::$def->l3yavgni * $l3yAvgOinir;

			$sl3yAvgOinir = seCalc::getOinir(self::$def->sl3yAvgOi, self::$def->sl3yavgni);
			self::$def->adjsl3yavgni = self::$def->sl3yavgni * $sl3yAvgOinir;

			$tl3yAvgOinir = seCalc::getOinir(self::$def->tl3yAvgOi, self::$def->tl3yAvgNi);
			self::$def->adjTl3yAvgNi = self::$def->tl3yAvgNi * $tl3yAvgOinir;

			$l5yAvgOinir = seCalc::getOinir(self::$def->l5yAvgOi, self::$def->l5yavgni);
			self::$def->al5yavgni = self::$def->l5yavgni * $l5yAvgOinir;

			$ctt = $result['om'];

			//match annual
			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			if (!$matches) {
				$lyOm_pre = 0;
				$fifthLyOm_pre = 0;

				self::$def->lyom = 0;
				self::$def->slyom = 0;
				self::$def->tlyom = 0;
				self::$def->flyom = 0;

				$trailMatch = '';
			} else {
				$annualMatch = $matches[0];

				//match trailing
				preg_match('/(Quarterly|Semi-Annual) Data[\s\S]+Calculation/U', $ctt, $matches);

				$trailMatch = $matches[0];

				//set yr shift if not set already
				if (!isset($yrShift)) {
					$yrShift = seCalc::getYrShift($annualMatch, $trailMatch);

					if (is_string($yrShift)) {
						return 'om err: '.$yrShift;
					}
				}

				preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $annualMatch, $matches, PREG_SET_ORDER);

				$matchCnt_pre = count($matches);
				$matchCnt = $matchCnt_pre - $yrShift;

				//if there is not enough years for the stock,
				//if might match title cells which will contain
				//a string such as Operating Margin % instead of a number
				//in this case we want to make sure it's converted to number
				//because database has specific data types in this case it has to be a decimal
				//otherwise, database will not allow it to be saved
				//floatval extracts the numeric portion of the string
				$lyOm_pre = floatval(str_replace(',', '', $matches[$matchCnt_pre - 1][2]));
				$fifthLyOm_pre = floatval(str_replace(',', '', $matches[$matchCnt_pre - 5][2]));

				self::$def->lyom = floatval(str_replace(',', '', $matches[$matchCnt - 1][2]));
				self::$def->slyom = floatval(str_replace(',', '', $matches[$matchCnt - 2][2]));
				self::$def->tlyom = floatval(str_replace(',', '', $matches[$matchCnt - 3][2]));
				self::$def->flyom = floatval(str_replace(',', '', $matches[$matchCnt - 4][2]));
			}

			$l3yAvgOm = (self::$def->lyom + self::$def->slyom + self::$def->tlyom) / 3;
			$sl3yAvgOm = (self::$def->slyom + self::$def->tlyom + self::$def->flyom) / 3;

			if ($yrShift == 0) {
				$latestYrOm = self::$def->lyom;
				$sl2yOm = self::$def->slyom + self::$def->tlyom;
				self::$def->sl3yAvgOm = $sl3yAvgOm;
				self::$def->tl3yAvgOm = (self::$def->tlyom + self::$def->flyom + $fifthLyOm_pre) / 3;
			} else {
				$latestYrOm = $lyOm_pre;
				$sl2yOm = self::$def->lyom + self::$def->slyom;
				self::$def->sl3yAvgOm = $l3yAvgOm;
				self::$def->tl3yAvgOm = $sl3yAvgOm;
			}

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

			//set trailing
			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $trailMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				self::$def->t12maom = 0;
				self::$def->lt12maom = 0;
				self::$def->slt12maom = 0;
				self::$def->tlt12maom = 0;
			} else {
				$matchCnt = count($matches);

				self::$def->t12maom = floatval(str_replace(',', '', $matches[$matchCnt - 1][2]));
				self::$def->lt12maom = floatval(str_replace(',', '', $matches[$matchCnt - 2][2]));
				self::$def->slt12maom = floatval(str_replace(',', '', $matches[$matchCnt - 3][2]));
				self::$def->tlt12maom = floatval(str_replace(',', '', $matches[$matchCnt - 4][2]));
			}

			$at12maom = (self::$def->t12maom + self::$def->lt12maom + self::$def->slt12maom + self::$def->tlt12maom) / 4;

			//the smaller of the latest avg or the avg of the 4 averages
			self::$def->t12maom = (min($at12maom, self::$def->t12maom) + $latestYrOm) / 2;;

			self::$def->crt3yAvgOm = (self::$def->t12maom + $sl2yOm) / 3;

			//in case om was 0
			if (self::$def->sl3yAvgOm <= 0) {
				if ((self::$def->sl3yAvgOm == 0) && (self::$def->crt3yAvgOm == 0)) {
					self::$def->tlomr = self::MIN_GROWTH_HARD + .01;
				} else {
					self::$def->tlomr = 0;
				}
			} else {
				self::$def->tlomr = (seCalc::calcNormTl(self::$def->sl3yAvgOm, self::$def->tl3yAvgOm, self::$def->tl3yAvgOm) + seCalc::calcNormTl(self::$def->crt3yAvgOm, self::$def->sl3yAvgOm, $l3yAvgOm)) / 2;
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

			//match annual
			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$annualMatch = $matches[0];

			//match trailing
			preg_match('/(Quarterly|Semi-Annual) Data[\s\S]+Calculation/U', $ctt, $matches);

			$trailMatch = $matches[0];

			//set yr shift if not set already
			if (!isset($yrShift)) {
				$yrShift = seCalc::getYrShift($annualMatch, $trailMatch);

				if (is_string($yrShift)) {
					return 'roe err: '.$yrShift;
				}
			}

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $annualMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no roe';
			}

			$matchCnt = count($matches) - $yrShift;

			self::$def->lyroe = floatval(str_replace(',', '', $matches[$matchCnt - 1][2]));
			self::$def->slyroe = floatval(str_replace(',', '', $matches[$matchCnt - 2][2]));
			self::$def->tlyroe = floatval(str_replace(',', '', $matches[$matchCnt - 3][2]));
			self::$def->flyroe = floatval(str_replace(',', '', $matches[$matchCnt - 4][2]));

			self::$def->aroe = (self::$def->lyroe + self::$def->slyroe + self::$def->tlyroe) / 3;
			$sl3yAvgRoe = (self::$def->slyroe + self::$def->tlyroe + self::$def->flyroe) / 3;

			if ($yrShift == 0) {
				$latestYrRoe = self::$def->lyroe;
				$sl2yRoe = self::$def->slyroe + self::$def->tlyroe;
				self::$def->sl3yAvgRoe = $sl3yAvgRoe;
				self::$def->tl3yAvgRoe = (self::$def->tlyroe + self::$def->flyroe + floatval(str_replace(',', '', $matches[count($matches) - 5][2]))) / 3;
			} else {
				$latestYrRoe = floatval(str_replace(',', '', $matches[count($matches) - 1][2]));
				$sl2yRoe = self::$def->lyroe + self::$def->slyroe;
				self::$def->sl3yAvgRoe = self::$def->aroe;
				self::$def->tl3yAvgRoe = $sl3yAvgRoe;
			}

			//in case roe was 0
			self::$def->slyroe = (self::$def->slyroe == 0) ? 1 : self::$def->slyroe;
			self::$def->tlyroe = (self::$def->tlyroe == 0) ? 1 : self::$def->tlyroe;

			$lytlroer = self::$def->lyroe / self::$def->slyroe;

			self::$def->aroeg = ((self::$def->lyroe - self::$def->slyroe) / abs(self::$def->slyroe) + (self::$def->slyroe - self::$def->tlyroe) / abs(self::$def->tlyroe)) / 2;

			//set trailing
			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $trailMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no t12mroe';
			}

			$matchCnt = count($matches);

			self::$def->t12maroe = floatval(str_replace(',', '', $matches[$matchCnt - 1][2]));
			self::$def->lt12maroe = floatval(str_replace(',', '', $matches[$matchCnt - 2][2]));
			self::$def->slt12maroe = floatval(str_replace(',', '', $matches[$matchCnt - 3][2]));
			self::$def->tlt12maroe = floatval(str_replace(',', '', $matches[$matchCnt - 4][2]));

			$at12maroe = (self::$def->t12maroe + self::$def->lt12maroe + self::$def->slt12maroe + self::$def->tlt12maroe) / 4;

			self::$def->t12maroe = ($at12maroe + $latestYrRoe) / 2;;

			self::$def->crt3yAvgRoe = (self::$def->t12maroe + $sl2yRoe) / 3;

			//in case roe was 0
			if (self::$def->lyroe <= 0) {
				if (self::$def->lyroe == 0 && self::$def->t12maroe == 0) {
					self::$def->tlroer = self::MIN_GROWTH_HARD + .01;
				} else {
					self::$def->tlroer = 0;
				}
			} else {
				self::$def->tlroer = (seCalc::calcNormTl(self::$def->sl3yAvgRoe, self::$def->tl3yAvgRoe, self::$def->tl3yAvgRoe) + seCalc::calcNormTl(self::$def->crt3yAvgRoe, self::$def->sl3yAvgRoe, self::$def->aroe)) / 2;
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

			//match annual
			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no rota';
			}

			$matchCnt = count($matches);

			$lyrota = str_replace(',', '', $matches[$matchCnt - 1][2]);
			$slyrota = str_replace(',', '', $matches[$matchCnt - 2][2]);
			$tlyrota = str_replace(',', '', $matches[$matchCnt - 3][2]);
			//$frlyrota = str_replace(',', '', $matches[$matchCnt - 4][2]);

			//self::$def->arote = (str_replace(',', '', $matches[2]) + str_replace(',', '', $matches[5]) + str_replace(',', '', $matches[8]) + str_replace(',', '', $matches[11]) + str_replace(',', '', $matches[14]) + str_replace(',', '', $matches[17]) + str_replace(',', '', $matches[20]) + str_replace(',', '', $matches[23]) + str_replace(',', '', $matches[26]) + str_replace(',', '', $matches[29])) / 10;
			self::$def->arota = ($lyrota + $slyrota + $tlyrota) / 3;

			//check for consistency, the difference of the highest and lowest must not exceed 50%
			$lowestrota = min($lyrota, $slyrota, $tlyrota);

			if (((max($lyrota, $slyrota, $tlyrota) - $lowestrota) >= 30) || ($lowestrota <= 0)) {
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

			//match annual
			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no rote';
			}

			$matchCnt = count($matches);

			$lyrote = str_replace(',', '', $matches[$matchCnt - 1][2]);
			$slyrote = str_replace(',', '', $matches[$matchCnt - 2][2]);
			$tlyrote = str_replace(',', '', $matches[$matchCnt - 3][2]);
			$frlyrote = str_replace(',', '', $matches[$matchCnt - 4][2]);
			$filyrote = str_replace(',', '', $matches[$matchCnt - 5][2]);

			//check for consistency, the difference of the highest and lowest must not exceed 50%
			$lowestrote = min($lyrote, $slyrote, $tlyrote, $frlyrote, $filyrote);

			if (((max($lyrote, $slyrote, $tlyrote, $frlyrote, $filyrote) - $lowestrote) >= 50) || ($lowestrote <= 0)) {
				self::$def->arote = 0;
			} else {
				self::$def->arote = ($lyrote + $slyrote + $tlyrote + $frlyrote + $filyrote) / 5;
			}

			$ctt = $result['pe'];

			preg_match('/\: ([^\:\(]+) \(As of/', $ctt, $matches);

			if (!$matches) {
				//check if no pe ratio, due to negative earnings
				preg_match('/\:\(As of/', $ctt, $matches);

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

			preg_match('/\: ([^\:\(]+) \(As of/', $ctt, $matches);

			if (!$matches) {
				//check if no pb ratio, due to too much liability and negative book value
				preg_match('/\:\(As of/', $ctt, $matches);

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

			self::$cpHtml_stk = $result['cp_stk'];
			self::$cpHtml_fnd = $result['cp_fnd'];

			$cpResult = self::getCp();

			if (is_string($cpResult)) {
				return $cpResult;
			}

			self::$def->cpResult = $cpResult;

			$ctt = $result['ios'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no ios';
			}

			$len = count($matches);

			self::$def->avgIos_val = (str_replace(',', '', $matches[$len - 1][2]) + str_replace(',', '', $matches[$len - 2][2]) + str_replace(',', '', $matches[$len - 3][2])) / 3;

			$ctt = $result['ros'];

			preg_match('/Annual Data[\s\S]+(Quarterly|Semi-Annual) Data/', $ctt, $matches);

			$tmpMatch = $matches[0];

			preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

			if (!$matches) {
				return 'no ros';
			}

			$len = count($matches);

			self::$def->avgRos_val = (str_replace(',', '', $matches[$len - 1][2]) + str_replace(',', '', $matches[$len - 2][2]) + str_replace(',', '', $matches[$len - 3][2])) / 3;

			self::$def->anios = (self::$def->avgIos_val - self::$def->avgRos_val) / $cpResult->cp;

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
				self::$def->cigr = (seCalc::calcNormTl(self::$def->adjsl3yavgni, self::$def->adjTl3yAvgNi, self::$def->adjTl3yAvgNi) + seCalc::calcNormTl(self::$def->adjl3yavgni, self::$def->adjsl3yavgni, self::$def->adjsl3yavgni)) / 2;
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

			preg_match('/\: (CN¥|\$|.*ZAR\<\/span\> |.*USD)(.+) Mil *\(TTM As of/', $ctt, $matches);

			if (!$matches) {
				return 'no t12mdda';
			}

			self::$def->t12mdda = str_replace(',', '', $matches[2]);

			$ctt = $result['capE'];

			preg_match('/\: (CN¥|\$|.*ZAR\<\/span\> |.*USD)(.+) Mil *\(TTM As of/', $ctt, $matches);

			if (!$matches) {
				return 'no t12mcapE';
			}

			self::$def->t12mcapE = str_replace(',', '', $matches[2]);

			//price reflecting last year's value
			$prlyv = self::estimateDiscountedPrice(self::$def->adjsl3yavgni, self::$def->lydda, self::$def->t12mcapE, self::$def->cigr, self::$def->lye, self::$def->so);
			self::$def->prlyvIcm = $prlyv->edp;
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

			//$ar = min($ar_arr);
			$ar = array_sum($ar_arr) / count($ar_arr);
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

			preg_match('/Quarterly Data[\s\S]+Change In Working Capital[\s\S]+Calculation/U', $ctt, $matches);

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
				preg_match('/Semi-Annual Data[\s\S]+Change In Working Capital[\s\S]+Calculation/U', $ctt, $matches);

				$tmpMatch = $matches[0];

				preg_match_all('/\<td\>(\<font[^\>]*\>)?([^\<]+)(\<\/font\>)?\<\/td\>/', $tmpMatch, $matches, PREG_SET_ORDER);

				if ($matches) {
					$len = count($matches);

					self::$def->t12mcCapE = (str_replace(',', '', $matches[$len - 1][2]) + str_replace(',', '', $matches[$len - 2][2]) + str_replace(',', '', $matches[$len - 3][2]) + str_replace(',', '', $matches[$len - 4][2])) / 2;
				} else {
					return 'no quaterly semi annually cCapE';
				}
			}

			//captial expenditure is the money spent on buying infrastructure such as building / equipments
			//change in working captial in the change in float
			//a change in the float (liquid cash) a company has may indicate a change in capital expenditure
			//though not necessarily
			//capital expenditure is capped at 0, because it is an expenditure, it can only be 0 or negative
			$cpcapE = min(self::$def->t12mcapE - self::$def->t12mcCapE, 0);//crt projected

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
				$cedp = self::estimateDiscountedPrice(self::$def->adjl3yavgni, self::$def->t12mdda, $cpcapE, self::$def->cpigr, self::$def->ce, $pso);
				$cpfe = $cedp->fe;
				self::$def->prcvIcm =$cedp->edp;

				//zero growth
				$prcv_0g = self::estimateDiscountedPrice(self::$def->adjl3yavgni, self::$def->lydda, self::$def->t12mcapE, 1, self::$def->ce, $pso);
				self::$def->prcv0gIcm = $prcv_0g->edp;

				//self::$def->prcvE = $cvE / $pso;
				//self::$def->prcv0gE = (self::$def->ce + $at12mni) / (1 + self::DR) / $pso;
			}

			self::$def->prcv = self::$def->prcvIcm / (1 + self::$ir);
			self::$def->prcv0g = self::$def->prcv0gIcm / (1 + self::$ir);

			self::$def->niosi = (self::$def->so - $pso) / self::$def->so;

			if (self::$def->cpigr === 0) {
				self::$def->fpigr = 0;
			} else {
				self::$def->fpigr = self::pjtIgr(self::$def->cigr * self::$def->cpigr, $ar * self::$def->cpigr, self::$def->adjl3yavgni * self::$def->cpigr, $pso);
			}

			$prfv = self::estimateDiscountedPrice(self::$def->adjl3yavgni, self::$def->t12mdda, $cpcapE, self::$def->fpigr, $cpfe, $pso);
			self::$def->fpIcm = $prfv->edp;
			//self::$def->fpE = $fvE / $pso;

			self::$def->fp = self::$def->fpIcm / (1 + self::$ir);

			self::$def->fptm = self::$def->fp / (1 + self::$ir);

			//downward margin of error
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
			self::$def->adjFedp = self::estimateDiscountedPrice(self::$def->adjl3yavgni, self::$def->t12mdda, $cpcapE, $afpigr, $cpfe, $pso);
			//self::$def->afptmE =  $aefv->ev / $pso;

			self::$def->afptm = self::$def->adjFedp->edp / (1 + self::$ir) / (1 + self::$ir);

			//price floor calculation assumes the worst case senario
			//through capping at 1, we cap the projection that we can make
			$lf_ar = min($ar, 1);
			$lf_cigr = min(self::$def->cigr, 1);

			$lf_cpigr = self::pjtIgr($lf_cigr, $lf_ar, self::$def->adjl3yavgni, self::$def->so);

			$lf_cpigr = min($lf_cpigr, 1);

			$lf_cpfe = self::estimatedValueE(self::$def->ce, self::$def->adjl3yavgni, $lf_cpigr)->fe;

			$lf_pso = max($pso, self::$def->so);

			if ($lf_cpigr == 0) {
				$lf_fpigr = 0;
			} else {
				$lf_fpigr = self::pjtIgr($lf_cigr * $lf_cpigr, $lf_ar * $lf_cpigr, self::$def->adjl3yavgni * $lf_cpigr, $lf_pso);
			}

			$lf_fpigr = min($lf_fpigr, 1);

			if (($lf_fpigr == 0) || ($lf_cigr == 0)) {
				$lf_dwmoe = 0;
			} else {
				$lf_dwmoe = 1 - 20 / $lf_cigr / $lf_fpigr;
			}

			$lf_dwmoe = max($lf_dwmoe, 0);

			$lf_adj = 1 - $lf_dwmoe;

			if ($lf_fpigr < 0) {
				$lf_adj = abs($lf_adj);
			}

			$lf_afpigr = $lf_fpigr * $lf_adj;

			$lf_afpigr = min($lf_afpigr, 1);

			self::$def->lf_fedp = self::estimateDiscountedPrice(self::$def->adjl3yavgni, self::$def->t12mdda, $cpcapE, $lf_afpigr, $lf_cpfe, $lf_pso);

			self::$def->lffptm = self::$def->lf_fedp->edp / (1 + self::DR) / (1 + self::$ir) / (1 + self::$ir);
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
			self::$def->flppadj = self::calcPpAdj(self::$def->adjl3yavgni * $afpigr / (1 + self::$ir) / (1 + self::$ir));
			self::$def->flppadj_lf = self::calcPpAdj(self::$def->adjl3yavgni * $lf_afpigr / (1 + self::$ir) / (1 + self::$ir));
			self::$def->histppadj = self::calcPpAdj(self::$def->al5yavgni);

			$ppadj_high = max(self::$def->flppadj, self::$def->flppadj_lf, self::$def->histppadj);
			$ppadj_low = min(self::$def->flppadj, self::$def->flppadj_lf, self::$def->histppadj);
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
					self::$rfr = self::RFR_CNY;
					self::$mp = self::CNYMP;//abitrary number of the max possible price
					self::$tgtNi = self::TGT_NI_CNY;
					self::$increment = .001;

					break;
				case 'SZSE':
					self::$rSe = '.SZ';
					self::$ir = self::CNYIR;
					self::$rfr = self::RFR_CNY;
					self::$mp = self::CNYMP;
					self::$tgtNi = self::TGT_NI_CNY;
					self::$increment = .001;

					break;
				case 'JSE':
					self::$rSe = 'J.J';
					self::$ir = self::ZARIR;
					self::$rfr = self::RFR_ZAR;
					self::$mp = self::ZARMP;
					self::$tgtNi = self::TGT_NI_ZAR;
					self::$increment = .01;

					break;
				case 'NYSE':
					self::$guruFullTkr = self::$tkr;

					$r_tkr = self::get_r_us_tkr();

					self::$rSe = '';
					self::$ir = self::USDIR;
					self::$rfr = self::RFR_USD;
					self::$mp = self::USDMP;
					self::$tgtNi = self::TGT_NI_USD;
					self::$increment = .01;
					//self::$firstQuarter = self::$usFirstQuarter;

					break;
				case 'Nasdaq':
					self::$guruFullTkr = self::$tkr;

					$r_tkr = self::get_r_us_tkr();

					self::$rSe = '';
					self::$ir = self::USDIR;
					self::$rfr = self::RFR_USD;
					self::$mp = self::USDMP;
					self::$tgtNi = self::TGT_NI_USD;
					self::$increment = .01;
					//self::$firstQuarter = self::$usFirstQuarter;

					break;
				default:
			}

			//self::$cpUrl = 'https://www.reuters.com/companies/'.$r_tkr.self::$rSe;
			self::$cpUrl_stk = 'https://www.marketwatch.com/investing/stock/'.self::$tkr.self::$rSe;
			self::$cpUrl_fnd = 'https://www.marketwatch.com/investing/fund/'.self::$tkr.self::$rSe;

			//cal data either from siphon or from db, depend on refresh
			if ($refresh) {
				//get from siphon, include cp
				$err = self::getDef_siphon();

				if ($err != null) {
					return $err;
				}
			} else {
				//get from db, then get cp
				$err = self::getDef_db();

				if ($err != null) {
					return $err;
				}

				//get cp
				self::$cpHtml_stk = seCurl::getCtts(self::$cpUrl_stk);
				self::$cpHtml_fnd = seCurl::getCtts(self::$cpUrl_fnd);

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
