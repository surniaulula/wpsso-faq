<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2016-2019 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for...' );
}

if ( ! class_exists( 'WpssoFaqConfig' ) ) {

	class WpssoFaqConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssofaq' => array(			// Plugin acronym.
					'version'     => '1.0.1-rc.1',	// Plugin version.
					'opt_version' => '1',		// Increment when changing default option values.
					'short'       => 'WPSSO FAQ',	// Short plugin name.
					'name'        => 'WPSSO FAQ Manager',
					'desc'        => 'Manage FAQ categories with Question and Answer pages.',
					'slug'        => 'wpsso-faq',
					'base'        => 'wpsso-faq/wpsso-faq.php',
					'update_auth' => '',
					'text_domain' => 'wpsso-faq',
					'domain_path' => '/languages',
					'req'         => array(
						'short'       => 'WPSSO Core',
						'name'        => 'WPSSO Core',
						'min_version' => '5.5.1-rc.1',
					),
					'assets' => array(
						'icons' => array(
							'low'  => 'images/icon-128x128.png',
							'high' => 'images/icon-256x256.png',
						),
					),
					'lib' => array(
						'pro' => array(
						),
						'std' => array(
						),
					),
				),
			),
		);

		public static function get_version( $add_slug = false ) {

			$ext  = 'wpssofaq';
			$info =& self::$cf[ 'plugin' ][$ext];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function set_constants( $plugin_filepath ) { 

			if ( defined( 'WPSSOFAQ_VERSION' ) ) {	// Define constants only once.
				return;
			}

			define( 'WPSSOFAQ_FILEPATH', $plugin_filepath );						
			define( 'WPSSOFAQ_PLUGINBASE', self::$cf[ 'plugin' ][ 'wpssofaq' ][ 'base' ] );		// wpsso-faq/wpsso-faq.php
			define( 'WPSSOFAQ_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_filepath ) ) ) );
			define( 'WPSSOFAQ_PLUGINSLUG', self::$cf[ 'plugin' ][ 'wpssofaq' ][ 'slug' ] );		// wpsso-faq
			define( 'WPSSOFAQ_URLPATH', trailingslashit( plugins_url( '', $plugin_filepath ) ) );
			define( 'WPSSOFAQ_VERSION', self::$cf[ 'plugin' ][ 'wpssofaq' ][ 'version' ] );						
		}

		public static function require_libs( $plugin_filepath ) {

			require_once WPSSOFAQ_PLUGINDIR . 'lib/register.php';
			require_once WPSSOFAQ_PLUGINDIR . 'lib/filters.php';

			add_filter( 'wpssofaq_load_lib', array( 'WpssoFaqConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {

			if ( false === $ret && ! empty( $filespec ) ) {

				$filepath = WPSSOFAQ_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $filepath ) ) {

					require_once $filepath;

					if ( empty( $classname ) ) {
						return SucomUtil::sanitize_classname( 'wpssofaq' . $filespec, $allow_underscore = false );
					} else {
						return $classname;
					}
				}
			}

			return $ret;
		}
	}
}
