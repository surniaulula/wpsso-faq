<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqSubmenuFaqGeneral' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoFaqSubmenuFaqGeneral extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;
		}

		/**
		 * Called by the extended WpssoAdmin class.
		 */
		protected function add_meta_boxes() {

			$metabox_id      = 'general';
			$metabox_title   = _x( 'Frequently Asked Questions', 'metabox title', 'wpsso-faq' );
			$metabox_screen  = $this->pagehook;
			$metabox_context = 'normal';
			$metabox_prio    = 'default';
			$callback_args   = array(	// Second argument passed to the callback function / method.
			);

			add_meta_box( $this->pagehook . '_' . $metabox_id, $metabox_title,
				array( $this, 'show_metabox_' . $metabox_id ), $metabox_screen,
					$metabox_context, $metabox_prio, $callback_args );
		}

		/**
		 * Frequently Asked Questions metabox.
		 */
		public function show_metabox_general() {

			$metabox_id  = 'faq-general';

			$filter_name = SucomUtil::sanitize_hookname( 'wpsso_' . $metabox_id . '_tabs' );

			$tabs = apply_filters( $filter_name, array(
				'shortcodes' => _x( 'Shortcode Defaults', 'metabox tab', 'wpsso-faq' ),
				'settings'   => _x( 'Add-on Settings', 'metabox tab', 'wpsso-faq' ),
			) );

			$table_rows = array();

			foreach ( $tabs as $tab_key => $title ) {

				$filter_name = SucomUtil::sanitize_hookname( 'wpsso_' . $metabox_id . '_' . $tab_key . '_rows' );

				$table_rows[ $tab_key ] = $this->get_table_rows( $metabox_id, $tab_key );

				$table_rows[ $tab_key ] = apply_filters( $filter_name, $table_rows[ $tab_key ], $this->form, $network = false );
			}

			$this->p->util->metabox->do_tabbed( $metabox_id, $tabs, $table_rows );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'faq-general-shortcodes':

					$table_rows[ 'faq_heading' ] = '' .
						$this->form->get_th_html( _x( 'FAQ Shortcode Title Heading', 'option label', 'wpsso-faq' ),
							$css_class = '', $css_id = 'faq_heading' ) .
						'<td>' . $this->form->get_select( 'faq_heading', $this->p->cf[ 'form' ][ 'html_headings' ] ) . '</td>';

					$table_rows[ 'faq_question_heading' ] = '' .
						$this->form->get_th_html( _x( 'Question Shortcode Title Heading', 'option label', 'wpsso-faq' ),
							$css_class = '', $css_id = 'faq_question_heading' ) .
						'<td>' . $this->form->get_select( 'faq_question_heading', $this->p->cf[ 'form' ][ 'html_headings' ] ) . '</td>';

					$table_rows[ 'faq_answer_format' ] = '' .
						$this->form->get_th_html( _x( 'Question Shortcode Answer Format', 'option label', 'wpsso-faq' ),
							$css_class = '', $css_id = 'faq_answer_format' ) .
						'<td>' . $this->form->get_select( 'faq_answer_format', array(
							'content' => __( 'Full Answer', 'wpsso-faq' ),
							'excerpt' => __( 'Answer Excerpt', 'wpsso-faq' ),
						) ) . '</td>';

					$table_rows[ 'faq_answer_toggle' ] = '' .
						$this->form->get_th_html( _x( 'Click Question to Show/Hide Answer', 'option label', 'wpsso-faq' ),
							$css_class = '', $css_id = 'faq_answer_toggle' ) .
						'<td>' . $this->form->get_checkbox( 'faq_answer_toggle' ) . '</td>';

					break;

				case 'faq-general-settings':

					$table_rows[ 'faq_public_disabled' ] = '' .
						$this->form->get_th_html( _x( 'Disable FAQ and Question URLs', 'option label', 'wpsso-faq' ),
							$css_class = '', $css_id = 'faq_public_disabled' ) .
						'<td>' . $this->form->get_checkbox( 'faq_public_disabled' ) . '</td>';

					// translators: Please ignore - translation uses a different text domain.
					$add_to_metabox_title = _x( $this->p->cf[ 'meta' ][ 'title' ], 'metabox title', 'wpsso' );

					// translators: Please ignore - translation uses a different text domain.
					$label_prefix  = _x( 'Post Type', 'option label', 'wpsso' );
					$post_type_obj = get_post_type_object( WPSSOFAQ_QUESTION_POST_TYPE );
					$add_to_values = SucomUtil::get_post_type_labels( $val_prefix = '', $label_prefix, array( $post_type_obj ) );

					// translators: Please ignore - translation uses a different text domain.
					$label_prefix  = _x( 'Taxonomy', 'option label', 'wpsso' );
					$taxonomy_obj  = get_taxonomy( WPSSOFAQ_FAQ_CATEGORY_TAXONOMY );
					$add_to_values += SucomUtil::get_taxonomy_labels( $val_prefix = 'tax_', $label_prefix, array( $taxonomy_obj ) );

					$table_rows[ 'plugin_add_to' ] = '' .	// Show Document SSO Metabox.
						$this->form->get_th_html( sprintf( _x( 'Show %s Metabox', 'option label', 'wpsso' ), $add_to_metabox_title ),
							$css_class = '', $css_id = 'plugin_add_to' ) .
						'<td>' . $this->form->get_checklist( $name_prefix = 'plugin_add_to', $add_to_values,
							$css_class = 'input_vertical_list' ) . '</td>';

					break;
			}

			return $table_rows;
		}
	}
}
