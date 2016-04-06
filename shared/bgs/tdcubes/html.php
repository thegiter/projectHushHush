<?php
	if (!defined('root')) {
		die;
	}
?>
<div id="bg-tdcubes" class="bg-tdcubes dsp-non">
	<?php
		//a tiny little performance optimization
		//is that we only populate 1 cube html
		//and then we clone however many we want using js
		//this means, if js is disabled, one 1 cube html is transfered over network
		//if js is not disabled, we can populate as many cubes as we want using the same amount of js code
		//this minimizes data transfer over the network
		//it is a very little optimization which does NOT make a big difference, but is still nice to have
		require_once root.'shared/td_cube/td_cube.php';
		
		//by default, cube is a div, with no id
		$cube = new tdCubeModule;

		$cube->front = true;
		$cube->rgt = true;
		$cube->lft = true;
		$cube->top = true;
		$cube->btm = true;

		$cube->depth = 0;
		
		$cube->clss = 'vsb-hid opa-0';
		
		echo $cube;
	?>
</div>