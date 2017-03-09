<?php
	defined('root') or die;
?>
<div class="clr-dbl-aft ptfl-ftr-cnr">
					<jdoc:include type="modules" name="footer" style="none" />
					
					<div class="site-info">
						<?php
							$ptfl_app = JFactory::getApplication();
							$ptfl_siteName = $ptfl_app->getCfg('sitename');
							
							if (!$ptfl_isHome) {
								$ptfl_url = JURI::root();
								
								echo '<a href="'.$ptfl_url.'" title="Go to the portfolio section\'s homepage." rel="home">'.$ptfl_siteName.'</a>';
							} else {
								echo $ptfl_siteName;
							};
						?>
						
					</div><!-- #site-info --><div class="site-generator">
						<?php
							function jCredit_colorfulLetters($string) {
								$letters = str_split($string);
								$cntr = 0;
								$colors_class = ['red', 'blue', 'orange', 'green'];	//must be in this order
								$html = '';
								
								foreach ($letters as $letter) {
									if ($letter != ' ') {
										$color = $colors_class[$cntr];
										
										if ($cntr == 3) {
											$cntr = 0;
										}
										else {
											$cntr++;
										}
										
										$html .= '<span class="'.$color.'">'.$letter.'</span>';
									}
									else {
										$html .= $letter;
									}
								}
								
								return $html;
							}
							
							echo jCredit_colorfulLetters('Proudly powered by');
							
						?> <a href="http://www.joomla.org" title="Open the Joomla! homepage." target="_blank" class="tgt-blank" rel="generator"><?php
							echo jCredit_colorfulLetters('Joomla!');
						?></a><span class="red">.</span>
					</div><!-- #site-generator -->
				</div>