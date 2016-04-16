<?php
	if (!defined('root')) {
		die;
	}
?>
		<div id="psd-bd-cnr">	<!-- pseudo body container -->
			<aside id="psd-menu-cnr">
				<?php
					require_once root.'shared/modules/hex_grid/hg.php';
					
					$hg = new hexGridModule;
					
					$hg->rows = 25;
					$hg->cols = 35;
					
					echo $hg;
				?>
				<div id="psd-menu-scrl-cnr">
				</div>
				<div class="sdw">
				</div>
			</aside>
			
			<section id="psd-vp-cnr" class="psd-vp-cnr"><!--pseudo viewport container-->
				<div id="psd-bg-cnr">
				</div>
				<div id="psd-ctt-scrl-cnr" class="psd-ctt-scrl-cnr">
					<div>
					</div>
				</div>

				<main id="psd-ctt-cnr" role="main">
				</main>
			</section>
			
			<aside>
			</aside>
			
			<section><!--notification pane-->
			</section>
		</div>
		<div id="shps-logo-cnr">
			<h1 id="shps-logo">
				<a><span class="opa-0 shps-logo-letter-init-size shps-logo-entry-trans">
					<div>
						S
					</div>
				</span><span class="opa-0 shps-logo-letter-init-size shps-logo-entry-trans">
					<div>
						H
					</div>
				</span><span class="opa-0 shps-logo-letter-init-size shps-logo-entry-trans">
					<div>
						P
					</div>
				</span><span class="opa-0 shps-logo-letter-init-size shps-logo-entry-trans">
					<div>
						S
					</div>
				</span></a>
			</h1>
		</div>