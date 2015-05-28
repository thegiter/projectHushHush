<div id="bc-cnr">
				<div class="bc-hd"></div><ul class="bc-bd no-style no-mrg-top no-mrg-btm"><li class="bc-have-arrow"><a href="/" title="Go to the homepage."><span class="kaiti" xml:lang="zh" lang="zh">の弑す魂の</span> PS</a></li><?php
					if (isset($debug) && $debug) {
						preg_match('/url=(.*)(&.*)*$/', $_SERVER['QUERY_STRING'], $matches);
						
						$bc_url = '/'.$matches[1];																						//$matches[0] will contain the text that matched the full pattern, $matches[1] will have the text that matched the first captured parenthesized subpattern, and so on
					}
					else if (strpos($_SERVER['SCRIPT_NAME'], '/news/') === 0 || strpos($_SERVER['SCRIPT_NAME'], '/about/') === 0) {		//triple equal signs are used to check whether the 2 are same in content and in type, eg. both are intergers.
						$bc_url = $_SERVER['REQUEST_URI'];
					}
					else {
						$bc_url = $_SERVER['PHP_SELF'];
					}
					
					$bc_url = preg_replace('/(\.{1}.*)?(\?{1}.*)?$/', '', $bc_url);		//clear anything after '.' or '?' if they exist.
					$bc_url = rawurldecode($bc_url); 									//decode url -- give my chinese back!!! rawurlencode encodes spaces as %20s where as urlencode encodes spaces as plus (+) signs
					$bc = explode('/', $bc_url);
					$last_key = count($bc)-1;
					$last_item = $bc[$last_key];
					
					if (empty($last_item) || preg_match('/^index(\.{1}.*)?(\?{1}.*)?$/', $last_item)) {
						$last_key = count($bc)-2;
					}
					
					$href = '';
					
					foreach($bc as $key => $value){
						if ($key == 0) {
							continue;
						}
						
						switch ($value) {
							case 'の弑す魂の_ps':
								$name = '<span class="kaiti" xml:lang="zh" lang="zh">の弑す魂の</span> PS';
								
								break;
							case 'の弑す魂の':
								$name = 'The Owner';
								
								break;
							default:
								$name = str_replace('_', ' ', $value);
								$name = str_replace('-', ' ', $name);
								$name = ucwords($name);
						}
						
						if ($key === $last_key) {
							echo '<li>'.$name.'</li></ul>';
							
							break;							// breaks the foreach statement, do not remove
						}
						else {
							$href .= $value.'/';														// note this is a plus equal to (for text) not a regular equal to, therefore $href is not the same as $value, so do not delete
							
							echo '<li class="bc-have-arrow"><a href="/'.$href.'">'.$name.'</a></li>';
						}
					}
				?><div class="bc-ass"></div><span class="bc-yrh">You are here</span>
			</div>