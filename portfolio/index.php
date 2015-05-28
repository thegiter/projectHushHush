<?php
/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

if (version_compare(PHP_VERSION, '5.3.10', '<'))
{
	die('Your host needs to use PHP 5.3.10 or higher to run this version of Joomla!');
}

/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
if (!defined('_JEXEC')) {
	define('_JEXEC', 1);
}

	if (!defined('root')) {
		define('root', '../');
	}
	
	require root.'shared/ajax_chk/ajax_chk.php';
	
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
	$doc->shpsAjax->csss = [];
	$doc->shpsAjax->jss = [];
	$doc->shpsAjax->cpns = [];
	
	ob_start();//output buffering of shpsAjax
		
// Execute the application.
$app->execute();

	$tmp_ob = ob_get_clean();
	
	if ($tmp_ob != '') {
		$doc->shpsAjax->html = $tmp_ob;
	}
	
	echo json_encode($doc->shpsAjax);