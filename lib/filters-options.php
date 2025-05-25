<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2015-2025 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {

	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqFiltersOptions' ) ) {

	class WpssoFaqFiltersOptions {

		private $p;	// Wpsso class object.
		private $a;	// WpssoFaq class object.

		/*
		 * Instantiated by WpssoFaqFilters->construct().
		 */
		public function __construct( &$plugin, &$addon ) {

			$this->p =& $plugin;
			$this->a =& $addon;

			if ( ! empty( $this->p->debug->enabled ) ) {

				$this->p->debug->mark();
			}

			$this->p->util->add_plugin_filters( $this, array(
				'save_settings_options' => 3,
			) );
		}

		/*
		 * The 'wpsso_save_settings_options' filter is applied by WpssoOptions->save_options(),
		 * WpssoAdmin->settings_sanitation(), and WpssoAdmin->save_site_settings().
		 *
		 * $opts is the new options to be saved. Wpsso->options and Wpsso->site_options are still the old options.
		 *
		 * $network is true if we're saving the multisite network settings.
		 *
		 * $is_option_upg is true when the option versions, not the plugin versions, have changed.
		 */
		public function filter_save_settings_options( array $opts, $network, $is_option_upg ) {

			if ( ! empty( $this->p->debug->enabled ) ) {

				$this->p->debug->mark();
			}

			if ( $network ) return $opts;	// Nothing to do.

			foreach ( array( 'faq_category_disabled', 'faq_tag_disabled' ) as $opt_key ) {

				if ( $this->p->options[ $opt_key ] != $opts[ $opt_key ] ) {

					flush_rewrite_rules( $hard = false );	// Update only the 'rewrite_rules' option, not the .htaccess file.

					break;
				}
			}

			return $opts;
		}
	}
}
