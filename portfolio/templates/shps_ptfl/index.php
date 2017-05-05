<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

	defined('_JEXEC') or die;

	if (!defined('root')) {
		die;
	}

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

if($task == "edit" || $layout == "form" ) {
	$fullWidth = 1;
} else {
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
$doc->addScript('templates/' .$this->template. '/js/template.js');

// Add Stylesheets
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Add current user information
$user = JFactory::getUser();

// Adjusting content width
if ($this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span6";
}
elseif ($this->countModules('position-7') && !$this->countModules('position-8'))
{
	$span = "span9";
}
elseif (!$this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span9";
}
else
{
	$span = "span12";
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="'. JUri::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="'. $sitename .'">'. htmlspecialchars($this->params->get('sitetitle')) .'</span>';
}
else
{
	$logo = '<span class="site-title" title="'. $sitename .'">'. $sitename .'</span>';
}

	$r =& $doc->shpsAjax->rscs;

	$rGrpCnt = count($r);

//first grp
	$r[$rGrpCnt] = [];
	$r1 =& $r[$rGrpCnt];

	$r1[0] = new stdClass;
	$r1[0]->type = 'link';
	$r1[0]->url = '/portfolio/templates/shps_ptfl/csss/ftr.css';
//sec grp
	$r[$rGrpCnt + 1] = [];
	$r2 =& $r[$rGrpCnt + 1];

	$r2[0] = new stdClass;
	$r2[0]->type = 'link';
	$r2[0]->url = '/portfolio/templates/shps_ptfl/csss/ptfl.css';
//3rd grp
	$r[$rGrpCnt + 2] = [];
	$r3 =& $r[$rGrpCnt + 2];

	$r3[0] = new stdClass;
	$r3[0]->type = 'script';
	$r3[0]->url = '/portfolio/templates/shps_ptfl/jss/shpsptfl_tmpl.js';
	//define $r[][]->async = false; to turn off async loading for the script

	$ptfl_isHome = false;

	if ($this->getTitle() == "Home") {
		$this->setTitle($sitename);

		$ptfl_isHome = true;
	} else {
		$this->setTitle($this->getTitle().' | '.$sitename);
	}
?>
<div id="ptfl-bd" class="bd-wpr td-cnr psv-3d fade-in-norm opa-0 dsp-non">
	<div id="ptfl-bd-hgt-wpr" class="psv-3d">
		<!-- Begin Content -->
		<jdoc:include type="modules" name="position-3" style="xhtml" />
		<jdoc:include type="message" />
		<jdoc:include type="component" />
		<jdoc:include type="modules" name="position-2" style="none" />
		<!-- End Content -->
		<div class="header-search pull-right">
			<jdoc:include type="modules" name="position-0" style="none" />
		</div>
		<?php if ($this->countModules('position-1')) : ?>
		<nav class="navigation" role="navigation">
			<jdoc:include type="modules" name="position-1" style="none" />
		</nav>
		<?php endif; ?>
		<jdoc:include type="modules" name="banner" style="xhtml" />
		<div class="row-fluid">
			<?php if ($this->countModules('position-8')) : ?>
			<!-- Begin Sidebar -->
			<div id="sidebar" class="span3">
				<div class="sidebar-nav">
					<jdoc:include type="modules" name="position-8" style="xhtml" />
				</div>
			</div>
			<!-- End Sidebar -->
			<?php endif; ?>
			<?php if ($this->countModules('position-7')) : ?>
				<div id="aside" class="span3">
					<!-- Begin Right Sidebar -->
					<jdoc:include type="modules" name="position-7" style="well" />
					<!-- End Right Sidebar -->
				</div>
			<?php endif; ?>
		</div>
		<!-- Footer -->
		<?php
			$ftr_bfr = 'portfolio/templates/shps_ptfl/tplt/ftr/ftr.php';
			$ftr_aftr = '';
		?>
	</div>
</div>

<jdoc:include type="modules" name="debug" style="none" />
