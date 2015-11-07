<?php
	defined('root') or die;
?>
		<title>CNY Stock Evaluator</title>
	</head>
	
	<body>
		<h1>
			CNY Stock Evaluator
		</h1>
		
		<form method="post" id="ticker-form">
			<label class="ipt-lbl" for="ticker-ipt">Enter Gurufocus Ticker:</label><input id="ticker-ipt" type="text" name="ticker" required="required"/><br/>
			<label class="ipt-lbl" for="car-ipt">Enter Competitive Advantage Rating (1 means neutral):</label><input id="car-ipt" type="number" step="any" name="car" value="1" required="required"/><br/>
			<label class="ipt-lbl">Company Condition Still Positive?</label><input id="cc-ipt-true" name="cc" type="radio" value="1" checked="checked"/><label for="cc-ipt-true">True</label><input name="cc" id="cc-ipt-false" type="radio" value="0"/><label for="cc-ipt-false">False</label><br/>
			<button id="run-btn" type="submit">Submit</button> CNY Inflation Rate: <span id="ir-cnr"></span>
		</form>
		
		<div id="msg-cnr">
		</div>
		
		<table>
			<?php
				require_once root.'se/cmm/lib/headers.php';
				
				foreach ($se_headers as $header_cell) {
					echo '<tr>
						'.$header_cell.'<td>
						</td>
					</tr>
					';
				}
			?>
		</table>
	</body>
</html>