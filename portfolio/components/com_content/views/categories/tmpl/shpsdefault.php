<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

	defined('_JEXEC') or die;
	defined('root') or die;

	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
	JHtml::_('behavior.caption');
			
	function popSmi($cats, $atcs) {	//populate sub menu items: categories and articles
		$smi = array();
		
		if (!empty($cats)) {
			//for each category
			foreach ($cats as $item) {
				$ttl = $item->title;
				
				$cat = $smi[$ttl] = new stdClass;
				
				$cat->type = 'cat';
				$cat->href = JRoute::_(ContentHelperRoute::getCategoryRoute($item->id));
				
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
		
				$query->select('*');
				$query->from('#__content');
				$query->where('catid="'.$item->id.'"');

				$db->setQuery((string)$query);

				// this check is important because the smi array key must only be created only if there are smi
				//(php 5.3 (used by web hosts) does not support function returning result as an argument for empty,) no longer true, is now upgraded
				//so we load result into variable first, this makes shorter code too (this part is still true though)
				$subCats = $item->getChildren();
				$dbAtcs = $db->loadObjectList();
				
				if (!empty($subCats) || !empty($dbAtcs)) {
					$cat->smi = popSmi($subCats, $dbAtcs);
				}
			}
		}
		
		if (!empty($atcs)) {
			//for each article
			foreach($atcs as $atc){
				$atc_ttl = $atc->title;
				$atc->slug = $atc->alias ? ($atc->id.':'.$atc->alias) : $atc->id;
				
				$atc_sc = $smi[$atc_ttl] = new stdClass;
				
				$atc_sc->type = 'ctt';
				$atc_sc->href = JRoute::_(ContentHelperRoute::getArticleRoute($atc->slug, $atc->catid));
				//$smi[$ttl]['pages'][$atc_ttl]['intro'] = $article->introtext;
			}
		}
		
		return $smi;	//sub menu items
	}
	
	if (count($this->items[$this->parent->id]) > 0) {
		$doc = JFactory::getDocument();
			
		$doc->shpsAjax = popSmi($this->items[$this->parent->id], null);
	}
?>