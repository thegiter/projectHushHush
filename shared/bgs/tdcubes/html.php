<?php
	if (!defined('root')) {
		die;
	}
?>
<div id="bg-tdcubes" class="bg-tdcubes fade-in-norm opa-0">
	<?php
		require_once root.'shared/td_cube/td_cube.php';
		
		$cube = new tdCubeModule;

		$cube->front = true;
		$cube->rgt = true;
		$cube->lft = true;
		$cube->top = true;
		$cube->btm = true;

		echo $cube;
	?>
</div>