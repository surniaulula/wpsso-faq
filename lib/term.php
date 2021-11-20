<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqTerm' ) ) {

	class WpssoFaqTerm {

		private $p;	// Wpsso class object.
		private $a;	// WpssoFaq class object.

		private $sc_after_key  = 'name';
		private $sc_column_key = 'wpsso_faq_shortcode';

		/**
		 * Instantiated by WpssoFaq->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			add_action( 'wp_loaded', array( $this, 'add_wp_hooks' ) );
		}

		/**
		 * Add WordPress action and filters hooks.
		 */
		public function add_wp_hooks() {

			if ( is_admin() ) {

				add_filter( 'manage_edit-' . WPSSOFAQ_FAQ_CATEGORY_TAXONOMY . '_columns', array( $this, 'add_term_column_headings' ), 10, 1 );

				add_filter( 'manage_' . WPSSOFAQ_FAQ_CATEGORY_TAXONOMY . '_custom_column', array( $this, 'get_column_content' ), 10, 3 );
			}
		}

		public function add_term_column_headings( $columns ) {

			if ( isset( $columns[ $this->sc_after_key ] ) ) {

				SucomUtil::add_after_key( $columns, $this->sc_after_key, $this->sc_column_key, __( 'Shortcode', 'wpsso-faq' ) );

			} else {

				$columns[ $this->sc_column_key ] = __( 'Shortcode', 'wpsso-faq' );
			}

			return $columns;
		}

		public function get_column_content( $value, $column_name, $term_id ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( $column_name . ' for term ID ' . $term_id );
			}

			if ( $this->sc_column_key === $column_name ) {

				$value = SucomForm::get_no_input_clipboard( '[' . WPSSOFAQ_FAQ_SHORTCODE_NAME . ' id="' . $term_id . '"]',
					$css_class = $column_name, $css_id = $column_name . '_' . $term_id );
			}

			return $value;
		}
	}
}
