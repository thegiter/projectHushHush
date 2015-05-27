<?php
/**
 * The home page template file.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
**/

	defined('root') or die;
	
	$thisPage = 'news'; //for menu

	require_once(root.'shared/phps/dtcs/ied.php');
	//require(root.'shared/phps/dtcs/ffd.php');
	require_once(root.'shared/phps/dtcs/jsd.php');	//put brs dtcs before this
	//require(root.'shared/phps/dtcs/fpd.php');
	require(root.'shared/phps/dtdec_x.php');
	
	echo "\n";
	
	require root.'shared/phps/heads.php';
?>


		<meta name="description" content="The news section of Desmond's personal site. Currently in its beta." />
		<meta name="keywords" content="弑魂,PS,Personal Site,News" />
		<meta name="robots" content="index,follow" />
		<meta name="cnr_bg_dftAnim_ids" content="abt-pnl-cnr-1 abt-pnl-cnr-2" /> <!-- left as a reference, should be deleted if find useless after the page is done. It is used to tell dft_anim.js which elements to animate -->
		
		<script src="/shared/jss/common.js" type="text/javascript">
		</script>
		<script src="/shared/jss/anim.js" type="text/javascript">				<!-- anim is required for ldg.js and beta.js -->
		</script>
		<script type="text/javascript" src="/shared/jss/bsc_twn.js">
		</script>
		<script src="/shared/jss/ldg.js" type="text/javascript">
		</script>
		<script src="/shared/jss/beta.js" type="text/javascript">			<!-- these jss are order-sensitive -->
		</script>
		<script	type="text/javascript" src="/shared/jss/wrt_bg.js">
		</script>
		<script	type="text/javascript" src="/news/wp-content/themes/shps_news/jss/menu.js">
		</script>
		<script type="text/javascript" src="/shared/jss/cnr_bg/dft_anim.js">
		</script>
		<script type="text/javascript" src="/shared/jss/gaa.js">			<!-- must be last -->
		</script>
		
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		
		<link rel="stylesheet" type="text/css" href="/shared/csss/ldg.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/logo_bg_1.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/logo.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/lv2/menu/csss/menu.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/bc.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/news/wp-content/themes/shps_news/csss/lv2_ttl.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/lv2/featured/csss/featured.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/news/wp-content/themes/shps_news/csss/news.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/cnr_bg.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" media="all" href="<?php
			bloginfo( 'stylesheet_url' );
		?>" />
		<link rel="stylesheet" type="text/css" href="/shared/footer/csss/footer.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/beta/init_stg.css" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="/shared/csss/beta/beta.css" charset="utf-8" />

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		
		<title><?php
			/*
			 * Print the <title> tag based on what is being viewed.
			 */
			global $page, $paged;

			wp_title( '|', true, 'right' );

			// Add the blog name.
			bloginfo( 'name' );

			// Add a page number if necessary:
			if ( $paged >= 2 || $page >= 2 )
				echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );
		?></title>
		
		<?php
			/* We add some JavaScript to pages with the comment form
			* to support sites with threaded comments (when in use).
			*/
			if ( is_singular() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}

			/* Always have wp_head() just before the closing </head>
			* tag of your theme, or you will break many plugins, which
			* generally use this hook to add elements to <head> such
			* as styles, scripts, and meta tags.
			*/
			//wp_head();
		?>
	</head>
	
	<body>
		<?php
			require(root.'shared/phps/ldg.php');
		?>
		
		<div id="cnr" class="bd-wpr dsp-non">
			<?php
				require root.'shared/lv2/header/header.php';
				
				$ftd_ctt_url = ''; //define the path to the headline content file
				$ftd_ttl = 'Headlines';	//define the title of the featured module
				$ftd_caption = true;	// enable the caption in the featured module
				
				require root.'shared/lv2/featured/featured.php';
			?>

			<div id="ctt-wpr" class="clr-dbl-aft">
				<div id="content" role="main" class="flt-lft">
					<?php
						/* Run the loop to output the posts.
						* If you want to overload this in a child theme then include a file
						* called loop-index.php and that will be used instead.
						*/
						get_template_part( 'loop', 'index' );
					?>
				</div><!-- #content -->
			</div>
			<?php
				get_sidebar();
				
				$ftr_bfr = 'news/wp-content/themes/shps_news/tplt/ftr/ftr.php'; 								
				$ftr_aftr = ''; 								//nothing after the copyright
				
				require root.'shared/footer/footer.php';
			?>

		</div>
		
		<?php
			/* Always have wp_footer() just before the closing </body>
			 * tag of your theme, or you will break many plugins, which
			 * generally use this hook to reference JavaScript files.
			 */

			//wp_footer();	causes error in strict xhtml format
			
			require(root.'shared/phps/beta.php');
		?>
		
	</body>
</html>