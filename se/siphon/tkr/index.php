<?php
	if (!defined('root')) {
		define('root', '../../../');
	}
	
	require root.'shared/phps/dtdec_x.php';
	
	echo "\n";
	
	require root.'shared/phps/heads.php';
?>
		<script src="/shared/jss/common.js" type="text/javascript"></script>
		
		<link href="/se/siphon/tkr/csss/st.css" rel="stylesheet" type="text/css" charset="utf-8"/>
		<link href="/se/cmm/single/csss/single.css" rel="stylesheet" type="text/css" charset="utf-8"/>
		
		<script src="/se/siphon/tkr/jss/st.js" async="async" type="text/javascript"></script>
		
		<title>CNY Stock Evaluator</title>
	</head>
	
	<body>
		<h1>
			CNY Stock Ticker List Siphoner
		</h1>
		
		<form id="se-form" method="post">
			<label>Choose Stock Exchange:</label><input id="se-ipt-shse" type="radio" value="SHSE" name="se"/><label for="se-ipt-shse">SHSE</label><input id="se-ipt-szse" type="radio" value="SZSE" name="se"/><label for="se-ipt-szse">SZSE</label><input id="se-ipt-jse" type="radio" value="JSE" name="se"/><label for="se-ipt-jse">JSE</label><label for="se-ipt-szse">SZSE</label><input id="se-ipt-nyse" type="radio" value="NYSE" name="se"/><label for="se-ipt-nyse">NYSE</label><input id="se-ipt-nas" type="radio" value="Nasdaq" name="se"/><label for="se-ipt-nas">Nasdaq</label><br/>
			<label for="cursor-ipt">Choose Stock List Cursor (start from which stock):</label><input id="cursor-ipt" name="cursor" value="1" type="number"/><br/>
			<button type="submit">Load Page</button>
		</form>
		
		<iframe id="se-iframe">
		</iframe>
		
		<button id="sp-btn">Siphon Page</button>
		
		<p id="msg-cnr">
		</p>
	</body>
</html>