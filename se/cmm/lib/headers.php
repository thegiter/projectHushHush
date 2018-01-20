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
			<h3 class="se-sortable">
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
		'<td data-acro="at12mni">
			<h3 class="se-sortable">
				Adjusted Trailing 12 Months Net Income
			</h3>
		</td>',
		'<td data-acro="lyom">
			<h3>
				Last Year Operating Margin
			</h3>
		</td>',
		'<td data-acro="t12maom">
			<h3 class="se-sortable">
				Trailing 12 Months Average Operating Margin
			</h3>
		</td>',
		'<td data-acro="tlomr">
			<h2 class="se-sortable">
				Trailing Operating Margin vs Last Year Operating Margin
			</h2>
		</td>',
		'<td data-acro="aomg">
			<h2 class="se-sortable">
				Ave OM Growth Rate
			</h2>
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
		'<td data-acro="lyroe">
			<h3>
				Last Year Return on Equity
			</h3>
		</td>',
		'<td data-acro="t12maroe">
			<h3>
				Trailing 12 Months Average Return on Equity
			</h3>
		</td>',
		'<td data-acro="tlroer">
			<h2>
				Trailing Return on Equity vs Last Year Return on Equity
			</h2>
		</td>',
		'<td data-acro="lyroc">
			<h3>
				Last Year Return on Capital
			</h3>
		</td>',
		'<td data-acro="t12maroc">
			<h3>
				Trailing 12 Months Average Return on Capital
			</h3>
		</td>',
		'<td data-acro="tlrocr">
			<h2>
				Trailing Return on Capital vs Last Year Return on Capital
			</h2>
		</td>',
		'<td data-acro="pci">
			<h4>
				Projected Current Income
			</h4>
		</td>',
		'<td data-acro="pa">
			<h2>
				Projection Accuracy
			</h2>
		</td>',
		'<td data-acro="pfi">
			<h4>
				Projected Future Income
			</h4>
		</td>',
		'<td data-acro="apfi">
			<h4>
				Adjusted Projected Future Income
			</h4>
		</td>',
		'<td data-acro="arota">
			<h3 class="se-sortable">
				Average Return on Tangible Assets of Last 3 Years
			</h3>
		</td>',
		'<td data-acro="normArota">
			<h3 class="se-sortable">
				Normalized AROTA of Last 3 Years
			</h3>
		</td>',
		'<td data-acro="rotaRank">
			<h3 class="se-sortable">
				ROTA Rank in Industry
			</h3>
		</td>',
		'<td data-acro="glbRank">
			<h3 class="se-sortable">
				Forbes Global Rank
			</h3>
		</td>',
		'<td data-acro="arote">
			<h3 class="se-sortable">
				Average ROTE of Last 5 Years
			</h3>
		</td>',
		'<td data-acro="car" class="input">
			Compatitive Advantage Rating
		</td>',
		'<td data-acro="lper">
			<h3 class="se-sortable">
				Latest P/E Ratio
			</h3>

			<label>Exlude:</label>
			<select id="lper-filter" name="lper">
				<option>not set</option>
				<option>>= 999.9999</option>
			</select>
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
		'<td data-acro="cigr">
			<h4>
				Current Income Growth Rate
			</h4>
		</td>',
		'<td data-acro="prlyv">
			<h4>
				Price Relfecting Last Year\'s Value
			</h4>
		</td>',
		'<td data-acro="cpigr">
			<h4>
				Current Projected Income Growth Rate
			</h4>
		</td>',
		'<td data-acro="prcv">
			<h4>
				Price Relfecting Current Value
			</h4>
		</td>',
		'<td data-acro="prcv0g">
			<h2>
				PRCV Of No Growth Potential
			</h2>
		</td>',
		'<td data-acro="fpigr">
			<h4>
				Future Projected Income Growth Rate
			</h4>

			<select id="fpigr-filter" name="fpigr">
				<option>not set</option>
				<option>&#60;= 1.2</option>
			</select>
		</td>',
		'<td data-acro="fp">
			<h4>
				Potential Future Price Cieling
			</h4>
		</td>',
		'<td data-acro="fptm">
			<h2>
				PFPC in Today\'s Money
			</h2>
		</td>',
		'<td data-acro="dwmoe">
			<h4>
				Downward Margin of Error
			</h4>
		</td>',
		'<td data-acro="afptm">
			<h2>
				Adjusted PFPC
			</h2>
		</td>',
		'<td data-acro="lffptm">
			<h2>
				Potential Future Price Floor
			</h2>
		</td>',
		'<td data-acro="ep">
			<h4>
				Equilibrium Price
			</h4>
		</td>',
		'<td data-acro="bp">
			<h2>
				Betting Price
			</h2>
		</td>',
		'<td data-acro="abdr">
			<h4>
				Adjusted Betting Discount Rate
			</h4>
		</td>',
		'<td data-acro="niosi">
			<h2 class="se-sortable">
				Net Issuance of Stock Influence on Projection
			</h2>
		</td>',
		'<td data-acro="iv">
			<h4>
				Intrinsic Value
			</h4>
		</td>',
		'<td data-acro="cp">
			<h3 class="se-sortable">
				Current Price
			</h3>
		</td>',
		'<td data-acro="bpcpr">
			<h2 class="se-sortable">
				Betting Price vs Current Price
			</h2>
		</td>',
		'<td data-acro="ivcpr">
			<h2 class="se-sortable">
				Price Floor vs Current Price
			</h2>
		</td>',
		'<td data-acro="cpfptmr">
			<h4>
				Current Price vs Adjusted Potentail Future Price Cieling
			</h4>
		</td>',
		'<td data-acro="pow">
			<h4>
				Probability of Winning Value (50% is gambling, less is lose)
			</h4>

			<label>Exlude:</label>
			<select id="pow-filter" name="pow">
				<option>not set</option>
				<option>&#60;= 80%</option>
				<option>&#60;= 50%</option>
			</select>
		</td>',
		'<td data-acro="powm">
			<h4>
				Probability of Winning Momentum
			</h4>

			<label>Exlude:</label>
			<select id="pow-filter" name="pow">
				<option>not set</option>
				<option>&#60;= 85%</option>
				<option>&#60;= 50%</option>
			</select>
		</td>',
		'<td data-acro="advice">
			<h4>
				Rough Raw Unrefined Advice
			</h4>

			<select id="advice-filter" name="advice">
				<option>not set</option>
				<option>hold</option>
				<option>betting buy</option>
				<option>buy</option>
				<option>sell</option>
				<option>be ready to sell</option>
			</select>
		</td>'
	];
?>
