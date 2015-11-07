<?php
	defined('root') or die;
?>
		<title>Stock Definition Batch Siphoner</title>
	</head>
	
	<body>
		<h1>
			CNY Stock Definition Batch Siphoner
		</h1>
		
		<form id="se-form" method="post">
			<label>Choose Stock Exchange:</label><input id="se-ipt-shse" type="radio" value="SHSE" name="se" required="required"/><label for="se-ipt-shse">SHSE</label><input id="se-ipt-szse" type="radio" value="SZSE" name="se"/><label for="se-ipt-szse">SZSE</label><br/>
			<button type="submit">Load Stock List</button>
		</form>
		
		<button disabled="disabled" id="ss-btn">Start Siphon</button>
		
		<p id="msg-cnr">
			<span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><!--for threads-->
		</p>
		
		<div class="thead">
			<td>
				Ticker
			</td><td>
				Name
			</td><?php
				require_once root.'se/cmm/lib/headers.php';
				
				foreach ($se_headers as $header_cell) {
					echo $header_cell;
				}
			?>
		</div>
		<div class="tkr-col">
		</div><div class="tbody">
		</div>
	</body>
</html>