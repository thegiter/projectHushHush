<?php
	defined('root') or die;
	
	function se_update_tbl_row_siphoned($tbl_name, $tkr, $def) {
		require_once root.'se/cmm/lib/db.php';
		
		//establish connection
		if (!@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)) {
			die('User Connection Error');//or die(mysql_error());
		} else {//then select db
			if (!@mysql_select_db(DB_NAME)) {
				die('Database Connection Error');//mysql_error();
			} else {//then excute sql query
				//we assume table already exist
				if (!@mysql_query('INSERT INTO '.$tbl_name.'(tkr, mc, bps, so, ce, der, debt, cap, lyni, lyie, lypii, lyltd, lystd, lyd, lye, lycap,
					lypcr, lyom, slyom, tlyom, aomg, lqom, slqom, tlqom, flqom, t4qaom, tlomr, apcr, cpii, wacodr, interest, fi, lyroe, slyroe, tlyroe,
					aroeg, lqroe, slqroe, tlqroe, flqroe, t4qaroe, tlroer, afi, fe, lper, aper, lpbr, apbr, gtlp, lpgc, gtap, apgc, pc, anios, fp,
					fptm, prcv, iv, cpivr, cpfptmr, pow, advice)
				VALUES("'.$tkr.'", '.$def->mc.', '.$def->bps.', '.$def->so.', '.$def->ce.', '.$def->der.', '.$def->debt.', '.$def->cap.',
					'.$def->lyni.', '.$def->lyie.', '.$def->lypii.', '.$def->lyltd.', '.$def->lystd.', '.$def->lyd.', '.$def->lye.', '.$def->lycap.',
					'.$def->lypcr.', '.$def->lyom.', '.$def->slyom.', '.$def->tlyom.', '.$def->aomg.', '.$def->lqom.', '.$def->slqom.', '.$def->tlqom.', 
					'.$def->flqom.', '.$def->t4qaom.', '.$def->tlomr.', '.$def->apcr.', '.$def->cpii.', '.$def->wacodr.', '.$def->interest.',
					'.$def->fi.', '.$def->lyroe.', '.$def->slyroe.', '.$def->tlyroe.', '.$def->aroeg.', '.$def->lqroe.', '.$def->slqroe.', '.$def->tlqroe.',
					'.$def->flqroe.', '.$def->t4qaroe.', '.$def->tlroer.', '.$def->afi.', '.$def->fe.', '.$def->lper.', '.$def->aper.', '.$def->lpbr.',
					'.$def->apbr.', '.$def->gtlp.', '.$def->lpgc.', '.$def->gtap.', '.$def->apgc.', '.$def->pc.', '.$def->anios.', '.$def->fp.',
					'.$def->fptm.', '.$def->prcv.', '.$def->iv.', '.$def->cpivr.', '.$def->cpfptmr.', '.$def->pow.', "'.$def->advice.'")
				ON DUPLICATE KEY UPDATE mc=VALUES(mc), bps=VALUES(bps), so=VALUES(so), ce=VALUES(ce), der=VALUES(der), debt=VALUES(debt), cap=VALUES(cap),
				lyni=VALUES(lyni), lyie=VALUES(lyie), lypii=VALUES(lypii), lyltd=VALUES(lyltd), lystd=VALUES(lystd), lyd=VALUES(lyd), lye=VALUES(lye),
				lycap=VALUES(lycap), lypcr=VALUES(lypcr), lyom=VALUES(lyom), slyom=VALUES(slyom), tlyom=VALUES(tlyom), aomg=VALUES(aomg),
				lqom=VALUES(lqom), slqom=VALUES(slqom), tlqom=VALUES(tlqom), flqom=VALUES(flqom), t4qaom=VALUES(t4qaom), tlomr=VALUES(tlomr),
				apcr=VALUES(apcr), cpii=VALUES(cpii), wacodr=VALUES(wacodr), interest=VALUES(interest), fi=VALUES(fi), lyroe=VALUES(lyroe),
				slyroe=VALUES(slyroe), tlyroe=VALUES(tlyroe), aroeg=VALUES(aroeg), lqroe=VALUES(lqroe), slqroe=VALUES(slqroe), tlqroe=VALUES(tlqroe),
				flqroe=VALUES(flqroe), t4qaroe=VALUES(t4qaroe), tlroer=VALUES(tlroer), afi=VALUES(afi), fe=VALUES(fe), lper=VALUES(lper),
				aper=VALUES(aper), lpbr=VALUES(lpbr), apbr=VALUES(apbr), gtlp=VALUES(gtlp), lpgc=VALUES(lpgc), gtap=VALUES(gtap), apgc=VALUES(apgc),
				pc=VALUES(pc), anios=VALUES(anios), fp=VALUES(fp), fptm=VALUES(fptm), prcv=VALUES(prcv), iv=VALUES(iv), cpivr=VALUES(cpivr),
				cpfptmr=VALUES(cpfptmr), pow=VALUES(pow), advice=VALUES(advice)')) {
					die('insert / update def row error
					'.json_encode($def));
				}
			}
		}
	}
?>