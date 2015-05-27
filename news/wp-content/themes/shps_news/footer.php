<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
	<div id="footer" role="contentinfo">
		<div class="colophon clr-sgl-aft">
<?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	get_sidebar( 'footer' );
?>

			<div class="site-info">
				<?php
					if (!is_front_page()) :
				?>
					<a href="<?php echo home_url( '/' ) ?>" title="Go to the news section's homepage." rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php
					else :
						bloginfo( 'name' );
					endif;
				?>
				
			</div><!-- #site-info --><div class="site-generator">
				<?php
					do_action( 'twentyten_credits' );
				?>
				
				Proudly powered by <a href="<?php echo esc_url( __('http://wordpress.org/', 'twentyten') ); ?>" title="<?php esc_attr_e('Open the WordPress homepage.', 'twentyten'); ?>" target="_blank" class="tgt-blank" rel="generator">WordPress</a>.
			</div><!-- #site-generator -->

		</div><!-- #colophon -->
	</div><!-- #footer -->