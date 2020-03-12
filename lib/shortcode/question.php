<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqShortcodeQuestion' ) ) {

	class WpssoFaqShortcodeQuestion {

		private $p;
		private $shortcode_name = 'question';	// Default shortcode name.

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {
				$this->p->debug->mark();
			}

			$this->shortcode_name = WPSSOFAQ_QUESTION_SHORTCODE_NAME;

			$this->add_shortcode();

			$this->p->util->add_plugin_filters( $this, array(
				'do_shortcode' => 1,
			) );
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
			 * Get the post module array.
			 */
			$mod = $this->p->post->get_mod( $atts[ 'id' ] );

			$post_url = get_permalink( $mod[ 'id' ] );

			$title_text = get_the_title( $mod[ 'id' ] );

			if ( has_excerpt( $mod[ 'id' ] ) ) {

				$content = $this->p->page->get_the_excerpt( $mod );

			} else {

				$content = $this->p->page->get_the_content( $mod );

				/* translators: Maximum number of words used in a post excerpt. */
				$excerpt_length = intval( _x( '55', 'excerpt_length' ) );
				$excerpt_length = (int) apply_filters( 'excerpt_length', $excerpt_length );

				$excerpt_more = apply_filters( 'excerpt_more', ' ' . '[&hellip;]' );

				$content = wp_trim_words( $content, $excerpt_length, $excerpt_more );
			}

			/**
			 * Create the HTML.
			 */
			$html = '<div class="wpsso-question" id="wpsso-question-' . $mod[ 'id' ] . '">' . "\n";

			if ( ! isset( $atts[ 'schema' ] ) || ! empty( $atts[ 'schema' ] ) ) {
				$html = apply_filters( $this->p->lca . '_content_html_script_application_ld_json', $html, $mod );
			}

			$html .= '<div class="wpsso-question-title">' . "\n";
			$html .= '<a href="' . $post_url . '">' . $title_text . '</a>' . "\n";
			$html .= '</div><!-- .wpsso-question-title -->' . "\n";
			$html .= '<div class="wpsso-question-content">' . "\n";
			$html .= '<p>' . $content . '</p>' . "\n";
			$html .= '</div><!-- .wpsso-question-excerpt -->' . "\n";
			$html .= '</div><!-- .wpsso-question -->' . "\n";

			return $html;
		}

		/**
		 * When the content filter is disabled, fallback and apply our own shortcode filter.
		 */
		public function filter_do_shortcode( $content ) {

			if ( false !== strpos( $content, '[' . $this->shortcode_name ) ) {
				$content = SucomUtilWP::do_shortcode_names( array( $this->shortcode_name ), $content );
			}

			return $content;
		}
	}
}
