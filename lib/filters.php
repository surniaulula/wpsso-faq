<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqFilters' ) ) {

	class WpssoFaqFilters {

		private $p;
		private $og_type_faq          = 'website';
		private $og_type_question     = 'article';
		private $schema_type_faq      = 'webpage.faq';
		private $schema_type_question = 'question';

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

			$this->p->util->add_plugin_filters( $this, array(
				'get_md_defaults' => 2,
				'get_md_options'  => array(
					'get_post_options'   => 3,
					'get_term_options'   => 3,
				),
			) );

			if ( is_admin() ) {

				$this->p->util->add_plugin_filters( $this, array( 
					'messages_tooltip' => 2,
				) );
			}
			/**
			 * Hard-code and disable these options in the settings pages.
			 */
			$this->p->options[ 'og_type_for_question' ]    = $this->og_type_question;
			$this->p->options[ 'og_type_for_question:is' ] = 'disabled';

			$this->p->options[ 'og_type_for_tax_faq_category' ]    = $this->og_type_faq;
			$this->p->options[ 'og_type_for_tax_faq_category:is' ] = 'disabled';

			$this->p->options[ 'schema_type_for_tax_faq_category' ]    = $this->schema_type_faq;
			$this->p->options[ 'schema_type_for_tax_faq_category:is' ] = 'disabled';

			$this->p->options[ 'schema_type_for_question' ]    = $this->schema_type_question;
			$this->p->options[ 'schema_type_for_question:is' ] = 'disabled';
		}

		public function filter_get_md_defaults( array $md_defs, array $mod ) {

			if ( $mod[ 'is_post' ] ) {

				if ( WPSSOFAQ_QUESTION_POST_TYPE === $mod[ 'post_type' ] ) {

					$md_defs[ 'og_type' ] = $this->og_type_question;

					$md_defs[ 'schema_type' ] = $this->schema_type_question;
				}
			
			} elseif ( $mod[ 'is_term' ] ) {

				if ( WPSSOFAQ_CATEGORY_TAXONOMY === $mod[ 'tax_slug' ] ) {

					$md_defs[ 'og_type' ] = $this->og_type_faq;

					$md_defs[ 'schema_type' ] = $this->schema_type_faq;
				}
			}

			return $md_defs;
		}

		public function filter_get_md_options( array $md_opts, $post_id, array $mod ) {

			$faq_opts = $this->filter_get_md_defaults( array(), $mod );

			foreach ( $faq_opts as $opt_key => $opt_val ) {

				$md_opts[ $opt_key ] = $opt_val;

				$md_opts[ $opt_key . ':is' ] = 'disabled';
			}

			return $md_opts;
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
