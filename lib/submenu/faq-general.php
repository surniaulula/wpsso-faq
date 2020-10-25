<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2020 Jean-Sebastien Morisset (https://wpsso.com/)
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

			$filter_name = SucomUtil::sanitize_hookname( $this->p->lca . '_' . $metabox_id . '_tabs' );

			$tabs = apply_filters( $filter_name, array(
				'shortcodes' => _x( 'Shortcode Defaults', 'metabox tab', 'wpsso-faq' ),
				'settings'   => _x( 'Add-on Settings', 'metabox tab', 'wpsso-faq' ),
			) );

			$table_rows = array();

			foreach ( $tabs as $tab_key => $title ) {

				$filter_name = SucomUtil::sanitize_hookname( $this->p->lca . '_' . $metabox_id . '_' . $tab_key . '_rows' );

				$table_rows[ $tab_key ] = array_merge(
					$this->get_table_rows( $metabox_id, $tab_key ),
					(array) apply_filters( $filter_name, array(), $this->form )
				);
			}

			$this->p->util->metabox->do_tabbed( $metabox_id, $tabs, $table_rows );
		}

		protected function get_table_rows( $metabox_id, $tab_key ) {

			$table_rows = array();

			switch ( $metabox_id . '-' . $tab_key ) {

				case 'faq-general-shortcodes':

					$table_rows[ 'faq_answer_toggle' ] = '' .
					$this->form->get_th_html( _x( 'Clicking a Question Shows its Answer', 'option label', 'wpsso-faq' ),
						$css_class = '', $css_id = 'faq_answer_toggle' ) . 
					'<td>' . $this->form->get_checkbox( 'faq_answer_toggle' ) . '</td>';

					$table_rows[ 'faq_answer_format' ] = '' .
					$this->form->get_th_html( _x( 'Answer Format Bellow the Question', 'option label', 'wpsso-faq' ),
						$css_class = '', $css_id = 'faq_answer_format' ) . 
					'<td>' . $this->form->get_select( 'faq_answer_format', array(
						'content' => __( 'Full Content', 'wpsso-faq' ),
						'excerpt' => __( 'Excerpt', 'wpsso-faq' ),
					) ) . '</td>';

					break;

				case 'faq-general-settings':

					$table_rows[ 'faq_public_disabled' ] = '' .
					$this->form->get_th_html( _x( 'Disable FAQ and Question Page URLs', 'option label', 'wpsso-faq' ),
						$css_class = '', $css_id = 'faq_public_disabled' ) . 
					'<td>' . $this->form->get_checkbox( 'faq_public_disabled' ) . '</td>';

					break;
			}

			return $table_rows;
		}
	}
}
