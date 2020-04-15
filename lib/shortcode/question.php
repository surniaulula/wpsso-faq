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
				$this->p->debug->log( $atts );
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

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( $mod[ 'name' ] . ' ID ' . $mod[ 'id' ] . ' is ' .
					( $mod[ 'is_public' ] ? 'public' : 'not public' ) );
			}

			$css_id = 'wpsso-question-' . $mod[ 'id' ];

			if ( $mod[ 'is_public' ] ) {

				$canonical_url = $this->p->util->get_canonical_url( $mod );

			} else {

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'getting canonical URL relative to current webpage' );
				}

				$canonical_url = WpssoUtil::add_query_frag( $this->p->util->get_canonical_url(), $css_id );
			}

			if ( $this->p->debug->enabled ) {
				$this->p->debug->log( 'canonical URL is ' . $canonical_url );
			}

			$title_text = get_the_title( $mod[ 'id' ] );

			$answer_text = isset( $this->p->options[ 'faq_answer_text_fmt' ] ) ?
				$this->p->options[ 'faq_answer_text_fmt' ] : 'excerpt';

			switch ( $answer_text ) {

				case 'content':

					$content = $this->p->page->get_the_content( $mod );

					break;

				case 'excerpt':
				default:

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

					break;
			}

			/**
			 * Create the HTML.
			 */
			$html = '<a name="' . $css_id . '"></a>' . "\n";	// Anchor.

			$html .= '<div class="wpsso-question" id="' . $css_id. '">' . "\n";

			if ( ! isset( $atts[ 'schema' ] ) || ! empty( $atts[ 'schema' ] ) ) {

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'adding schema markup for ' . $css_id );
				}

				$html .= apply_filters( $this->p->lca . '_content_html_script_application_ld_json', '', $mod, $canonical_url );
			}

			$html .= '<h4 class="wpsso-question-title">';

			/**
			 * Show / hide answer when question title is clicked.
			 */
			if ( empty( $this->p->options[ 'faq_answer_toggle' ] ) ) {
			
				/**
				 * Only link the title if we have a publicly accessible page.
				 */
				if ( $mod[ 'is_public' ] ) {
					$html .= '<a href="' . $canonical_url . '">' . $title_text . '</a>';
				} else {
					$html .= $title_text;
				}

			} else {
				$html .= '<a onClick="var el = document.getElementById( \'' . $css_id . '-content\' ); el.style.display === \'none\' ? el.style.display = \'block\' : el.style.display = \'none\';">' . $title_text . '</a>';

			}

			$html .= '</h4><!-- .wpsso-question-title -->' . "\n";

			$html .= '<div class="wpsso-question-content" id="' . $css_id . '-content"';

			/**
			 * Hide answer by default.
			 */
			if ( ! empty( $this->p->options[ 'faq_answer_toggle' ] ) ) {
				$html .= ' style="display:none;"';
			}

			$html .= '>' . "\n";

			$html .= '<p>' . $content . '</p>' . "\n";

			$html .= '</div><!-- .wpsso-question-content -->' . "\n";

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
