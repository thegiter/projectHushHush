<?php
	$dr = .2;//discount rate is the minimum profit rate to justify the investment
	$mos = -.45;//margin of safety
	$vir = 10;//value to earnings ratio		
	$ir = .012;
	
	function projectedIncome($oni, $opcr, $ooi, $oyom, $ot12maom, $pequity, $pder, $wacodr, $oyroe, $ot12maroe) {
		$result = new stdClass;

		//measures the impact of operating income on net income
		$oinir = $ooi / $oni;//1
		
		$oinir = ($oinir > 1) ? 1 : $oinir;
		$oinir = ($oinir < -1) ? -1 : $oinir;
		
		//in case om was 0
		if ($oyom <= 0) {
			$tlomr = 0;
		} else {
			$tlomr = $ot12maom / $oyom;
		}
		
		$result->tlomr = $tlomr;

		$ppcr = $opcr * $oinir * $tlomr;
echo ' projected pcr: '.$ppcr;

		$pdebt = $pequity * $pder;
		$pcap = $pequity + $pdebt;
		$ppii = $pcap * $ppcr;
		
		$pinterest = $pdebt * $wacodr;
		$result->pinterest = $pinterest;
		$pi = $ppii - $pinterest;
		
			//in case om was 0
		if ($oyroe <= 0) {
			$tlroer = 0;
		} else {
			$tlroer = $ot12maroe / $oyroe;//44 / 49 = .8
		}
		
		if ($pi < 0) {
			$roeai = $pi * abs($tlroer);
		} else {
			$roeai = $pi * $tlroer;
		}
		
		$result->pi = ($pi + $roeai) / 2;
		
		return $result;
	}
	
	$def = new stdClass;

	$def->bps = 3.61;
		
	$def->so = 324794;

	$def->ce = $def->bps * $def->so;
echo '
ce: '.$def->ce;
	$def->der = .52;
echo '
der: '.$def->der;

	$def->lyni = 179461;
echo '
lyni: '.$def->lyni;

	$def->t12mni = 180232;

	$def->lyie = -269398;

	$def->lyltd = 550090;
	$def->lystd = 0;
	
	$def->lyd = $def->lyltd + $def->lystd;
echo '
ly debt: '.$def->lyd;
	$def->lye = 1031066;
	
	$def->cdebt = $def->ce * $def->der;//0
		
	$def->ccap = $def->cdebt + $def->ce;//556

	$def->lypii = $def->lyni - $def->lyie;//139.9
		
	$def->lycap = $def->lye + $def->lyd;//296.8
	$def->lypcr = $def->lypii / $def->lycap;//.45
		
	/*$alroc = $lroc * (1 / (1 + ($lroc - (100 / $vrr)) / 100));
	
	$crr = 100 / $alroc;
	
	$vcr = $vrr / $crr;
	
	$ver = $vcr / (1 + $def->der);*/

	$def->lyoi = 230944;
	
	$def->t12moi = 230521;
	
	$def->lyom = 44.34;
	
	$def->t12maom = 47.33;
	$def->lt12maom = 47.34;
	$def->slt12maom = 49.51;
	$def->tlt12maom = 27.35;
	
	$at12maom = ($def->t12maom + $def->lt12maom + $def->slt12maom + $def->tlt12maom) / 4;
	
	$lower_aom = ($at12maom < $def->t12maom) ? $at12maom : $def->t12maom;
	//$lower_aom = $def->t12maom;
	
	$def->t12maom = $lower_aom;
	
	//cost of debt can be invalid sometimes, due to company paying interest when there was no debt
	//we can't just say cost of debt is 0 in this case, but without cost of debt, we can not calculate the proper interest expense
	//we will have to skip the stock by setting cost of debt to very high
	$def->wacodr = 0.54;
	
	$def->lyroe = 19.15;

	$def->t12maroe = 17.05;
	$def->lt12maroe = 17.9;
	$def->slt12maroe = 20.09;
	$def->tlt12maroe = 10.87;
	
	$at12maroe = ($def->t12maroe + $def->lt12maroe + $def->slt12maroe + $def->tlt12maroe) / 4;
	
	$lower_aroe = ($at12maroe < $def->t12maroe) ? $at12maroe : $def->t12maroe;
	//$lower_aroe = $def->t12maroe;
	
	$def->t12maroe = $lower_aroe;
	
	$pcv = projectedIncome($def->lyni, $def->lypcr, $def->lyoi, $def->lyom, $def->t12maom, $def->ce, $def->der, $def->wacodr, $def->lyroe, $def->t12maroe);
	
	$def->pci = $pcv->pi;
echo '
projected current income: '.$def->pci;

	$def->pa = ($def->pci == 0) ? 0 : $def->t12mni / $def->pci;
echo '
projection accuracy: '.$def->pa;

	$def->fe = $def->ce + $def->pci;
	
	$pfv = projectedIncome($def->t12mni, $def->lypcr, $def->t12moi, $def->lyom, $def->t12maom, $def->fe, $def->der, $def->wacodr, $def->lyroe, $def->t12maroe);
	
	$def->pfi = $pfv->pi;
echo '
projected future income: '.$def->pfi;

	$def->apfi = $def->pfi * $def->pa;
echo '
adjusted projected fi: '.$def->apfi;
/*		$def->lper = str_replace(',', '', $matches[1]);
	
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
*/
	$def->anios = 50.67;
	
	//in case so eop is 0, we assume an abstract value of 1 for calculation purposes
	//if there is anios, this would result in future price significantly lower than cp
	//this is fine, because we are gonna ignore this stock due to lack of data
	if ($def->so == 0) {
		$def->so = 1;
	}

	$igr = $def->t12mni / $def->lyni;
echo '
incomg growth rate: '.$igr;
	
	$lyv = $def->lyni * $vir / (1 + $dr);
	$cv = $def->t12mni * $vir * $igr / (1 + $dr);
	$fv = $def->apfi * $vir * $igr / (1 + $dr);
echo '
fv: '.$fv;
	$def->fp = $fv / ($def->so + $def->anios);
	
	$def->fptm = $def->fp / (1 + $ir);
echo '
fptm: '.$def->fptm;
	$def->prcv = $cv / $def->so;
echo '
prcv: '.$def->prcv;
	$def->prlyv = $lyv / $def->so;
echo '
prlyv: '.$def->prlyv;
	$def->iv = $def->fptm / (1 + $dr);//iv is how much below the fptm in order to get the profit specified by discount rate
echo '
iv: '.$def->iv;
	$def->cp = 3.21;
echo '
cp: '.$def->cp;
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
echo '
cpivr: '.$def->cpivr;
	//cpcv ratio on the other hand is a non greedy ratio to sell at a lower price
	//we are making less profit thus non greedy
	//it reflects how much profit is made from cv to cp compared to cv
	if ($def->fptm == 0) {
		$def->cpfptmr = 1;
	} else {
		$def->cpfptmr = ($def->cp - $def->fptm) / abs($def->fptm);
	}
echo '
cpfptmr'.$def->cpfptmr;
//	$def->pow = (22.5 - $def->gtap / 2) / 22.5;
	
	$def->advice = 'hold';

	if ($def->cpivr < $mos) {
		$def->advice = 'buy';
	}
	
	if ($def->cpfptmr >= $dr) {
		$def->advice = 'sell';
	}
echo '
advice: '.$def->advice;
?>