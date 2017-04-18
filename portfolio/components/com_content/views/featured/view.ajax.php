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

				$shpsptfl_ftd[$key]['item_link'] = str_replace('/portfolio/', '#!portfolio/#', JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid)));

				//the alt txt can be empty, which mean no anything, or cap, which mean regular caption,
				//or txt which means stand alone text, or txtImg, which mean text with image as background
				//or txtImgClip, which means text with image cliped to text.
				if (isset($images->image_intro_alt) && !empty($images->image_intro_alt)) {
					if ($images->image_intro_alt == 'cap') {
						$shpsptfl_ftd[$key]['img_cap'] = htmlspecialchars($images->image_intro_caption);
					} else {
						$shpsptfl_ftd[$key]['txt'] = true;

						if ($images->image_intro_alt == 'txtImg') {
							$shpsptfl_ftd[$key]['txt_img'] = true;
						} else if ($images->image_intro_alt == 'txtImgClip') {
							$shpsptfl_ftd[$key]['txt_imgClip'] = true;
						}
					}
				}

				if (isset($images->image_intro) && !empty($images->image_intro)) {
					$shpsptfl_ftd[$key]['img_url'] = root.'portfolio/'.htmlspecialchars($images->image_intro);
				}

				//if is txt, if cap is set, txt is cap, else txt is external link
				if (isset($shpsptfl_ftd[$key]['txt'])) {
					if (isset($images->image_intro_caption) && !empty($images->image_intro_caption)) {
						$shpsptfl_ftd[$key]['txt_html'] = $images->image_intro_caption;
					} else {
						ob_start();//output buffering
						require root.$urls->urlatext;
						$shpsptfl_ftd[$key]['txt_html'] = ob_get_clean();
					}

					//if is set pstn and wdt, else will be random
					if ($urls) {
						if ($urls->urlbtext) {
							$shpsptfl_ftd[$key]['txt_pstn'] = $urls->urlbtext;
						}

						if ($urls->urlctext) {
							$shpsptfl_ftd[$key]['txt_wdt'] = $urls->urlctext;
						}
					}
				}
			}

			$doc = JFactory::getDocument();

			$doc->shpsAjax = $shpsptfl_ftd;
		}
	}
?>
