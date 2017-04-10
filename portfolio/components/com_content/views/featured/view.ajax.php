<?php
	defined('_JEXEC') or die;
	(strpos($_POST['refpg'], '#!portfolio/') === 0) or die;

	class ContentViewFeatured extends JViewLegacy {
		protected $item = null;
		protected $items = null;

		function display($tpl=null) {
			$items = $this->get('Items');

			foreach ($items as $key => &$item) {
				$images = json_decode($item->images);
				$urls = json_decode($item->urls);
				$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;

				if (isset($images->image_intro) && !empty($images->image_intro)) {
					$shpsptfl_ftd[$key]['img_url'] = root.'portfolio/'.htmlspecialchars($images->image_intro);
					// = htmlspecialchars($images->image_intro_alt);
					$shpsptfl_ftd[$key]['img_cap'] = htmlspecialchars($images->image_intro_caption);
				}

				$shpsptfl_ftd[$key]['item_link'] = str_replace('/portfolio/', '#!portfolio/#', JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)));

				if ($urls && !empty($urls->urlatext)) {
					$shpsptfl_ftd[$key]['txt'] = true;

					if ($urls->urlbtext) {
						$shpsptfl_ftd[$key]['txt_pstn'] = $urls->urlbtext;
					}

					if ($urls->urlctext) {
						$shpsptfl_ftd[$key]['txt_wdt'] = $urls->urlctext;
					}

					ob_start();//output buffering
					require root.$urls->urlatext;
					$shpsptfl_ftd[$key]['txt_html'] = ob_get_clean();
				}
			}

			$doc = JFactory::getDocument();

			$doc->shpsAjax = $shpsptfl_ftd;
		}
	}
?>
