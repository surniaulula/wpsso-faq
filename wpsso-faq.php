<?php
/**
 * Plugin Name: WPSSO FAQ Manager
 * Plugin Slug: wpsso-faq
 * Text Domain: wpsso-faq
 * Domain Path: /languages
 * Plugin URI: https://wpsso.com/extend/plugins/wpsso-faq/
 * Assets URI: https://surniaulula.github.io/wpsso-faq/assets/
 * Author: JS Morisset
 * Author URI: https://wpsso.com/
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Description: Create FAQ and Question / Answer Pages with Optional Shortcodes to Include FAQs and Questions in your Content.
 * Requires PHP: 5.6
 * Requires At Least: 4.4
 * Tested Up To: 5.5.1
 * Version: 3.7.0-dev.5
 * 
 * Version Numbering: {major}.{minor}.{bugfix}[-{stage}.{level}]
 *
 *      {major}         Major structural code changes / re-writes or incompatible API changes.
 *      {minor}         New functionality was added or improved in a backwards-compatible manner.
 *      {bugfix}        Backwards-compatible bug fixes or small improvements.
 *      {stage}.{level} Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).
 * 
 * Copyright 2019-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstracts/add-on.php';	// WpssoAddOn class.
}

if ( ! class_exists( 'WpssoFaq' ) ) {

	class WpssoFaq extends WpssoAddOn {

		public $filters;	// WpssoFaqFilters class.
		public $post;		// WpssoFaqPost class.
		public $style;		// WpssoFaqStyle class.
		public $term;		// WpssoFaqTerm class.

		protected $p;

		private static $instance = null;

		public function __construct() {

			parent::__construct( __FILE__, __CLASS__ );
		}

		public static function &get_instance() {

			if ( null === self::$instance ) {

				self::$instance = new self;
			}

			return self::$instance;
		}

		public function init_textdomain( $debug_enabled = false ) {

			static $local_cache = null;

			if ( null === $local_cache || $debug_enabled ) {

				$local_cache = 'wpsso-faq';

				load_plugin_textdomain( 'wpsso-faq', false, 'wpsso-faq/languages/' );
			}

			return $local_cache;
		}

		public function init_objects() {

			$this->p =& Wpsso::get_instance();

			if ( $this->p->debug->enabled ) {

				$this->p->debug->mark();
			}

			if ( $this->get_missing_requirements() ) {	// Returns false or an array of missing requirements.

				return;	// Stop here.
			}

			$this->filters = new WpssoFaqFilters( $this->p );
			$this->post    = new WpssoFaqPost( $this->p );
			$this->style   = new WpssoFaqStyle( $this->p );
			$this->term    = new WpssoFaqTerm( $this->p );
		}
	}

        global $wpssofaq;

	$wpssofaq =& WpssoFaq::get_instance();
}
