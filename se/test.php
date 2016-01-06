<?php
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
		
		if ($so == 0) {
			$p0g = 0;
		} else {
			$p0g = $ni * $vir / $so;
		}
		
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
	
		$def = new stdClass;
		
		$def->car = 1;
		$def->cc = 1;
		
		$dr = .2;//discount rate is the minimum profit rate to justify the investment
		$bdr = .03;//the betting discount rate for smaller profit yet larger risk, but potentially higher profit as well
		$def->dr = $dr;
		$mos = .7;//margin of safety
		$vir = 10;//value to income ratio		
		
		define('CNYIR', '0.020');
		define('ZARIR', '0.061');
		define('USDIR', '0.016');
		define('CNYMP', '200');
		define('ZARMP', '1000');
		define('USDMP', '200');

				$ir = USDIR;
				$mp = USDMP;//abitrary number of the max possible price
		
		$def->mc = 20638;
		$def->bps = 4.39;
		$def->so = 423;
		$def->ce = $def->bps * $def->so;
		$def->der = .48;
		$def->debt = $def->ce * $def->der;
		$def->cap = $def->debt + $def->ce;

		$def->slyder = .27;

		$def->lyni = 112;
		$def->t12mni = 267;
		$def->lyie = -32;
		$def->t12mie = -53;
		$def->lypii = $def->lyni - $def->lyie;
		$def->t12mpii = $def->t12mni - $def->t12mie;
		
		$def->lyltd = 500;
		$def->lystd = 0;
		$def->lyd = $def->lyltd + $def->lystd;
		$def->lye = 1334;
		$def->lycap = $def->lye + $def->lyd;
		
		$def->slye = 745;
		$def->slycap = (1 + $def->slyder) * $def->slye;
		
		$def->lypcr = $def->lypii / $def->slycap;
		$def->t12mpcr = $def->t12mpii / $def->lycap;

		$def->lyoi = 228;
		$def->t12moi = 403;
		
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
		
		$def->lyom = 5.22;
		$def->slyom = 1.39;
		$def->tlyom = 11.74;
		
		//in case om was 0
		$def->slyom = ($def->slyom == 0) ? 1 : $def->slyom;
		$def->tlyom = ($def->tlyom == 0) ? 1 : $def->tlyom;
		
		$lytlomr = $def->lyom / $def->slyom;
		
		$def->aomg = (($def->lyom - $def->slyom) / abs($def->slyom) + ($def->slyom - $def->tlyom) / abs($def->tlyom)) / 2;

		$def->t12maom = 4.38;
		$def->lt12maom = 7.83;
		$def->slt12maom = 9.67;
		$def->tlt12maom = 7.68;
		
		$at12maom = ($def->t12maom + $def->lt12maom + $def->slt12maom + $def->tlt12maom) / 4;
		
		$lower_aom = ($at12maom < $def->t12maom) ? $at12maom : $def->t12maom;
		
		$def->t12maom = $lower_aom;
		
		//in case om was 0
		if ($def->lyom <= 0) {
			$def->tlomr = 0;
		} else {
			$def->tlomr = $lower_aom / $def->lyom;
		}
		
		//cost of debt can be invalid sometimes, due to company paying interest when there was no debt
		//we can't just say cost of debt is 0 in this case, but without cost of debt, we can not calculate the proper interest expense
		//we will have to skip the stock by setting cost of debt to very high
			$def->wacodr = .076;

		$def->lyroe = 10.82;
		$def->slyroe = 2.47;
		$def->tlyroe = 48.47;
		
		$def->aroe = ($def->lyroe + $def->slyroe + $def->tlyroe) / 3;
		
		//in case roe was 0
		$def->slyroe = ($def->slyroe == 0) ? 1 : $def->slyroe;
		$def->tlyroe = ($def->tlyroe == 0) ? 1 : $def->tlyroe;
		
		$lytlroer = $def->lyroe / $def->slyroe;
		
		$def->aroeg = (($def->lyroe - $def->slyroe) / abs($def->slyroe) + ($def->slyroe - $def->tlyroe) / abs($def->tlyroe)) / 2;
		
		$def->t12maroe = 18.62;
		$def->lt12maroe = 14.23;
		$def->slt12maroe = 18.4;
		$def->tlt12maroe = 15.11;
		
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
	
			$def->lper = 79.22;
		
		$def->aper = (199.23 + 307.6 + 16.61) / 3;
		
			$def->lpbr = 11.12;

		$def->apbr = (16.44 + 6.93 + 5.96) / 3;
		
		$alper = $def->lper;
		$def->alpbr = $def->lpbr;
		
		$alper = ($alper < 0) ? 999.9999 : $alper;
		$def->alpbr = ($def->alpbr < 0) ? 999.9999 : $def->alpbr;
		
		$def->gtlp = $alper * $def->alpbr / $def->car;
		$def->lpgc = ($def->gtlp > 22.5) ? 0 : 1;
		
		$aaper = $def->aper;
		$def->aapbr = $def->apbr;

		$aaper = ($aaper < 0) ? 999.9999 : $aaper;
		$def->aapbr = ($def->aapbr < 0) ? 999.9999 : $def->aapbr;
		
		$def->gtap = $aaper * $def->aapbr / $def->car;
		$def->apgc = ($def->gtap > 22.5) ? 0 : 1;
		
		$def->pc = $def->lpgc * $def->cc;
		
		$def->anios = (125 + 0 + 20) / 3;
		
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
		
		if ($def->so == 0) {
			$def->prlyv = 0;
		} else {
			$def->prlyv = $lyv / $def->so;
		}
		
		//thus, cv is t12mni (current ni) + the expected igr of the future (although we do not know what the igr will be in the future)
		//thus, we use the current igr, but adjust it with a few factors
		$lower_ar = ($def->tlomr < $def->tlroer) ? $def->tlomr : $def->tlroer;
			
		$ar = $lower_ar;
			
		//$ar = ($lower_ar < 1) ? $lower_ar : 1;
		
		$def->cpigr = projectedIgr($def->cigr, $ar, $def->t12mni, $vir, $def->so, $mp);
		
		$cv = estimatedValue($def->t12mni, $vir, $def->cpigr, $dr);
		
		if ($def->so == 0) {
			$def->prcv = 0;
			$def->prcv0g = 0;
		} else {
			$def->prcv = $cv / $def->so;
			$def->prcv0g = $def->t12mni * $vir * $coinir / (1 + $dr) / $def->so;
		}
		
		$pso = $def->so + $def->anios;
		
		if ($def->so == 0) {
			$def->niosi = -1;
		} else {
			$def->niosi = ($def->so - $pso) / $def->so;
		}
		
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
		
		$def->cp = 64;
		
		if (($def->bp <= 0) || ($def->cp <= 0)) {
			$def->bpcpr = -1;
		} else {
			$def->bpcpr = ($def->bp - $def->cp) / $def->cp;
		}
		
		$def->iv = $def->prcv0g;
		
		$lower_p = $def->lffptm;
		
		//ivcpr ratio is a non greedy ratio to buy in to get the dr
		//unless iv is 0
		if (($lower_p <= 0) || ($def->cp <= 0)) {
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
	
	echo json_encode($def);
?>