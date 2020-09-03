<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqFiltersMessages' ) ) {

	class WpssoFaqFiltersMessages {

		private $p;

		/**
		 * Instantiated by WpssoFaqFilters->__construct().
		 */
		public function __construct( &$plugin ) {

			/**
			 * Just in case - prevent filters from being hooked and executed more than once.
			 */
			static $do_once = null;

			if ( true === $do_once ) {

				return;	// Stop here.
			}

			$do_once = true;

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( is_admin() ) {

				$this->p->util->add_plugin_filters( $this, array( 
					'messages_tooltip'      => 2,
				) );
			}
		}

		public function filter_messages_tooltip( $text, $msg_key ) {

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( strpos( $msg_key, 'tooltip-faq_' ) !== 0 ) {
				return $text;
			}

			switch ( $msg_key ) {

				case 'tooltip-faq_answer_toggle':	// Clicking a Question Shows its Answer.

					$text = __( 'Hide the answer text by default and show when the question title is clicked.', 'wpsso-faq' );

					break;

				case 'tooltip-faq_answer_format':	// Answer Format Bellow the Question.

					$text = __( 'Select the type of answer text to include below the question title.', 'wpsso-faq' );

					break;

				case 'tooltip-faq_public_disabled':	// Disable FAQ and Question Page URLs.

					$text = __( 'The FAQ and question pages have publicly accessible URLs by default.', 'wpsso-faq' ) . ' ';

					$text .= __( 'If you enable this option, the FAQ and question content will only be accessible by using their shortcodes.', 'wpsso-faq' );

					break;
			}

			return $text;
		}
	}
}
