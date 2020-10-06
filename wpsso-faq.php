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
 * Version: 3.7.0-dev.1
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

if ( ! class_exists( 'SucomAddOn' ) ) {

	require_once dirname( __FILE__ ) . '/lib/abstracts/com/add-on.php';	// SucomAddOn class.
}

if ( ! class_exists( 'WpssoFaq' ) ) {

	class WpssoFaq extends SucomAddOn {

		/**
		 * Library class object variables.
		 */
		public $filters;	// WpssoFaqFilters class.
		public $post;		// WpssoFaqPost class.
		public $reg;		// WpssoFaqRegister class.
		public $style;		// WpssoFaqStyle class.
		public $term;		// WpssoFaqTerm class.

		/**
		 * Reference Variables (config, options, modules, etc.).
		 */
		protected $p;
		protected $ext   = 'wpssofaq';
		protected $p_ext = 'faq';
		protected $cf    = array();

		private static $instance = null;

		public function __construct() {

			require_once dirname( __FILE__ ) . '/lib/config.php';

			WpssoFaqConfig::set_constants( __FILE__ );

			WpssoFaqConfig::require_libs( __FILE__ );	// Includes the register.php class library.

			$this->cf =& WpssoFaqConfig::$cf;

			$this->reg = new WpssoFaqRegister();		// Activate, deactivate, uninstall hooks.

			/**
			 * WPSSO filter hooks.
			 */
			add_filter( 'wpsso_get_config', array( $this, 'get_config' ), 10, 1 );
			add_filter( 'wpsso_get_avail', array( $this, 'get_avail' ), 10, 1 );

			/**
			 * WPSSO action hooks.
			 */
			add_action( 'wpsso_init_textdomain', array( $this, 'init_textdomain' ), 10, 1 );
			add_action( 'wpsso_init_objects', array( $this, 'init_objects' ), 10, 0 );
			add_action( 'wpsso_init_plugin', array( $this, 'init_missing_requirements' ), 10, 2 );

			/**
			 * WordPress action hooks.
			 */
			add_action( 'all_admin_notices', array( $this, 'show_missing_requirements' ) );
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
