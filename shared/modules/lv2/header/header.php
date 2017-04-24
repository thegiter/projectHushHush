<?php
	defined('root') or die;
?>
		<header>
<?php


	echo "\n\t\t\t";

	require root.'shared/lv2/menu/menu.php';

	echo "\n\t\t\t";

	require root.'shared/phps/bc.php';
?>

			<h2 class="lv2-ttl-cnr img-cnr pos-rel">
				<img id="<?php
					$lv2_ttl = array('news' => 'news', 'portfolio' => 'ptfl', 'about' => 'abt');

					echo $lv2_ttl[$thisPage].'-ttl';
				?>" class="lv2-ttl" alt="<?php
					echo ucwords($thisPage);
				?>" src="/shared/imgs/tsp.gif" />
			</h2>
		</header>
