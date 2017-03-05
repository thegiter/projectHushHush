<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

	defined('_JEXEC') or die;
	defined('root') or die;

	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

	JHtml::_('behavior.caption');

	$jml_menu = JFactory::getApplication()->getMenu();

	if ($jml_menu->getActive() == $jml_menu->getDefault()) {
		require root.'portfolio/components/com_content/views/featured/tmpl/shpsdefault.php';
	} else {
		require root.'portfolio/components/com_content/views/featured/tmpl/jdefault.php';
	}
// If the page class is defined, add to class as suffix.
// It will be a separate class if the user starts it with a space
?>