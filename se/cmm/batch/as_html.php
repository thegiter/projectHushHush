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
			</td><td data-acro="mc">
				<h3>
					Market Cap
				</h3>
			</td><td data-acro="bps">
				<h3>
					Book Value Per Share
				</h3>
			</td><td data-acro="so">
				<h3>
				Shares Outstanding (EOP)
			</h3>
			</td><td data-acro="ce">
				<h4>
				Current Equity
			</h4>
			</td><td data-acro="der">
				<h3>
				Debt to Equity Ratio
			</h3>
			</td><td data-acro="debt">
				<h4>
				Debt
			</h4>
			</td><td data-acro="cap">
				<h4>
				Capital
			</h4>
			</td><td data-acro="lyni">
				<h3>
				Last Year Net Income
			</h3>
			</td><td data-acro="lyie">
				<h3>
				Last Year Interest Expense
			</h3>
			</td><td data-acro="lypii">
				<h4>
				Last Year Pre-Interest Income
			</h4>
			</td><td data-acro="lyltd">
				<h3>
				Last Year Long-Term Debt
			</h3>
			</td><td data-acro="lystd">
				<h3>
				Last Year Short-Term Debt
			</h3>
			</td><td data-acro="lyd">
				<h4>
				Last Year Debt
			</h4>
			</td><td data-acro="lye">
				<h3>
				Last Year Equity
			</h3>
			</td><td data-acro="lycap">
				<h4>
				Last Year Capital
			</h4>
			</td><td data-acro="lypcr">
				<h4>
				Last Year PII to Capital Ratio
			</h4>
			</td><td data-acro="lyom">
				<h3>
				Last Year Operating Margin
			</h3>
			</td><td data-acro="slyom">
				<h3>
				Second Last Year Operating Margin
			</h3>
			</td><td data-acro="tlyom">
				<h3>
				Third Last Year Operating Margin
			</h3>
			</td><td data-acro="aomg">
				<h2>
				Average Operating Margin Growth
			</h2>
			</td><td data-acro="apcr">
				<h4>
				Adjusted PII to Capital Ratio
			</h4>
			</td><td data-acro="cpii">
				<h4>
				Current Pre-Interest Income
			</h4>
			</td><td data-acro="wacodr">
				<h3>
				Weighted Average Cost of Debt Ratio
			</h3>
			</td><td data-acro="interest">
				<h4>
				Interest
			</h4>
			</td><td data-acro="fi">
				<h4>
				Future Income
			</h4>
			</td><td data-acro="lyroe">
				<h3>
				Last Year Return on Equity
			</h3>
			</td><td data-acro="slyroe">
				<h3>
				Second Last Year Return on Equity
			</h3>
			</td><td data-acro="tlyroe">
				<h3>
				Third Last Year Return on Equity
			</h3>
			</td><td data-acro="aroeg">
				<h2>
				Average Return on Equity Growth
			</h2>
			</td><td data-acro="afi">
				<h4>
				Adjusted Future Income
			</h4>
			</td><td data-acro="fe">
				<h4>
				Future Equity
			</h4>
			</td><td data-acro="car">
				Compatitive Advantage Rating
			</td><td data-acro="lper">
				<h3>
				Latest P/E Ratio
			</h3>
			</td><td data-acro="lpbr">
				<h3>
				Latest P/B Ratio
			</h3>
			</td><td data-acro="gtlp">
				<h4>
				Graham Test of Latest Price
			</h4>
			</td><td data-acro="lpgc">
				<h4>
				Latest Price Graham Condition
			</h4>
			</td><td data-acro="aper">
				<h3>
				Average P/E Ratio of Last 3 Years
			</h3>
			</td><td data-acro="apbr">
				<h3>
				Average P/B Ratio of Last 3 Years
			</h3>
			</td><td data-acro="gtap">
				<h4>
				Graham Test of Average Price
			</h4>
			</td><td data-acro="apgc">
				<h4>
				Average Price Graham Condition
			</h4>
			</td><td data-acro="cc">
				Company Condition Still Good?
			</td><td data-acro="pc">
				<h2>
				Price Condition
			</h2>
			</td><td data-acro="anios">
				<h3>
				Average Net Issuance of Stock of Last 3 Years
			</h3>
			</td><td data-acro="fp">
				<h4>
				Future Price
			</h4>
			</td><td data-acro="fptm">
				<h4>
				Future Price in Today's Money
			</h4>
			</td><td data-acro="prcv">
				<h4>
				Price Relfecting Current Value
			</h4>
			</td><td data-acro="iv">
				<h2>
				Intrinsic Value
			</h2>
			</td><td data-acro="cpivr">
				<h2>
				Current Price vs Intrinsic Value
			</h2>
			</td><td data-acro="cpcvr">
				<h4>
					Current Price vs Current Value
				</h4>
			</td><td data-acro="pow">
				<h4>
					Probability of Winning (50% is gambling, less is lose)
				</h4>
			</td><td data-acro="advice">
				<h4>
					Rough Raw Unrefined Advice
				</h4>
			</td>
		</div>
		<div class="tkr-col">
		</div><div class="tbody">
		</div>
	</body>
</html>