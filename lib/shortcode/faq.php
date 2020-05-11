<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2020 Jean-Sebastien Morisset (https://wpsso.com/)
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

			$atts = shortcode_atts( array(	// Since WP v2.5.
				'__include_schema' => true,	// Apply the 'wpsso_content_html_script_application_ld_json' filter.
				'id'               => 0,
				'order'            => 'ASC',
				'orderby'          => 'title',
			), $atts );

			if ( empty( $atts[ 'id' ] ) ) {	// Nothing to do.
				return '<!-- ' . $this->shortcode_name . ' shortcode: no id attribute -->' . "\n\n";
			} elseif ( ! is_numeric( $atts[ 'id' ] ) ) {
				return '<!-- ' . $this->shortcode_name . ' shortcode: id attribute is not numeric -->' . "\n\n";
			}

			/**
			 * Get the term module array.
			 */
			$mod = $this->p->term->get_mod( $atts[ 'id' ] );

			$css_id = 'wpsso-faq-' . $mod[ 'id' ];

			$frag_anchor = WpssoUtil::get_fragment_anchor( $mod );	// Returns for example "#sso-term-123-tax-faq-category".

			$canonical_url = $this->p->util->get_canonical_url( $mod );

			$title_text = $this->p->page->get_term_title( $mod[ 'id' ], $sep = false );

			/**
			 * Create the HTML.
			 */
			$html = '<a name="' . trim( $frag_anchor, '#' ) . '"></a>' . "\n";

			$html .= '<div class="wpsso-faq" id="' . $css_id . '">' . "\n";
		
			if ( wp_validate_boolean( $atts[ '__include_schema' ] ) ) {

				if ( $this->p->debug->enabled ) {
					$this->p->debug->log( 'adding schema markup for ' . $css_id );
				}

				$html .= apply_filters( 'wpsso_content_html_script_application_ld_json', '', $mod );
			}

			$html .= '<h3 class="wpsso-faq-title">';

			/**
			 * Only link the title if we have a publicly accessible page.
			 */
			if ( $mod[ 'is_public' ] ) {	// Since WPSSO Core v7.0.0.

				$html .= '<a href="' . $canonical_url . '">' . $title_text . '</a>';

			} else {
				$html .= $title_text;
			}

			$html .= '</h3><!-- .wpsso-faq-title -->' . "\n";

			$posts_args = array( 'order' => $atts[ 'order' ], 'orderby' => $atts[ 'orderby' ] );

			$posts_mods = $mod[ 'obj' ]->get_posts_mods( $mod, $ppp = -1, $paged = null, $posts_args );

			foreach ( $posts_mods as $post_mod ) {

				/**
				 * Since the faq shortcode already includes Schema markup for all the questions, signal the
				 * question shortcode not to include the Schema markup.
				 */
				$html .= do_shortcode( '[' . WPSSOFAQ_QUESTION_SHORTCODE_NAME . ' id="' . $post_mod[ 'id' ] . '" __include_schema="0"]' );
			}
			
			$html .= '</div><!-- .wpsso-faq -->' . "\n";

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
