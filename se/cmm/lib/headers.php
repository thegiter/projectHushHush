<?php
	defined('root') or die;
	
	$se_headers = [
		'<td data-acro="mc">
			<h3>
				Market Cap
			</h3>
		</td>',
		'<td data-acro="bps">
			<h3>
				Book Value Per Share (EOP)
			</h3>
		</td>',
		'<td data-acro="so">
			<h3>
				Shares Outstanding (EOP)
			</h3>
		</td>',
		'<td data-acro="ce">
			<h4>
				EOP Equity
			</h4>
		</td>',
		'<td data-acro="der">
			<h3>
				Debt to Equity Ratio
			</h3>
		</td>',
		'<td data-acro="debt">
			<h4>
				EOP Debt
			</h4>
		</td>',
		'<td data-acro="cap">
			<h4>
				EOP Capital
			</h4>
		</td>',
		'<td data-acro="lyni">
			<h3>
				Last Year Net Income
			</h3>
		</td>',
		'<td data-acro="lyie">
			<h3>
				Last Year Interest Expense
			</h3>
		</td>',
		'<td data-acro="lypii">
			<h4>
				Last Year Pre-Interest Income
			</h4>
		</td>',
		'<td data-acro="lyltd">
			<h3>
				Last Year Long-Term Debt
			</h3>
		</td>',
		'<td data-acro="lystd">
			<h3>
				Last Year Short-Term Debt
			</h3>
		</td>',
		'<td data-acro="lyd">
			<h4>
				Last Year Debt
			</h4>
		</td>',
		'<td data-acro="lye">
			<h3>
				Last Year Equity
			</h3>
		</td>',
		'<td data-acro="lycap">
			<h4>
				Last Year Capital
			</h4>
		</td>',
		'<td data-acro="lypcr">
			<h4>
				Last Year PII to Capital Ratio
			</h4>
		</td>',
		'<td data-acro="lyom">
			<h3>
				Last Year Operating Margin
			</h3>
		</td>',
		'<td data-acro="t12maom">
			<h3 class="se-sortable">
				Trailing 12 Month Average Operating Margin
			</h3>
		</td>',
		'<td data-acro="tlomr">
			<h2 class="se-sortable">
				Trailing Operating Margin vs Last Year Operating Margin
			</h2>
		</td>',
		'<td data-acro="apcr">
			<h4>
				Adjusted PII to Capital Ratio
			</h4>
		</td>',
		'<td data-acro="cpii">
			<h4>
				Future Pre-Interest Income
			</h4>
		</td>',
		'<td data-acro="wacodr">
			<h3>
				Weighted Average Cost of Debt Ratio
			</h3>
			
			<label>Exlude:</label>
			<select id="wacodr-filter" name="wacodr">
				<option>not set</option>
				<option>>= 9999.9999</option>
			</select>
		</td>',
		'<td data-acro="interest">
			<h4>
				Interest
			</h4>
		</td>',
		'<td data-acro="fi">
			<h4>
				Future Income
			</h4>
		</td>',
		'<td data-acro="lyroe">
			<h3>
				Last Year Return on Equity
			</h3>
		</td>',
		'<td data-acro="t12maroe">
			<h3>
				Trailing 12 Month Average Return on Equity
			</h3>
		</td>',
		'<td data-acro="tlroer">
			<h2>
				Trailing Return on Equity vs Last Year Return on Equity
			</h2>
		</td>',
		'<td data-acro="afi">
			<h4>
				Adjusted Future Income
			</h4>
		</td>',
		'<td data-acro="fe">
			<h4>
				Future Equity
			</h4>
		</td>',
		'<td data-acro="car" class="input">
			Compatitive Advantage Rating
		</td>',
		'<td data-acro="lpba">
			<h3>
				Latest P/B Adjustment
			</h3>
		</td>',
		'<td data-acro="lper">
			<h3 class="se-sortable">
				Latest P/E Ratio
			</h3>
		</td>',
		'<td data-acro="lpbr">
			<h3>
				Latest P/B Ratio
			</h3>
		</td>',
		'<td data-acro="gtlp">
			<h4>
				Graham Test of Latest Price
			</h4>
		</td>',
		'<td data-acro="lpgc">
			<h4>
				Latest Price Graham Condition
			</h4>
		</td>',
		'<td data-acro="aper">
			<h3>
				Average P/E Ratio of Last 3 Years
			</h3>
		</td>',
		'<td data-acro="apbr">
			<h3>
				Average P/B Ratio of Last 3 Years
			</h3>
		</td>',
		'<td data-acro="gtap">
			<h4>
				Graham Test of Average Price
			</h4>
		</td>',
		'<td data-acro="apgc">
			<h4>
				Average Price Graham Condition
			</h4>
		</td>',
		'<td data-acro="cc" class="input">
			Company Condition Still Good?
		</td>',
		'<td data-acro="pc">
			<h2>
				Price Condition
			</h2>
			
			<select id="pc-filter" name="pc">
				<option>not set</option>
				<option>1</option>
				<option>0</option>
			</select>
		</td>',
		'<td data-acro="anios">
			<h3>
				Average Net Issuance of Stock of Last 3 Years
			</h3>
		</td>',
		'<td data-acro="fp">
			<h4>
				Future Price
			</h4>
		</td>',
		'<td data-acro="fptm">
			<h4>
				Future Price in Today\'s Money
			</h4>
		</td>',
		'<td data-acro="prcv">
			<h4>
				Price Relfecting Current Value
			</h4>
		</td>',
		'<td data-acro="iv">
			<h2>
				Intrinsic Value
			</h2>
		</td>',
		'<td data-acro="cpivr">
			<h2 class="se-sortable">
				Current Price vs Intrinsic Value
			</h2>
		</td>',
		'<td data-acro="cpfptmr">
			<h4>
				Current Price vs Future Price in Today\'s Money
			</h4>
		</td>',
		'<td data-acro="pow">
			<h4>
				Probability of Winning (50% is gambling, less is lose)
			</h4>
		</td>',
		'<td data-acro="advice">
			<h4>
				Rough Raw Unrefined Advice
			</h4>
			
			<select id="advice-filter" name="advice">
				<option>not set</option>
				<option>hold</option>
				<option>buy</option>
				<option>sell</option>
			</select>
		</td>'
	];
?>