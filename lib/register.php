<?php
/**
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2014-2020 Jean-Sebastien Morisset (https://wpsso.com/)
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'These aren\'t the droids you\'re looking for.' );
}

if ( ! class_exists( 'WpssoFaqRegister' ) ) {

	class WpssoFaqRegister {

		public function __construct() {

			register_activation_hook( WPSSOFAQ_FILEPATH, array( $this, 'network_activate' ) );

			register_deactivation_hook( WPSSOFAQ_FILEPATH, array( $this, 'network_deactivate' ) );

			if ( is_multisite() ) {

				add_action( 'wpmu_new_blog', array( $this, 'wpmu_new_blog' ), 10, 6 );

				add_action( 'wpmu_activate_blog', array( $this, 'wpmu_activate_blog' ), 10, 5 );
			}

			add_action( 'wpsso_init_options', array( $this, 'register_taxonomy_faq_category' ) );

			add_action( 'wpsso_init_options', array( $this, 'register_post_type_question' ) );
		}

		/**
		 * Fires immediately after a new site is created.
		 */
		public function wpmu_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

			switch_to_blog( $blog_id );

			$this->activate_plugin();

			restore_current_blog();
		}

		/**
		 * Fires immediately after a site is activated (not called when users and sites are created by a Super Admin).
		 */
		public function wpmu_activate_blog( $blog_id, $user_id, $password, $signup_title, $meta ) {

			switch_to_blog( $blog_id );

			$this->activate_plugin();

			restore_current_blog();
		}

		public function network_activate( $sitewide ) {

			self::do_multisite( $sitewide, array( $this, 'activate_plugin' ) );
		}

		public function network_deactivate( $sitewide ) {

			self::do_multisite( $sitewide, array( $this, 'deactivate_plugin' ) );
		}

		/**
		 * uninstall.php defines constants before calling network_uninstall().
		 */
		public static function network_uninstall() {

			$sitewide = true;

			/**
			 * Uninstall from the individual blogs first.
			 */
			self::do_multisite( $sitewide, array( __CLASS__, 'uninstall_plugin' ) );
		}

		private static function do_multisite( $sitewide, $method, $args = array() ) {

			if ( is_multisite() && $sitewide ) {

				global $wpdb;

				$db_query = 'SELECT blog_id FROM '.$wpdb->blogs;
				$blog_ids = $wpdb->get_col( $db_query );

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );

					call_user_func_array( $method, array( $args ) );
				}

				restore_current_blog();

			} else {
				call_user_func_array( $method, array( $args ) );
			}
		}

		private function activate_plugin() {

			if ( class_exists( 'Wpsso' ) ) {

				/**
				 * Register plugin install, activation, update times.
				 */
				if ( class_exists( 'WpssoUtilReg' ) ) {	// Since WPSSO v6.13.1.

					$version = WpssoFaqConfig::$cf[ 'plugin' ][ 'wpssofaq' ][ 'version' ];

					WpssoUtilReg::update_ext_version( 'wpssofaq', $version );
				}

				$this->register_taxonomy_faq_category();

				$this->register_post_type_question();

				flush_rewrite_rules();
			}
		}

		private function deactivate_plugin() {

			unregister_taxonomy( WPSSOFAQ_CATEGORY_TAXONOMY );

			unregister_post_type( WPSSOFAQ_QUESTION_POST_TYPE );

			flush_rewrite_rules();
		}

		private static function uninstall_plugin() {
		}

		public function register_taxonomy_faq_category() {

			$labels = array(
				'name'                       => __( 'Categories', 'wpsso-faq' ),
				'singular_name'              => __( 'Category', 'wpsso-faq' ),
				'menu_name'                  => _x( 'Categories', 'Admin menu name', 'wpsso-faq' ),
				'all_items'                  => __( 'All Categories', 'wpsso-faq' ),
				'edit_item'                  => __( 'Edit Category', 'wpsso-faq' ),
				'view_item'                  => __( 'View Category', 'wpsso-faq' ),
				'update_item'                => __( 'Update Category', 'wpsso-faq' ),
				'add_new_item'               => __( 'Add New Category', 'wpsso-faq' ),
				'new_item_name'              => __( 'New Category Name', 'wpsso-faq' ),
				'parent_item'                => __( 'Parent Category', 'wpsso-faq' ),
				'parent_item_colon'          => __( 'Parent Category:', 'wpsso-faq' ),
				'search_items'               => __( 'Search Categories', 'wpsso-faq' ),
				'popular_items'              => __( 'Popular Categories', 'wpsso-faq' ),
				'separate_items_with_commas' => __( 'Separate categories with commas', 'wpsso-faq' ),
				'add_or_remove_items'        => __( 'Add or remove categories', 'wpsso-faq' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'wpsso-faq' ),
				'not_found'                  => __( 'Not categories found.', 'wpsso-faq' ),
				'back_to_items'              => __( '← Back to categories', 'wpsso-faq' ),
			);

			$args = array(
				'label'              => _x( 'Categories', 'Taxonomy label', 'wpsso-faq' ),
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_in_rest'       => true,
				'show_tagcloud'      => false,
				'show_in_quick_edit' => true,
				'show_admin_column'  => true,
				'description'        => _x( 'Categories for Questions', 'Taxonomy description', 'wpsso-faq' ),
				'hierarchical'       => true,
			);

			register_taxonomy( WPSSOFAQ_CATEGORY_TAXONOMY, array( WPSSOFAQ_QUESTION_POST_TYPE ), $args );
		
		}

		public function register_post_type_question() {

			$labels = array(
				'name'                     => __( 'Questions', 'Post type general name', 'wpsso-faq' ),
				'singular_name'            => __( 'Question', 'Post type singular name', 'wpsso-faq' ),
				'add_new'                  => __( 'Add New', 'wpsso-faq' ),
				'add_new_item'             => __( 'Add New Question', 'wpsso-faq' ),
				'edit_item'                => __( 'Edit Question', 'wpsso-faq' ),
				'new_item'                 => __( 'New Question', 'wpsso-faq' ),
				'view_item'                => __( 'View Question', 'wpsso-faq' ),
				'view_items'               => __( 'View FAQs', 'wpsso-faq' ),
				'search_items'             => __( 'Search Questions', 'wpsso-faq' ),
				'not_found'                => __( 'No questions found', 'wpsso-faq' ),
				'not_found_in_trash'       => __( 'No questions found in Trash', 'wpsso-faq' ),
				'parent_item_colon'        => __( 'Parent Question:', 'wpsso-faq' ),
				'all_items'                => __( 'All Questions', 'wpsso-faq' ),
				'archives'                 => __( 'Question Archives', 'wpsso-faq' ),
				'attributes'               => __( 'Question Attributes', 'wpsso-faq' ),
				'insert_into_item'         => __( 'Insert into answer', 'wpsso-faq' ),
				'uploaded_to_this_item'    => __( 'Uploaded to this question', 'wpsso-faq' ),
				'featured_image'           => __( 'Question Image', 'wpsso-faq' ),
				'set_featured_image'       => __( 'Set question image', 'wpsso-faq' ),
				'remove_featured_image'    => __( 'Remove question image', 'wpsso-faq' ),
				'use_featured_image'       => __( 'Use as question image', 'wpsso-faq' ),
				'menu_name'                => _x( 'FAQs', 'Admin menu name', 'wpsso-faq' ),
				'filter_items_list'        => __( 'Filter questions', 'wpsso-faq' ),
				'items_list_navigation'    => __( 'Questions list navigation', 'wpsso-faq' ),
				'items_list'               => __( 'Questions list', 'wpsso-faq' ),
				'name_admin_bar'           => _x( 'Question', 'Admin bar name', 'wpsso-faq' ),
				'item_published'	   => __( 'Question published.', 'wpsso-faq' ),
				'item_published_privately' => __( 'Question published privately.', 'wpsso-faq' ),
				'item_reverted_to_draft'   => __( 'Question reverted to draft.', 'wpsso-faq' ),
				'item_scheduled'           => __( 'Question scheduled.', 'wpsso-faq' ),
				'item_updated'             => __( 'Question updated.', 'wpsso-faq' ),
			);

			$args = array(
				'label'                 => _x( 'Question', 'Post type label', 'wpsso-faq' ),
				'labels'                => $labels,
				'description'           => _x( 'Question and Answer', 'Post type description', 'wpsso-faq' ),
				'public'                => true,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'show_ui'               => true,
				'show_in_nav_menus'     => true,
				'show_in_menu'          => true,
				'show_in_admin_bar'     => true,
				'menu_position'         => 20,
				'menu_icon'             => 'dashicons-editor-help',
				'capability_type'       => 'page',
				'hierarchical'          => false,
				'supports'              => array(
					'title',
					'editor',
					'author',
					'thumbnail',
					'excerpt',
					'trackbacks',
					'comments',
					'revisions',
					'page-attributes',
				),
				'taxonomies'            => array( WPSSOFAQ_CATEGORY_TAXONOMY ),
				'has_archive'           => 'faqs',
				'can_export'            => true,
				'show_in_rest'          => true,
			);

			register_post_type( WPSSOFAQ_QUESTION_POST_TYPE, $args );
		}
	}
}
