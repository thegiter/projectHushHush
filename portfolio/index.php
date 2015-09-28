<?php
/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

	if (!defined('root')) {
		define('root', '../');
	}
	
	//must validate first, because validate doesn't care if is get or post
	//while ajax_chk must be POST
	require_once root.'shared/ajax_chk/ajax_chk.php';

if (version_compare(PHP_VERSION, '5.3.10', '<'))
{
	die('Your host needs to use PHP 5.3.10 or higher to run this version of Joomla!');
}

	require_once root.'shared/cache_ctrl/json.php';
	
/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
if (!defined('_JEXEC')) {
	define('_JEXEC', 1);
}

if (file_exists(__DIR__ . '/defines.php'))
{
	include_once __DIR__ . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', __DIR__);
	require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';

// Mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;

// Instantiate the application.
$app = JFactory::getApplication('site');

	$doc = JFactory::getDocument();
	
	$doc->shpsAjax = new stdClass;
	$doc->shpsAjax->rscs = [];
	
	ob_start();//output buffering of shpsAjax
		
// Execute the application.
$app->execute();

	$tmp_ob = ob_get_clean();
	
	if ($tmp_ob != '') {
		$r =& $doc->shpsAjax->rscs;
		
		$rGrpCnt = count($r);
		
		$r[$rGrpCnt] = [];
		$r1 =& $r[$rGrpCnt];
		
		$r1[0] = new stdClass;
		$r1[0]->type = 'html';
		$r1[0]->html = $tmp_ob;
	}
	
	echo json_encode($doc->shpsAjax);