<?php
	defined('root') or die;
	
	$ptfl_doc = JFactory::getDocument();

	
	$ptfl_doc->addScript(root.'shared/jss/common.js');		//added every time because order is important
	$ptfl_doc->addScript(root.'shared/jss/anim.js');		//anim is required for ldg.js and beta.js
	$ptfl_doc->addScript(root.'shared/jss/bsc_twn.js');		//
	$ptfl_doc->addScript(root.'shared/jss/cnr_bg/dft_anim.js');
	$ptfl_doc->addScript(root.'portfolio/components/com_content/views/article/tmpl/jss/default.js');
	$ptfl_doc->addScript(root.'shared/jss/beta.js');		//these jss are order-sensitive
	$ptfl_doc->addScript(root.'shared/jss/wrt_bg.js');
	$ptfl_doc->addScript(root.'shared/jss/gaa.js');			//must be last
	
/*	foreach ($ptflAtc_jss as $url) {
		$ptfl_doc->addScript(root.$url);
	}*/
	
	$ptfl_doc->addStyleSheet(root.'portfolio/components/com_content/views/article/tmpl/csss/default.css');
	$ptfl_doc->addStyleSheet(root.'shared/logo/sunken/csss/logo.css');

	/*foreach ($ptflAtc_csss as $url) {
		$ptfl_doc->addStyleSheet(root.$url);
	}*/
	
// If the page class is defined, add to class as suffix.
// It will be a separate class if the user starts it with a space
	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
	
	$params  = $this->item->params;
	$images  = json_decode($this->item->images);
	
	JHtml::_('behavior.caption');
?>
<div class="ptfl-article-para-bg" style="background-color:<?php
	$color_code = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
			
	$color = '#';
		
	for ($i=1; $i<=6; $i++) {
		$color .= $color_code[rand(0,15)];
	}
	
	echo $color;
?>">
	<div class="post-proc">	<!-- post processing -->
		<div class="ptfl-article-bg-img"<?php
			if (isset($images->image_fulltext) && !empty($images->image_fulltext)) {
				echo ' style="background-image:url('.htmlspecialchars($images->image_fulltext).');';
				
				if ($images->image_fulltext_alt && ($images->image_fulltext_alt == 'scale')) {
					echo 'background-size:contain;';
				}

				if (($images->image_fulltext_caption) && ($images->image_fulltext_caption = ('top' || 'center' || 'bottom'))) {
					$bg_pstn = htmlspecialchars($images->image_fulltext_caption);
				}
				else {
					$bg_pstn = 'center';
				}
					
				echo 'background-position:'.$bg_pstn.'"';
			}
		?>>
		</div>
	</div>	
</div>
<div class="ptfl-article-bd-wpr">
	<header id="ptfl-artic-hdr" class="ptfl-splash-bg-cnr">
		<div class="ptfl-article-logo-cnr">
			<?php
				require root.'shared/logo/sunken/logo.php';
			?>
		
		</div>
			
		<section id="ptfl-article-header-info-cnr" class="ptfl-article-header-info-cnr" title="Header Info">
			<?php if ($params->get('show_category')) : ?>
				<div id="ptfl-article-header-cat" class="category-name opa-0" style="color:<?php
					echo $color;
				?>">
					<?php
						$title = $this->escape($this->item->category_title);
						
						if ($params->get('link_category') && $this->item->catslug) {
							echo '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '" itemprop="genre" title="Go to the '.$title.' category page.">' . $title . '</a>';
						}
						else{
							echo '<span itemprop="genre">' . $title . '</span>';
						}
					?>
				</div>
			<?php endif; ?>
					
				
			<h2 id="ptfl-article-title" class="trans-init" itemprop="name">
				<?php
					if ($params->get('show_title')) {
						echo $this->escape($this->item->title);
					}
				?>
				
			</h2>
			
			<section id="ptfl-article-header-publish" title="Publish Info" class="opa-0">
				<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
					<div class="createdby" itemprop="author" itemscope itemtype="http://schema.org/Person">
						<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
						<?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
						<?php if (!empty($this->item->contact_link) && $params->get('link_author') == true) : ?>
							<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', $this->item->contact_link, $author, array('itemprop' => 'url'))); ?>
						<?php else: ?>
							<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				
				<?php if ($params->get('show_publish_date')) : ?>
					<div class="published">
						<span class="icon-calendar"></span>
						<time datetime="<?php echo JHtml::_('date', $this->item->publish_up, 'c'); ?>" itemprop="datePublished">
							<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
						</time>
					</div>
				<?php endif; ?>
				
				<?php if ($params->get('show_modify_date')) : ?>
					<div class="modified">
						<span class="icon-calendar"></span>
						<time datetime="<?php echo JHtml::_('date', $this->item->modified, 'c'); ?>" itemprop="dateModified">
							<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
						</time>
					</div>
				<?php endif; ?>
			</section>
		</section>
		
		<div class="ptfl-artic-show-btn-cnr">
			<div id="ptfl-artic-show-btn" class="clickable fade-in-norm dsp-non opa-0" title="Scroll up the article.">
			</div>
		</div>
	</header>
	<?php
		if (preg_match('/<\!--shps (.+) ptfl-->/', $this->item->text, $include_code)) {
			$include_urls = explode(';', $include_code[1]);
				
			foreach ($include_urls as $url) {
				if (preg_match('/\.(js|css)$/', $url, $include_extension)) {
					if ($include_extension[1] == 'js') {
						$ptfl_doc->addScript(root.$url);
					}
					else {
						$ptfl_doc->addStyleSheet(root.$url);
					}
				}
			}
		}
			
		require root.'shared/read_bg/read_bg.php';
		
		$article_read = new readBgModule;
		
		$article_read->outterTag = 'main';
		$article_read->outterOtherClss = 'pos-rel pb-ctt-cnr';
		
		$article_read->innerTag = 'article';
		$article_read->innerOtherClss = 'ptfl-article-ctt article';

		$article_read->cttWprId = 'ptfl-article-pg-1';
		$article_read->cttWprClss = 'pb-ctt-wpr';
		
		
		$article_read->content = $this->item->text;
		
		echo $article_read;
	?>

</div>
<?php
	require root.'shared/tools/tools.php';
	
	$ptflArticle_tools = new toolsModule;
	
	if (isset($this->item->toc)) {	//toc: table of content
		$ptflArticle_tools->before = $this->item->toc;
	}
	
	$ptfl_doc->tools = $ptflArticle_tools;
	
	if (isset($this->item->pageNav)) {
		$ptfl_doc->pageNav = $this->item->pageNav;
	}
?>