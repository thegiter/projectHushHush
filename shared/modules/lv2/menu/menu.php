<?php
	defined('root') or die;
?><nav class="menu-cnr">
				<div id="mi-bg-cnr" class="pos-abs">			<!-- mi stands for menu item -->
					<?php
						require root.'shared/phps/wrt_bg.php';
					?>
				</div>
			
				<ul class="no-style no-mrg-top no-mrg-btm" id="menu-lst">
					<?php
						require_once root.'shared/mis/mis.php';
						
						$menu_html = '';								//clear variable $menu_html in case another menu was produced and $menu_html is not empty
						
						foreach ($mis as $mi => $mi_ppt) {												//ppt: properties, not the microsoft shit
							$menu_html .= '<li';
							
							if ($mi == $thisPage) {
								$menu_html .= ' class="shadowed fnt-wht" id="menu-crt-itm">'.$mi_ppt['name'].'</li>';
							}
							else {
								$menu_html .= '><a href="'.$mi_ppt['href'].'">'.$mi_ppt['name'].'</a></li>';
							}
						}
						
						echo $menu_html;
					?>

				</ul>
			</nav>