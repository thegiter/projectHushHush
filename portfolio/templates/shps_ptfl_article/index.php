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

if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}
else
{
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
	
	//require_once root.'shared/phps/dtcs/ied.php';
	//require(root.'shared/phps/dtcs/ffd.php');
	//require_once root.'shared/phps/dtcs/wkd.php';
	//require_once root.'shared/phps/dtcs/jsd.php';	//put brs dtcs before this
	//require(root.'shared/phps/dtcs/fpd.php');
	
	$dtc_browsers = array('ie', 'gc');

	require_once root.'shared/dtc/dtc.php';
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<?php
		$ptfl_isHome = false;
		
		if ($this->getTitle() == "Home") {
			$this->setTitle($sitename);

			$ptfl_isHome = true;
		}
		else {
			$this->setTitle($this->getTitle().' | '.$sitename);
		}
		
		$ptfl_doc = JFactory::getDocument();
		
		//$ptfl_doc->setMimeEncoding('application/xhtml+xml');

		$ptfl_doc->addStyleSheet(root.'shared/csss/default.css');
		$ptfl_doc->addStyleSheet(root.'portfolio/templates/shps_ptfl_article/csss/ptfl.css');
		$ptfl_doc->addStyleSheet(root.'shared/footer/csss/footer.css');
		$ptfl_doc->addStyleSheet(root.'shared/csss/beta/init_stg.css');
		$ptfl_doc->addStyleSheet(root.'shared/csss/beta/beta.css');
	?>
	
	<jdoc:include type="head" />
	
	<?php
	// Use of Google Font
	if ($this->params->get('googleFont'))
	{
	?>
		<link href='//fonts.googleapis.com/css?family=<?php echo $this->params->get('googleFontName');?>' rel='stylesheet' type='text/css' />
		<style type="text/css">
			h1,h2,h3,h4,h5,h6,.site-title{
				font-family: '<?php echo str_replace('+', ' ', $this->params->get('googleFontName'));?>', sans-serif;
			}
		</style>
	<?php
	}
	?>
	<?php
	// Template color
	if ($this->params->get('templateColor'))
	{
	?>
	<style type="text/css">
		body.site
		{
			border-top: 3px solid <?php echo $this->params->get('templateColor');?>;
			background-color: <?php echo $this->params->get('templateBackgroundColor');?>
		}
		.navbar-inner, .nav-list > .active > a, .nav-list > .active > a:hover, .dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover, .nav-pills > .active > a, .nav-pills > .active > a:hover,
		.btn-primary
		{
			background: <?php echo $this->params->get('templateColor');?>;
		}
		.navbar-inner
		{
			-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
			box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2);
		}
	</style>
	<?php
	}
	?>
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

	<body class="site <?php echo $option
		. ' view-' . $view
		. ($layout ? ' layout-' . $layout : ' no-layout')
		. ($task ? ' task-' . $task : ' no-task')
		. ($itemid ? ' itemid-' . $itemid : '')
		. ($params->get('fluidContainer') ? ' fluid' : '');
	?>">

		<!-- Body -->
		<div id="ptfl-article-bd-wpr" class="bd-wpr-ptfl-article">
			<!-- Header -->
			
			<!-- Begin Content -->
			<jdoc:include type="modules" name="position-3" style="xhtml" />
			<jdoc:include type="message" />
			<jdoc:include type="component" />
			<?php
				if (!empty($ptfl_doc->pageNav)) {
					echo $ptfl_doc->pageNav;
				}
			?>
			
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
			
				require root.'shared/footer/footer.php';
			?>
			
		</div>
		<?php
			if (!empty($ptfl_doc->tools)) {
				echo $ptfl_doc->tools;
			}
			
			require root.'shared/phps/beta.php';
		?>
		
		<jdoc:include type="modules" name="debug" style="none" />
	</body>
</html>
