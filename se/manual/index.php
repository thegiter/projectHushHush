<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	
	require root.'shared/phps/dtdec_x.php';
	
	echo "\n";
	
	require root.'shared/phps/heads.php';
?>
		
		<script src="/shared/jss/common.js" type="text/javascript"></script>
		
		<link href="/se/cmm/single/csss/single.css" rel="stylesheet" type="text/css" charset="utf-8"/>
		
		<script src="/se/manual/jss/manual.js" type="text/javascript"></script>
		
		<title>Stock Manual Evaluator</title>
	</head>
	
	<body>
		<h1>
			Stock Manual Evaluator 
		</h1>
		
		<div id="msg-cnr">
		</div>
		
		<form method="post" id="def-form">
			<table>
				<tr>
					<td>
						<label for="ir">Inflation Rate:</label>
					</td><td>
						<input id="ir" name="ir" type="text"/>
					</td>
				</tr>
					
				<?php
					require_once root.'se/cmm/lib/db_cols.php';
					
					foreach ($def_cols as $def_name => $db_value) {
						echo '<tr>
							<td>
								<lable for="'.$def_name.'">'.$def_name.'</lable>
							</td><td>
								<input id="'.$def_name.'" type="text" name="'.$def_name.'"/>
							</td>
						</tr>
						';
					}
				?>
			</table>
			
			<button id="run-btn" type="submit">Submit</button>
		</form>
	</body>
</html>