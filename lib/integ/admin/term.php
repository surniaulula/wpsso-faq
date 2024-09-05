<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqIntegAdminTerm' ) ) {

	class WpssoFaqIntegAdminTerm {

		private $p;	// Wpsso class object.
		private $sc_after_key  = 'name';
		private $sc_column_key = 'wpsso_faq_shortcode';

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			/*
			 * This hook is fired once WordPress, plugins, and the theme are fully loaded and instantiated.
			 */
			add_action( 'wp_loaded', array( $this, 'add_wp_callbacks' ) );
		}

		/*
		 * Add WordPress action and filters callbacks.
		 */
		public function add_wp_callbacks() {

			if ( ! is_admin() ) return;	// Just in case.

			add_filter( 'manage_edit-' . WPSSOFAQ_FAQ_CATEGORY_TAXONOMY . '_columns', array( $this, 'add_term_column_headings' ), 10, 1 );

			add_filter( 'manage_' . WPSSOFAQ_FAQ_CATEGORY_TAXONOMY . '_custom_column', array( $this, 'get_column_content' ), 10, 3 );
		}

		public function add_term_column_headings( $columns ) {

			$sc_title_transl = __( 'FAQ Shortcode', 'wpsso-faq' );

			if ( isset( $columns[ $this->sc_after_key ] ) ) {

				SucomUtil::add_after_key( $columns, $this->sc_after_key, $this->sc_column_key, $sc_title_transl );

			} else $columns[ $this->sc_column_key ] = $sc_title_transl;

			return $columns;
		}

		public function get_column_content( $value, $column_name, $term_id ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( $column_name . ' for term ID ' . $term_id );
			}

			if ( $this->sc_column_key === $column_name ) {

				$form      = $this->p->admin->get_form_object( $menu_ext = 'wpsso' );	// Return and maybe set/reset the WpssoAdmin->form value.
				$shortcode = '[' . WPSSOFAQ_FAQ_SHORTCODE_NAME . ' id="' . $term_id . '"]';
				$css_class = $column_name;
				$css_id    = $column_name . '_' . $term_id;

				$value = $form->get_no_input_clipboard( $shortcode, $css_class, $css_id );
			}

			return $value;
		}
	}
}
