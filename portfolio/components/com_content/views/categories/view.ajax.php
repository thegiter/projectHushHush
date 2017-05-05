<?php
	defined('_JEXEC') or die;

	require root.'shared/ajax_chk/ajax_chk.php';

	define('shpsptfl_ajax', true);

	class ContentViewCategories extends JViewCategories {
		/**
		 * Language key for default page heading
		 *
		 * @var    string
		 * @since  3.2
		 */
		protected $pageHeading = 'JGLOBAL_ARTICLES';

		/**
		 * @var    string  The name of the extension for the category
		 * @since  3.2
		 */
		protected $extension = 'com_content';
	}
?>
