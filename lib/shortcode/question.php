<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2024 Jean-Sebastien Morisset (https://wpsso.com/)
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

			$atts = shortcode_atts( array(
				'__add_json'  => true,
				'id'          => 0,
				'heading'     => $this->p->options[ 'faq_question_heading' ],
				'title'       => null,
				'show_answer' => $this->p->options[ 'faq_answer_toggle' ] ? false : null,
			), $atts );

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log_arr( 'atts', $atts );
			}

			if ( empty( $atts[ 'id' ] ) ) {	// Nothing to do.

				return '<!-- ' . $this->shortcode_name . ' shortcode: no id attribute -->' . "\n\n";

			} elseif ( ! is_numeric( $atts[ 'id' ] ) ) {

				return '<!-- ' . $this->shortcode_name . ' shortcode: id attribute is not numeric -->' . "\n\n";
			}

			$post_id       = $atts[ 'id' ];
			$mod           = $this->p->post->get_mod( $post_id );
			$css_id        = 'wpsso-question-' . $post_id;
			$frag_anchor   = $this->p->util->get_fragment_anchor( $mod );	// Returns for example "#sso-post-123".
			$canonical_url = $this->p->util->get_canonical_url( $mod );
			$title_text    = empty( $atts[ 'title' ] ) ? get_the_title( $post_id ) : sanitize_text_field( $atts[ 'title' ] );

			/*
			 * Attach the post ID to the question so the post cache can be cleared when the question is updated.
			 *
			 * Use SucomUtilWP::get_post_object(), which is back-end compatible, instead of using "global $post".
			 */
			$post_obj = SucomUtilWP::get_post_object( $use_post = true );

			if ( ! empty( $post_obj->ID ) && $post_obj->ID !== $post_id ) {

				WpssoPost::add_attached( $post_id, $attach_name = 'post', $post_obj->ID );
			}

			switch ( $this->p->options[ 'faq_answer_format' ] ) {

				case 'content':

					$content = $this->p->page->get_the_content( $mod );

					break;

				default:

					if ( has_excerpt( $post_id ) ) {

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

			/*
			 * Create the HTML.
			 */
			$html = '<a name="' . trim( $frag_anchor, '#' ) . '"></a>' . "\n";

			$html .= '<div class="wpsso-question" id="' . $css_id . '">' . "\n";

			if ( wp_validate_boolean( $atts[ '__add_json' ] ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'adding schema json-ld markup for ' . $css_id );
				}

				$html .= $this->p->schema->get_mod_script_type_application_ld_json_html( $mod, 'wpsso-schema-question-' . $post_id );
			}

			$html .= '<' . esc_attr( $atts[ 'heading' ] ) . ' class="wpsso-question-title">';

			/*
			 * Show / hide answer when question title is clicked.
			 */
			if ( $this->p->options[ 'faq_answer_toggle' ] ) {

				$html .= '<a href="#" onClick="var el = document.getElementById( \'' . $css_id . '-content\' ); ' .
					'el.style.display === \'none\' ? el.style.display = \'block\' : el.style.display = \'none\'; return false;">' .
						$title_text . '</a>';

			} else {

				/*
				 * Link the title if we have a publicly accessible page.
				 */
				$html .= $mod[ 'is_public' ] ? '<a href="' . $canonical_url . '">' . $title_text . '</a>' : $title_text;
			}

			$html .= '</' . esc_attr( $atts[ 'heading' ] ) . '><!-- .wpsso-question-title -->' . "\n";

			$html .= '<div class="wpsso-question-content" id="' . $css_id . '-content"';

			/*
			 * Hide answer by default.
			 */
			if ( $this->p->options[ 'faq_answer_toggle' ] ) {

				if ( ! SucomUtil::get_bool( $atts[ 'show_answer' ] ) ) {	// Maybe convert string to boolean.

					$html .= ' style="display:none;"';
				}
			}

			$html .= '>' . "\n";

			$html .= '<p>' . $content . '</p>' . "\n";

			$html .= '</div><!-- .wpsso-question-content -->' . "\n";

			$html .= '</div><!-- .wpsso-question -->' . "\n";

			return $html;
		}

		/*
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
