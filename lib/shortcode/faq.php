<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2022 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqShortcodeFaq' ) ) {

	class WpssoFaqShortcodeFaq {

		private $p;	// Wpsso class object.

		private $shortcode_name = 'faq';	// Default shortcode name.

		public function __construct( &$plugin ) {

			$this->p =& $plugin;

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			$this->shortcode_name = WPSSOFAQ_FAQ_SHORTCODE_NAME;

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

			$atts = shortcode_atts( array(	// Since WP v2.5.
				'__add_json'       => true,
				'id'               => 0,
				'heading'          => $this->p->options[ 'faq_heading' ],
				'order'            => 'ASC',
				'orderby'          => 'title',
				'title'            => null,
				'show_answer_ids'  => null,
				'show_answer_nums' => null,
			), $atts );

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log_arr( 'atts', $atts );
			}

			if ( empty( $atts[ 'id' ] ) ) {	// Nothing to do.

				return '<!-- ' . $this->shortcode_name . ' shortcode: no id attribute -->' . "\n\n";

			} elseif ( ! is_numeric( $atts[ 'id' ] ) ) {

				return '<!-- ' . $this->shortcode_name . ' shortcode: id attribute is not numeric -->' . "\n\n";
			}

			if ( null !== $atts[ 'show_answer_ids' ] ) {

				$atts[ 'show_answer_ids' ] = array_map( 'trim', explode( ',', $atts[ 'show_answer_ids' ] ) );
			}

			if ( null !== $atts[ 'show_answer_nums' ] ) {

				$atts[ 'show_answer_nums' ] = array_map( 'trim', explode( ',', $atts[ 'show_answer_nums' ] ) );
			}

			$term_id       = $atts[ 'id' ];
			$mod           = $this->p->term->get_mod( $term_id );
			$css_id        = 'wpsso-faq-' . $term_id;
			$frag_anchor   = WpssoUtil::get_fragment_anchor( $mod );	// Returns for example "#sso-term-123-tax-faq-category".
			$canonical_url = $this->p->util->get_canonical_url( $mod );
			$title_text    = empty( $atts[ 'title' ] ) ?
				$this->p->page->get_term_title( $term_id, $title_sep = false ) :
					sanitize_text_field( $atts[ 'title' ] );

			$mod[ 'posts_args' ] = array(
				'order'          => $atts[ 'order' ],
				'orderby'        => $atts[ 'orderby' ],
				'paged'          => false,	// Just in case.
				'posts_per_page' => -1,		// Just in case.
			);

			/*
			 * Create the HTML.
			 */
			$html = '<a name="' . trim( $frag_anchor, '#' ) . '"></a>' . "\n";

			$html .= '<div class="wpsso-faq" id="' . $css_id . '">' . "\n";

			if ( wp_validate_boolean( $atts[ '__add_json' ] ) ) {

				if ( $this->p->debug->enabled ) {

					$this->p->debug->log( 'adding schema json-ld markup for ' . $css_id );
				}

				$html .= $this->p->schema->get_mod_script_type_application_ld_json_html( $mod, 'wpsso-schema-json-faq-' . $term_id );
			}

			$html .= '<' . esc_attr( $atts[ 'heading' ] ) . ' class="wpsso-faq-title">';

			/*
			 * Only link the title if we have a publicly accessible page.
			 */
			if ( $mod[ 'is_public' ] ) {	// Since WPSSO Core v7.0.0.

				$html .= '<a href="' . $canonical_url . '">' . $title_text . '</a>';

			} else {

				$html .= $title_text;
			}

			$html .= '</' . esc_attr( $atts[ 'heading' ] ) . '><!-- .wpsso-faq-title -->' . "\n";

			$posts_mods = $this->p->page->get_posts_mods( $mod );

			if ( $this->p->debug->enabled ) {

				$this->p->debug->log( 'found ' . count( $posts_mods ) . ' post mods' );
			}

			foreach ( $posts_mods as $num => $post_mod ) {

				/*
				 * Since the faq shortcode already includes Schema markup for all the questions, signal the
				 * question shortcode not to include the Schema markup.
				 */
				$question_atts = '__add_json="0" id="' . $post_mod[ 'id' ] . '"';

				if ( ( null !== $atts[ 'show_answer_nums' ] && in_array( $num + 1, $atts[ 'show_answer_nums' ] ) ) ||
					null !== $atts[ 'show_answer_ids' ] && in_array( $post_mod[ 'id' ], $atts[ 'show_answer_ids' ] ) ) {

					$question_atts .= ' show_answer="true"';
				}

				$html .= do_shortcode( '[' . WPSSOFAQ_QUESTION_SHORTCODE_NAME . ' ' . $question_atts . ']' );
			}

			$html .= '</div><!-- .wpsso-faq -->' . "\n";

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
