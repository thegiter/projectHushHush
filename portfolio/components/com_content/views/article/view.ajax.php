<?php
	defined('_JEXEC') or die;
	defined('root') or die;
	
	class ContentViewArticle extends JViewLegacy {
		protected $item;
		protected $state;
		protected $params;
		
		public function display($tpl = null) {
			$item = $this->item = $this->get('Item');
			
			$item->slug	= $item->alias ? ($item->id.':'.$item->alias) : $item->id;	// slug is required by pagebreak plugin
			
			$this->state  = $this->get('State');
			$this->params = $this->state->get('params');
			
			if ($item->params->get('show_intro', '1') == '1')
			{
				$item->text = $item->introtext . ' ' . $item->fulltext;
			}
			elseif ($item->fulltext)
			{
				$item->text = $item->fulltext;
			}
			else
			{
				$item->text = $item->introtext;
			}
			
			$dispatcher	= JEventDispatcher::getInstance();
			$offset = $this->state->get('list.offset');
			
			//process content plugins, main purpose: content - pagebreak plugin
			JPluginHelper::importPlugin('content');
			$dispatcher->trigger('onContentPrepare', array ('com_content.article', &$item, &$this->params, $offset));
			
			require_once root.'shared/read_bg/read_bg.php';
		
			$article_read = new readBgModule;
			
			$article_read->innerTag = 'article';
			$article_read->innerOtherClss = 'ptfl-article-ctt article';

			$article_read->content = $item->text;
			
			echo $article_read->cttHtml().'<!--shps separator-->'.$item->pageNav;
		}
	}
?>