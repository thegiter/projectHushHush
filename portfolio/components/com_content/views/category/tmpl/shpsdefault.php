<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
	defined('_JEXEC') or die;

	JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
	JHtml::_('behavior.caption');

	foreach ($this->items as $idx => &$item) {
		$images = json_decode($item->images);
		$urls = json_decode($item->urls);
		$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;

		$shpsptfl_atcs[$idx]['item_link'] = str_replace('/portfolio/', '#!portfolio/#', JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)));

		//the alt txt can be empty, which mean no anything, or cap, which mean regular caption,
		//or txt which means stand alone text, or txtImg, which mean text with image as background
		//or txtImgClip, which means text with image cliped to text.
		if (isset($images->image_intro_alt) && !empty($images->image_intro_alt)) {
			if ($images->image_intro_alt == 'cap') {
				$shpsptfl_atcs[$idx]['img_cap'] = htmlspecialchars($images->image_intro_caption);
			} else {
				$shpsptfl_atcs[$idx]['txt'] = true;

				if ($images->image_intro_alt == 'txtImg') {
					$shpsptfl_atcs[$idx]['txt_img'] = true;
				} else if ($images->image_intro_alt == 'txtImgClip') {
					$shpsptfl_atcs[$idx]['txt_imgClip'] = true;
				}
			}
		}

		if (isset($images->image_intro) && !empty($images->image_intro)) {
			$shpsptfl_atcs[$idx]['img_url'] = root.'portfolio/'.htmlspecialchars($images->image_intro);
		}

		//if is txt, if cap is set, txt is cap, else txt is external link
		if (isset($shpsptfl_atcs[$idx]['txt'])) {
			if (isset($images->image_intro_caption) && !empty($images->image_intro_caption)) {
				$shpsptfl_atcs[$idx]['txt_html'] = $images->image_intro_caption;
			} else {
				ob_start();//output buffering
				require root.$urls->urlatext;
				$shpsptfl_atcs[$idx]['txt_html'] = ob_get_clean();
			}

			//if is set pstn and wdt, else will be random
			if ($urls) {
				if ($urls->urlbtext) {
					$shpsptfl_atcs[$idx]['txt_pstn'] = $urls->urlbtext;
				}

				if ($urls->urlctext) {
					$shpsptfl_atcs[$idx]['txt_wdt'] = $urls->urlctext;
				}
			}
		}
	}

	$doc = JFactory::getDocument();

	$doc->shpsAjax = $shpsptfl_atcs;
?>
