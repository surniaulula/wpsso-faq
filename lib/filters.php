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
		private $msgs;		// WpssoFaqFiltersMessages class object.
		private $og_type_faq          = 'website';
		private $og_type_question     = 'article';
		private $schema_type_faq      = 'webpage.faq';
		private $schema_type_question = 'question';

		public function __construct( &$plugin ) {

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
					'get_post_options' => 3,
					'get_term_options' => 3,
				),
				'bc_category_tax_slug' => 2,
			) );

			if ( is_admin() ) {

				/**
				 * Instantiate the WpssoFaqFiltersMessages class object.
				 */
				if ( ! class_exists( 'WpssoFaqFiltersMessages' ) ) {

					require_once WPSSOFAQ_PLUGINDIR . 'lib/filters-messages.php';
				}

				$this->msgs = new WpssoFaqFiltersMessages( $plugin );
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

		public function filter_bc_category_tax_slug( $tax_slug, $mod ) {

			if ( WPSSOFAQ_QUESTION_POST_TYPE === $mod[ 'post_type' ] ) {

				$tax_slug = WPSSOFAQ_CATEGORY_TAXONOMY;
			}

			return $tax_slug;
		}
	}
}
