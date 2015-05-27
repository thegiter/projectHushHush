<?php
	if (!defined('root')) {
		define('root', '../../');
	}
	if ($_POST['refuri'] != '/about/の弑す魂の_ps/') {		// refuri is sent by js
		require_once(root.'shared/phps/get/err_403.php');

		die();
	}
	
	$tap_ctt_arr = array('terms_of_use' => '<h3 id="shps-tap-ttl">
							<span>Agree to Everything</span>
						</h3>
						<div id="shps-tap-ctt">
							<p>
								By using the services provided by <span class="kaiti" xml:lang="zh">の弑す魂の</span> PS or clicking <q>agree</q> or <q>accept</q> when an agreement is presented to you, you agree to agree to everything that <span class="kaiti" xml:lang="zh">の弑す魂の</span> PS demands or decides. You may not use the services if you do not agree. If you are not legally allowed to form a binding contract with <span class="kaiti" xml:lang="zh">の弑す魂の</span> PS, then you may not agree to the terms and may not use the services.
							</p>
							<p>
								<span class="kaiti" xml:lang="zh">の弑す魂の</span> PS may change its agreements at anytime without prior notices. By using the services after an agreement is changed, you agree to agree to the updated agreement.
							</p>
							<p>
								<span class="kaiti" xml:lang="zh">の弑す魂の</span> PS may end its services at anytime without prior notices.
							</p>
							<p>
								You may only use the services through means provided to you by <span class="kaiti" xml:lang="zh">の弑す魂の</span> PS.
							</p>
							<p>
								You are solely responsible for any loss or damage which are caused by your actions.
							</p>
						</div>',
						'privacy_policy' => '<h3 id="shps-tap-ttl">
							<span>Nothing Is Private, Everything Is Public</span>
						</h3>
						<p>
							<span class="kaiti" xml:lang="zh">の弑す魂の</span> PS does not guarantee any privacy protections. You are responsible for your own privacy. Do not input anything that you don\'t want to share with the public into this site.
						</p>
						<p>
							Although <span class="kaiti" xml:lang="zh">の弑す魂の</span> PS takes measures to protect your privacy as much as possible, it takes no responsibility when a privacy leakage occurs. You have the right to refuse to give any information to the site. Any information given to the site becomes the site\'s property and is up to the site to decide what to do with it.
						</p>
						<p>
							In short, <em class="fs-nrm">GIVE INFORMATION AT YOUR OWN RISK</em>.
						</p>',
						'copyright' => '<h3 id="shps-tap-ttl">
							<span>All Rights Reserved</span>
						</h3>
						<p>
							<span class="kaiti" xml:lang="zh">の弑す魂の</span> PS is copyrighted by <span class="kaiti" xml:lang="zh">の弑す魂の</span> PS. All rights are reserved. Its contents, however, are copyrighted by their respective authors.
						</p>
						<p>
							Use of material on <span class="kaiti" xml:lang="zh">の弑す魂の</span> PS without getting permission first is considered a criminal offense. The offender will be hunted down and will apologize naked in public.
						</p>
						<p>
							You have been warned.
						</p>');
						
	echo $tap_ctt_arr[$_GET['ttl']];
?>