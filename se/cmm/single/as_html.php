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
			<tr>
				<td>
					<h3>
						Market Cap
					</h3>
				</td><td id="mc-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Book Value Per Share
					</h3>
				</td><td id="bps-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Shares Outstanding (EOP)
					</h3>
				</td><td id="so-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Current Equity
					</h4>
				</td><td id="ce-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Debt to Equity Ratio
					</h3>
				</td><td id="der-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Debt
					</h4>
				</td><td id="debt-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Capital
					</h4>
				</td><td id="cap-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Last Year Net Income
					</h3>
				</td><td id="lyni-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Last Year Interest Expense
					</h3>
				</td><td id="lyie-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Last Year Pre-Interest Income
					</h4>
				</td><td id="lypii-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Last Year Long-Term Debt
					</h3>
				</td><td id="lyltd-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Last Year Short-Term Debt
					</h3>
				</td><td id="lystd-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Last Year Debt
					</h4>
				</td><td id="lyd-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Last Year Equity
					</h3>
				</td><td id="lye-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Last Year Capital
					</h4>
				</td><td id="lycap-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Last Year PII to Capital Ratio
					</h4>
				</td><td id="lypcr-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Last Year Operating Margin
					</h3>
				</td><td id="lyom-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Second Last Year Operating Margin
					</h3>
				</td><td id="slyom-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Third Last Year Operating Margin
					</h3>
				</td><td id="tlyom-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h2>
						Average Operating Margin Growth
					</h2>
				</td><td id="aomg-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Adjusted PII to Capital Ratio
					</h4>
				</td><td id="apcr-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Current Pre-Interest Income
					</h4>
				</td><td id="cpii-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Weighted Average Cost of Debt Ratio
					</h3>
				</td><td id="wacodr-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Interest
					</h4>
				</td><td id="int-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Future Income
					</h4>
				</td><td id="fi-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Last Year Return on Equity
					</h3>
				</td><td id="lyroe-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Second Last Year Return on Equity
					</h3>
				</td><td id="slyroe-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Third Last Year Return on Equity
					</h3>
				</td><td id="tlyroe-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h2>
						Average Return on Equity Growth
					</h2>
				</td><td id="aroeg-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Adjusted Future Income
					</h4>
				</td><td id="afi-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Future Equity
					</h4>
				</td><td id="fe-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Latest P/E Ratio
					</h3>
				</td><td id="lper-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Latest P/B Ratio
					</h3>
				</td><td id="lpbr-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Graham Test of Latest Price
					</h4>
				</td><td id="gtlp-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Latest Price Graham Condition
					</h4>
				</td><td id="lpgc-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Average P/E Ratio of Last 3 Years
					</h3>
				</td><td id="aper-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Average P/B Ratio of Last 3 Years
					</h3>
				</td><td id="apbr-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Graham Test of Average Price
					</h4>
				</td><td id="gtap-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Average Price Graham Condition
					</h4>
				</td><td id="apgc-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h2>
						Price Condition
					</h2>
				</td><td id="pc-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h3>
						Average Net Issuance of Stock of Last 3 Years
					</h3>
				</td><td id="anios-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Future Price
					</h4>
				</td><td id="fp-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Future Price in Today's Money
					</h4>
				</td><td id="fptm-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Price Relfecting Current Value
					</h4>
				</td><td id="prcv-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h2>
						Intrinsic Value
					</h2>
				</td><td id="iv-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h2>
						Current Price vs Intrinsic Value
					</h2>
				</td><td id="cpivr-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Current Price vs Current Value
					</h4>
				</td><td id="cpcvr-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Probability of Winning
					</h4>
				</td><td id="pow-cnr">
				</td>
			</tr>
			<tr>
				<td>
					<h4>
						Raw Rough Unrefined Advice
					</h4>
				</td><td id="advice-cnr">
				</td>
			</tr>
		</table>
	</body>
</html>