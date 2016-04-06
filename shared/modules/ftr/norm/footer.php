<?php
	defined('root') or die;
?>
		<footer>
			<div class="pos-rel">
				<?php
					require root.'shared/phps/hr.php';
				?>
							
			</div>
			<div class="lv2-ftr-ctnr">
				<?php
					//show content before the level 2 footer copyright
					if ($ftr_bfr) {
						require root.$ftr_bfr;
					};
				?>

				<div class="ta-ctr">
					<?php
						require root.'shared/footer/sml/footer.php';
					?>
								
				</div>
				<?php
					// show content after the level 2 footer copyright
					if ($ftr_aftr) {
						require root.$ftr_aftr;
					}; 
				?>
			</div>
		</footer>