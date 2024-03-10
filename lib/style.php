<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqStyle' ) ) {

	class WpssoFaqStyle {

		private $p;	// Wpsso class object.
		private $a;	// WpssoFaq class object.

		/*
		 * Instantiated by WpssoFaq->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			if ( is_admin() ) {

				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), WPSSO_ADMIN_SCRIPTS_PRIORITY );
			}
		}

		public function admin_enqueue_styles( $hook_name ) {

			$this->add_admin_page_style();
		}

		private function add_admin_page_style() {

			$custom_style_css = '
				body.taxonomy-faq_category #col-container #col-left {
					width:25%;
				}
				body.taxonomy-faq_category #col-container #col-right {
					width:75%;
				}
				table.wp-list-table > thead > tr > th.column-wpsso_faq_shortcode,
				table.wp-list-table > tbody > tr > td.column-wpsso_faq_shortcode {
					width:200px;
					height:auto;
				}
				table.wp-list-table.tags > thead > tr > th.column-wpsso_faq_shortcode,
				table.wp-list-table.tags > tbody > tr > td.column-wpsso_faq_shortcode {
					width:160px;
				}
				table.wp-list-table > tbody > tr > td.column-wpsso_faq_shortcode input {
					width:100%;
				}
			';

			$custom_style_css = SucomUtil::minify_css( $custom_style_css, $filter_prefix = 'wpsso' );

			wp_add_inline_style( 'sucom-admin-page', $custom_style_css );
		}
	}
}
