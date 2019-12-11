<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2016-2019 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqConfig' ) ) {

	class WpssoFaqConfig {

		public static $cf = array(
			'plugin' => array(
				'wpssofaq' => array(			// Plugin acronym.
					'version'     => '2.0.0-rc.1',	// Plugin version.
					'opt_version' => '3',		// Increment when changing default option values.
					'short'       => 'WPSSO FAQ',	// Short plugin name.
					'name'        => 'WPSSO FAQ Manager',
					'desc'        => 'Manage FAQ categories with Question and Answer pages.',
					'slug'        => 'wpsso-faq',
					'base'        => 'wpsso-faq/wpsso-faq.php',
					'update_auth' => '',		// No premium version.
					'text_domain' => 'wpsso-faq',
					'domain_path' => '/languages',
					'req'         => array(
						'short'       => 'WPSSO Core',
						'name'        => 'WPSSO Core',
						'min_version' => '6.16.0-rc.1',
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
						'shortcode' => array(
							'faq'      => 'FAQ Shortcode',
							'question' => 'Question Shortcode',
						),
					),
				),
			),
		);

		public static function get_version( $add_slug = false ) {

			$info =& self::$cf[ 'plugin' ][ 'wpssofaq' ];

			return $add_slug ? $info[ 'slug' ] . '-' . $info[ 'version' ] : $info[ 'version' ];
		}

		public static function set_constants( $plugin_file_path ) { 

			if ( defined( 'WPSSOFAQ_VERSION' ) ) {	// Define constants only once.
				return;
			}

			$info =& self::$cf[ 'plugin' ][ 'wpssofaq' ];

			/**
			 * Define fixed constants.
			 */
			define( 'WPSSOFAQ_FILEPATH', $plugin_file_path );						
			define( 'WPSSOFAQ_PLUGINBASE', $info[ 'base' ] );	// Example: wpsso-faq/wpsso-faq.php.
			define( 'WPSSOFAQ_PLUGINDIR', trailingslashit( realpath( dirname( $plugin_file_path ) ) ) );
			define( 'WPSSOFAQ_PLUGINSLUG', $info[ 'slug' ] );	// Example: wpsso-faq.
			define( 'WPSSOFAQ_URLPATH', trailingslashit( plugins_url( '', $plugin_file_path ) ) );
			define( 'WPSSOFAQ_VERSION', $info[ 'version' ] );						

			define( 'WPSSOFAQ_CATEGORY_TAXONOMY', 'faq_category' );
			define( 'WPSSOFAQ_QUESTION_POST_TYPE', 'question' );

			/**
			 * Define variable constants.
			 */
			self::set_variable_constants();
		}

		public static function set_variable_constants( $var_const = null ) {

			if ( null === $var_const ) {
				$var_const = self::get_variable_constants();
			}

			/**
			 * Define the variable constants, if not already defined.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( ! defined( $name ) ) {
					define( $name, $value );
				}
			}
		}

		public static function get_variable_constants() { 

			$var_const = array();

			$var_const[ 'WPSSOFAQ_FAQ_SHORTCODE_NAME' ]      = 'faq';
			$var_const[ 'WPSSOFAQ_QUESTION_SHORTCODE_NAME' ] = 'question';

			/**
			 * Maybe override the default constant value with a pre-defined constant value.
			 */
			foreach ( $var_const as $name => $value ) {

				if ( defined( $name ) ) {
					$var_const[$name] = constant( $name );
				}
			}

			return $var_const;
		}

		public static function require_libs( $plugin_file_path ) {

			require_once WPSSOFAQ_PLUGINDIR . 'lib/filters.php';
			require_once WPSSOFAQ_PLUGINDIR . 'lib/post.php';
			require_once WPSSOFAQ_PLUGINDIR . 'lib/register.php';
			require_once WPSSOFAQ_PLUGINDIR . 'lib/style.php';
			require_once WPSSOFAQ_PLUGINDIR . 'lib/term.php';

			add_filter( 'wpssofaq_load_lib', array( 'WpssoFaqConfig', 'load_lib' ), 10, 3 );
		}

		public static function load_lib( $ret = false, $filespec = '', $classname = '' ) {

			if ( false === $ret && ! empty( $filespec ) ) {

				$file_path = WPSSOFAQ_PLUGINDIR . 'lib/' . $filespec . '.php';

				if ( file_exists( $file_path ) ) {

					require_once $file_path;

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
