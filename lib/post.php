<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2021 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqPost' ) ) {

	class WpssoFaqPost {

		private $p;	// Wpsso class object.
		private $a;	// WpssoFaq class object.

		private $sc_after_key  = 'title';
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

				$ptns = array( WPSSOFAQ_QUESTION_POST_TYPE );

				if ( is_array( $ptns ) ) {

					foreach ( $ptns as $ptn ) {

						if ( $this->p->debug->enabled ) {

							$this->p->debug->log( 'adding column filters for post type ' . $ptn );
						}

						/**
						 * See https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$post_type_posts_columns.
						 */
						add_filter( 'manage_' . $ptn . '_posts_columns', array( $this, 'add_post_column_headings' ), 10, 1 );

						/**
						 * See https://codex.wordpress.org/Plugin_API/Action_Reference/manage_$post_type_posts_custom_column.
						 */
						add_action( 'manage_' . $ptn . '_posts_custom_column', array( $this, 'show_column_content' ), 10, 2 );
					}
				}
			}
		}

		public function add_post_column_headings( $columns ) {

			if ( isset( $columns[ $this->sc_after_key ] ) ) {

				SucomUtil::add_after_key( $columns, $this->sc_after_key, $this->sc_column_key, __( 'Shortcode', 'wpsso-faq' ) );

			} else {

				$columns[ $this->sc_column_key ] = __( 'Shortcode', 'wpsso-faq' );
			}

			return $columns;
		}

		public function show_column_content( $column_name, $post_id ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( $column_name . ' for post ID ' . $post_id );
			}

			if ( $this->sc_column_key === $column_name ) {

				$form      = $this->p->admin->get_form_object( $menu_ext = 'wpsso' );
				$shortcode = '[' . WPSSOFAQ_QUESTION_SHORTCODE_NAME . ' id="' . $post_id . '"]';
				$css_class = $column_name;
				$css_id    = $column_name . '_' . $post_id;

				echo $form->get_no_input_clipboard( $shortcode, $css_class, $css_id );
			}
		}
	}
}
