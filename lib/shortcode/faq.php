<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqShortcodeFaq' ) ) {

	class WpssoFaqShortcodeFaq {

		private $p;
		private $shortcode_name = 'faq';	// Default shortcode name.

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->shortcode_name = WPSSOFAQ_FAQ_SHORTCODE_NAME;

			$this->add_shortcode();
		}

		public function add_shortcode() {

			if ( shortcode_exists( $this->shortcode_name ) ) {

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'cannot add ' . $this->shortcode_name . ' shortcode - already exists' );
				}

				return false;
			}

        		add_shortcode( $this->shortcode_name, array( $this, 'do_shortcode' ) );

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( $this->shortcode_name . ' shortcode added' );
			}

			return true;
		}

		public function remove_shortcode() {

			if ( shortcode_exists( $this->shortcode_name ) ) {

				remove_shortcode( $this->shortcode_name );

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( $this->shortcode_name . ' shortcode removed' );
				}

				return true;

			}
			
			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( 'cannot remove ' . $this->shortcode_name . ' shortcode - does not exist' );
			}

			return false;
		}

		public function do_shortcode( $atts = array(), $content = null, $tag = '' ) { 

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			if ( empty( $atts[ 'id' ] ) ) {	// Nothing to do.
				return '<!-- ' . $this->shortcode_name . ' shortcode: no id attribute -->' . "\n\n";
			} elseif ( ! is_numeric( $atts[ 'id' ] ) ) {
				return '<!-- ' . $this->shortcode_name . ' shortcode: id attribute is not numeric -->' . "\n\n";
			}

			/**
			 * Get the term module array.
			 */
			$mod = $this->p->term->get_mod( $atts[ 'id' ] );

			$term_link  = get_term_link( $mod[ 'id' ], $mod[ 'tax_slug' ] );
			$term_title = $this->p->page->get_term_title( $mod[ 'id' ], $sep = '', $prefix = '' );

			/**
			 * Create the HTML.
			 */
			$html = '<div class="wpsso-faq" id="wpsso-faq-' . $mod[ 'id' ] . '">' . "\n";
		
			if ( ! isset( $atts[ 'schema' ] ) || ! empty( $atts[ 'schema' ] ) ) {
				$html = apply_filters( $this->p->lca . '_content_html_script_application_ld_json', $html, $mod );
			}

			if ( is_string( $term_link ) ) {
				$html .= '<h3><a href="' . $term_link . '">' . $term_title . '</a></h3>' . "\n";
			} else {
				$html .= '<h3>' . $term_title . '</h3>' . "\n";
			}

			$posts_args = array( 'orderby' => 'title', 'order'   => 'ASC' );
			$posts_mods = $mod[ 'obj' ]->get_posts_mods( $mod, $ppp = -1, $paged = null, $posts_args );

			foreach ( $posts_mods as $post_mod ) {

				/**
				 * Signal the question shortcode not to include schema markup since the faq shortcode may already
				 * include markup for all the questions.
				 */
				$html .= do_shortcode( '[' . WPSSOFAQ_QUESTION_SHORTCODE_NAME . ' id="' . $post_mod[ 'id' ] . '" schema="0"]' );
			}
			
			$html .= '</div><!-- .wpsso-faq -->' . "\n";

			return $html;
		}
	}
}
