<?php
	if (!defined('root')) {
		define('root', '../../../../../');
	}

	require_once root.'se/cmm/lib/chk_auth.php';

	$_POST['se'] = $_GET['se'];
	$_POST['tkr'] = $_GET['tkr'];

	//we re-siphon the tkr def
	//it then returns the json data of def obj
	$_POST['refresh'] = 'refresh';
	$_POST['ignore_lu'] = 'ignore';
	require root.'se/siphon/def/db/batch/siphon.php';

	echo '<table>
		<tr>
			<td>
				pf
			</td><td>
				'.$def->lffptm.'
			</td>
		</tr>
		<tr>
			<td>
				pc
			</td><td>
				'.$def->fptm.'
			</td>
		</tr>
		<tr>
			<td>
				bp
			</td><td>
				'.$def->bp.'
			</td>
		</tr>
		<tr>
			<td>
				cp
			</td><td>
				'.$def->cp.'
			</td>
		</tr>
		<tr>
			<td>
				glbRank
			</td><td>
				'.$def->glbRank.'
			</td>
		</tr>
		<tr>
			<td>
				prlyv
			</td><td>
				'.$def->prlyv.'
			</td>
		</tr>
	</table>';
?>
