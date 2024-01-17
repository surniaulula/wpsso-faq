<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2024 Jean-Sebastien Morisset (https://wpsso.com/)
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

		/*
		 * Instantiated by WpssoFaq->init_objects().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			/*
			 * This hook is fired once WordPress, plugins, and the theme are fully loaded and instantiated.
			 */
			add_action( 'wp_loaded', array( $this, 'add_wp_callbacks' ) );
		}

		/*
		 * Add WordPress action and filters callbacks.
		 */
		public function add_wp_callbacks() {

			if ( is_admin() ) {

				$post_type = WPSSOFAQ_QUESTION_POST_TYPE;

				/*
				 * See https://codex.wordpress.org/Plugin_API/Filter_Reference/manage_$post_type_posts_columns.
				 */
				add_filter( 'manage_' . $post_type . '_posts_columns', array( $this, 'add_post_column_headings' ), 10, 1 );

				/*
				 * See https://codex.wordpress.org/Plugin_API/Action_Reference/manage_$post_type_posts_custom_column.
				 */
				add_action( 'manage_' . $post_type . '_posts_custom_column', array( $this, 'show_column_content' ), 10, 2 );

				/*
				 * Maybe change 'Add title' to 'Question title'.
				 */
				add_filter( 'enter_title_here', array( $this, 'maybe_modify_enter_title' ), 10, 2 );

				/*
				 * Maybe change 'Type / to choose a block' to 'Answer text / to choose a block'.
				 */
				add_filter( 'write_your_story', array( $this, 'maybe_modify_enter_content' ), 10, 2 );

				/*
				 * Maybe change the 'Excerpt' metabox title to 'Answer Excerpt'.
				 *
				 * The 'add_meta_boxes' action fires after all built-in meta boxes have been added.
				 */
				add_action( 'add_meta_boxes', array( $this, 'maybe_modify_excerpt_metabox' ), 10, 2 );

				/*
				 * Maybe add a 'Add Answer' or 'Edit Answer' title above the content for the classic editor.
				 */
				add_action( 'edit_form_after_title', array( $this, 'maybe_show_edit_form_after_title' ), 10, 1 );
			}
		}

		public function add_post_column_headings( $columns ) {

			$sc_title_transl = __( 'Question Shortcode', 'wpsso-faq' );

			if ( isset( $columns[ $this->sc_after_key ] ) ) {

				SucomUtil::add_after_key( $columns, $this->sc_after_key, $this->sc_column_key, $sc_title_transl );

			} else $columns[ $this->sc_column_key ] = $sc_title_transl;

			return $columns;
		}

		public function show_column_content( $column_name, $post_id ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( $column_name . ' for post ID ' . $post_id );
			}

			if ( $this->sc_column_key === $column_name ) {

				$form      = $this->p->admin->get_form_object( $menu_ext = 'wpsso' );	// Return and maybe set/reset the WpssoAdmin->form value.
				$shortcode = '[' . WPSSOFAQ_QUESTION_SHORTCODE_NAME . ' id="' . $post_id . '"]';
				$css_class = $column_name;
				$css_id    = $column_name . '_' . $post_id;

				echo $form->get_no_input_clipboard( $shortcode, $css_class, $css_id );
			}
		}

		/*
		 * Maybe change 'Add title' to 'Question title'.
		 */
		public function maybe_modify_enter_title( $text, $post_obj ) {

			$post_type = get_post_type( $post_obj );

			if ( WPSSOFAQ_QUESTION_POST_TYPE === $post_type ) {

				$text = __( 'Question title', 'wpsso-faq' );
			}

			return $text;
		}

		/*
		 * Maybe change 'Type / to choose a block' to 'Answer text / to choose a block'.
		 */
		public function maybe_modify_enter_content( $text, $post_obj ) {

			$post_type = get_post_type( $post_obj );

			if ( WPSSOFAQ_QUESTION_POST_TYPE === $post_type ) {

				$text = __( 'Answer text / to choose a block', 'wpsso-faq' );
			}

			return $text;
		}

		/*
		 * Maybe change the 'Excerpt' metabox title to 'Answer Excerpt'.
		 */
		public function maybe_modify_excerpt_metabox( $post_type, $post_obj ) {

			 if ( WPSSOFAQ_QUESTION_POST_TYPE === $post_type ) {

				global $wp_meta_boxes;

				if ( isset( $wp_meta_boxes[ $post_type ][ 'normal' ][ 'core' ][ 'postexcerpt' ] ) ) {

					$postexcerpt =& $wp_meta_boxes[ $post_type ][ 'normal' ][ 'core' ][ 'postexcerpt' ];

					$postexcerpt[ 'title' ] = __( 'Answer Excerpt', 'wpsso-faq' );
				}
			}
		}

		/*
		 * Maybe add a 'Add Answer' or 'Edit Answer' title above the content for the classic editor.
		 */
		public function maybe_show_edit_form_after_title( $post_obj ) {

			$post_type = get_post_type( $post_obj );

			if ( WPSSOFAQ_QUESTION_POST_TYPE === $post_type ) {

				$post_status = get_post_status( $post_obj );

				echo '<h1 style="margin:0;padding:15px 0 0 0;">';

				if ( 'auto-draft' === $post_status ) {

					echo __( 'Add Answer', 'wpsso-faq' );

				} else {

					echo __( 'Edit Answer', 'wpsso-faq' );
				}

				echo '</h1>';
			}
		}
	}
}
