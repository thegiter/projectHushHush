<?php
	header('Access-Control-Allow-Origin: http://'.preg_replace('/ses\d+\./', '', $_SERVER['SERVER_NAME']));

	if (!defined('root')) {
		define('root', '../../../../../');
	}

	require_once root.'se/cmm/lib/chk_auth.php';

	const GLB_RANK_MAX = 4000;

	$se = $_POST['se'];
	$tkr = $_POST['tkr'];
	$refresh = (isset($_POST['refresh']) && ($_POST['refresh'] == 'refresh')) ? true : false;
	$ignore_lu = (isset($_POST['ignore_lu']) && ($_POST['ignore_lu'] == 'ignore')) ? true : false;
	$car = 1;
	$cc = 1;
	$glbrank = 1;
	$tbl_name = strtolower($se).'_defs';
	$old_advice;
	$def;

	require_once root.'se/cmm/lib/db.php';

	//here, we assume that the table already exist
	//but we don't know if the data for the ticker exist or not
	//if ticker exist and has any of the car or cc values, we will use those from the db for the siphon
	//else if no data for these fields, then we assume no user specified data, and we will use the default ones
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	if ($mysqli->connect_error) {
		die('Connect Error ('.$mysqli->connect_errno.')'.$mysqli->connect_error);
	} else {//then select db
		//get all tickers from the se table
		if ($db_def = $mysqli->query("
			SELECT
				lu
			FROM
				".$tbl_name."
			WHERE
				tkr = '".$tkr."'
		")) {
			$db_def->data_seek(0);

			$db_tkr_def = $db_def->fetch_assoc();

			//check if last update is less than 5 days
			if (!$ignore_lu && $db_tkr_def['lu']) {
				$lu = new DateTime($db_tkr_def['lu']);
				$now = new DateTime('now');

				if ($lu->diff($now)->days < 4) {
					//return db data and skip siphon
					if ($db_def = $mysqli->query("
						SELECT
							*
						FROM
							".$tbl_name."
						WHERE
							tkr = '".$tkr."'
					")) {
						$db_def->data_seek(0);

						$db_tkr_def = $db_def->fetch_assoc();

						$def = new stdClass;

						foreach ($db_tkr_def as $ttl => $value) {
							if (($ttl == 'tkr') || is_numeric($ttl)) {
								continue;
							}

							$def->{$ttl} = $value;
						}

						die(json_encode($def));
					}
				}
			}

			//else if more than 5 days
			//fetch car cc, etc if exist and continue to siphon
			if ($db_def = $mysqli->query("
				SELECT
					car
					, cc
					, advice
				FROM
					".$tbl_name."
				WHERE
					tkr = '".$tkr."'
			")) {
				$db_def->data_seek(0);

				$db_tkr_def = $db_def->fetch_assoc();

				if ($db_tkr_def['car']) {
					$car = $db_tkr_def['car'];
				}

				if ($db_tkr_def['cc']) {
					$cc = $db_tkr_def['cc'];
				}

				if ($db_tkr_def['advice']) {
					$old_advice = $db_tkr_def['advice'];
				}
			}

			//fetch global rank, if found, check last update, must be less than a year
			//if over a year, remove global rank, else, assign to var
			if ($db_def = $mysqli->query("
				SELECT
					glbrank
					, glbranklu
				FROM
					".strtolower($se)."_vars
				WHERE
					tkr = '".$tkr."'
			")) {
				$db_def->data_seek(0);

				$db_tkr_def = $db_def->fetch_assoc();

				//check if last update is more than a year
				if ($db_tkr_def['glbranklu']) {
					$lu = new DateTime($db_tkr_def['glbranklu']);
					$now = new DateTime('now');

					$gr = $db_tkr_def['glbrank'];

					if ($lu->diff($now)->y >= 1) {//if over a year
						//remove glbrank
						$mysqli->query("
							DELETE
							FROM
								".strtolower($se)."_vars
							WHERE
								tkr = '".$tkr."'
						");
					} else if ($gr > GLB_RANK_MAX) {//if over max
						$glbrank = GLB_RANK_MAX;
					} else {
						$glbrank = $gr;
					}
				}
			}
		}
	}

	require_once root.'se/siphon/cmm/lib/analyze_data.php';

	$def = seAnalyze::getStockDef($se.':'.$tkr, $car, $cc, $glbrank, $refresh);

	//upon receiving of the siphoned data, check if siphon successful,
	//if failed, we will respond with failure msg instead of retrying.
	//because retrying could result in php running longer than allowed seconds
	//this has the byproduct of informing the client side of failures, client side can then initiate a retry
	if (is_string($def)) {
		$err = new stdClass;

		$err->err = true;
		$err->err_msg = $def;

		die(json_encode($err));
	}

	//if succes, we will update db, but not the user input values, the user input values must be changed in use_db
	//if store db failed, the script dies automatically
	//if store db succeeded, we will respond with the json encoded def
	require_once root.'se/cmm/lib/update_tbl_row.php';

	se_update_tbl_row_siphoned($tbl_name, $tkr, $def);

	//we check if advice changed to sell or buy, and update to advice table
	if ((($def->advice == 'buy') || ($def->advice == 'sell') || ($def->advice == 'be ready to sell')) && (!isset($old_advice) || ($def->advice != $old_advice))) {
		if (!isset($old_advice)) {
			$old_advice = '';
		}

		//update tbl
		if (!$mysqli->query("
			INSERT INTO
				advice_updates(tkr, old_advice, new_advice)
				VALUES('".$tkr."', '".$old_advice."', '".$def->advice."')
				ON DUPLICATE KEY UPDATE
					old_advice = VALUES(old_advice)
					, new_advice = VALUES(new_advice)
		")) {
			die('insert / update advice updates tbl error');
		}
	}

	echo json_encode($def);
?>
