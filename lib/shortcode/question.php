<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqShortcodeQuestion' ) ) {

	class WpssoFaqShortcodeQuestion {

		private $p;	// Wpsso class object.

		private $shortcode_name = 'question';	// Default shortcode name.

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->shortcode_name = WPSSOFAQ_QUESTION_SHORTCODE_NAME;

			$this->add_shortcode();

			$this->p->util->add_plugin_filters( $this, array(
				'do_shortcode' => 1,	// In cases where the content filter is disabled.
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

			$atts = shortcode_atts( array(	// Since WP v2.5.
				'__include_schema' => true,	// Apply the 'wpsso_content_html_script_application_ld_json' filter.
				'id'               => 0,
			), $atts );

			if ( empty( $atts[ 'id' ] ) ) {	// Nothing to do.

				return '<!-- ' . $this->shortcode_name . ' shortcode: no id attribute -->' . "\n\n";

			} elseif ( ! is_numeric( $atts[ 'id' ] ) ) {

				return '<!-- ' . $this->shortcode_name . ' shortcode: id attribute is not numeric -->' . "\n\n";
			}

			$question_post_id = $atts[ 'id' ];

			/**
			 * Attach the post id to the question so the post cache can be cleared when the question is updated.
			 */
			global $post;

			if ( $post->ID && $post->ID !== $question_post_id ) {

				WpssoPost::add_attached( $question_post_id, $attach_name = 'post', $post->ID );
			}

			/**
			 * Get the post module array.
			 */
			$mod = $this->p->post->get_mod( $question_post_id );

			$css_id = 'wpsso-question-' . $question_post_id;

			$frag_anchor = WpssoUtil::get_fragment_anchor( $mod );	// Returns for example "#sso-post-123".

			$canonical_url = $this->p->util->get_canonical_url( $mod );

			$title_text = get_the_title( $question_post_id );

			switch ( $this->p->options[ 'faq_answer_format' ] ) {

				case 'content':

					$content = $this->p->page->get_the_content( $mod );

					break;

				case 'excerpt':
				default:

					if ( has_excerpt( $question_post_id ) ) {

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
			$html = '<a name="' . trim( $frag_anchor, '#' ) . '"></a>' . "\n";

			$html .= '<div class="wpsso-question" id="' . $css_id. '">' . "\n";

			if ( wp_validate_boolean( $atts[ '__include_schema' ] ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'adding schema markup for ' . $css_id );
				}

				$html .= apply_filters( 'wpsso_content_html_script_application_ld_json', '', $mod );
			}

			$html .= '<h4 class="wpsso-question-title">';

			/**
			 * Show / hide answer when question title is clicked.
			 */
			if ( ! empty( $this->p->options[ 'faq_answer_toggle' ] ) ) {

				$html .= '<a onClick="var el = document.getElementById( \'' . $css_id . '-content\' ); ' .
					'el.style.display === \'none\' ? el.style.display = \'block\' : el.style.display = \'none\';">' .
						$title_text . '</a>';

			} else {

				/**
				 * Only link the title if we have a publicly accessible page.
				 */
				if ( $mod[ 'is_public' ] ) {	// Since WPSSO Core v7.0.0.

					$html .= '<a href="' . $canonical_url . '">' . $title_text . '</a>';

				} else {
					$html .= $title_text;
				}
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
