<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2024 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqSubmenuFaqSettings' ) && class_exists( 'WpssoAdmin' ) ) {

	class WpssoFaqSubmenuFaqSettings extends WpssoAdmin {

		public function __construct( &$plugin, $id, $name, $lib, $ext ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->menu_id   = $id;
			$this->menu_name = $name;
			$this->menu_lib  = $lib;
			$this->menu_ext  = $ext;

			$this->menu_metaboxes = array(
				'faq' => _x( 'Frequently Asked Questions', 'metabox title', 'wpsso-faq' ),
			);
		}

		public function show_metabox_faq( $obj, $mb ) {

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$tabs = array(
				'shortcode-defaults' => _x( 'Shortcode Defaults', 'metabox tab', 'wpsso-faq' ),
				'addon-settings'     => _x( 'Add-on Settings', 'metabox tab', 'wpsso-faq' ),
			);

			$this->show_metabox_tabbed( $obj, $mb, $tabs );
		}

		protected function get_table_rows( $page_id, $metabox_id, $tab_key = '', $args = array() ) {

			$table_rows = array();
			$match_rows = trim( $page_id . '-' . $metabox_id . '-' . $tab_key, '-' );

			switch ( $match_rows ) {

				case 'faq-settings-faq-shortcode-defaults':

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

				case 'faq-settings-faq-addon-settings':

					$table_rows[ 'faq_public_disabled' ] = '' .
						$this->form->get_th_html( _x( 'Disable FAQ and Question URLs', 'option label', 'wpsso-faq' ),
							$css_class = '', $css_id = 'faq_public_disabled' ) .
						'<td>' . $this->form->get_checkbox( 'faq_public_disabled' ) . '</td>';

					// translators: Please ignore - translation uses a different text domain.
					$add_to_metabox_title = _x( $this->p->cf[ 'meta' ][ 'title' ], 'metabox title', 'wpsso' );

					// translators: Please ignore - translation uses a different text domain.
					$label_prefix  = _x( 'Post Type', 'option label', 'wpsso' );
					$post_type_obj = get_post_type_object( WPSSOFAQ_QUESTION_POST_TYPE );
					$add_to_values = SucomUtilWP::get_post_type_labels( $val_prefix = '', $label_prefix, array( $post_type_obj ) );

					// translators: Please ignore - translation uses a different text domain.
					$label_prefix  = _x( 'Taxonomy', 'option label', 'wpsso' );
					$taxonomy_obj  = get_taxonomy( WPSSOFAQ_FAQ_CATEGORY_TAXONOMY );
					$add_to_values += SucomUtilWP::get_taxonomy_labels( $val_prefix = 'tax_', $label_prefix, array( $taxonomy_obj ) );

					$table_rows[ 'plugin_add_to' ] = '' .	// Show Document SSO Metabox.
						$this->form->get_th_html( sprintf( _x( 'Show %s Metabox', 'option label', 'wpsso' ), $add_to_metabox_title ),
							$css_class = '', $css_id = 'plugin_add_to' ) .
						'<td>' . $this->form->get_checklist( $name_prefix = 'plugin_add_to', $add_to_values,
							$css_class = 'column-list' ) . '</td>';

					break;
			}

			return $table_rows;
		}
	}
}
