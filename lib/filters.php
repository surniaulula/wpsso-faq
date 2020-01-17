<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2016-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqFilters' ) ) {

	class WpssoFaqFilters {

		private $p;

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
		}

		public function filter_get_md_defaults( array $md_defs, array $mod ) {

			if ( $mod[ 'is_post' ] ) {

				if ( WPSSOFAQ_QUESTION_POST_TYPE === $mod[ 'post_type' ] ) {

					$md_defs[ 'schema_type' ] = 'question';
				}
			
			} elseif ( $mod[ 'is_term' ] ) {

				if ( WPSSOFAQ_CATEGORY_TAXONOMY === $mod[ 'tax_slug' ] ) {

					$md_defs[ 'schema_type' ] = 'webpage.faq';
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
	}
}
