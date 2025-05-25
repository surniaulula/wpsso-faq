<?php
/*
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl.txt
 * Copyright 2019-2025 Jean-Sebastien Morisset (https://wpsso.com/)
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

			add_action( 'wpsso_init_options', array( __CLASS__, 'register_question_post_type' ), WPSSOFAQ_FAQ_MENU_ORDER, 0 );

			add_action( 'wpsso_init_options', array( __CLASS__, 'register_faq_category_taxonomy' ), WPSSOFAQ_FAQ_MENU_ORDER, 0 );
			
			add_action( 'wpsso_init_options', array( __CLASS__, 'register_faq_tag_taxonomy' ), WPSSOFAQ_FAQ_MENU_ORDER, 0 );
		}

		/*
		 * Fires immediately after a new site is created.
		 */
		public function wpmu_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {

			switch_to_blog( $blog_id );

			$this->activate_plugin();

			restore_current_blog();
		}

		/*
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

		/*
		 * uninstall.php defines constants before calling network_uninstall().
		 */
		public static function network_uninstall() {

			$sitewide = true;

			/*
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

			/*
			 * Register plugin install, activation, update times.
			 */
			if ( class_exists( 'WpssoUtilReg' ) ) {

				$version = WpssoFaqConfig::get_version();

				WpssoUtilReg::update_ext_version( 'wpssofaq', $version );
			}

			self::register_question_post_type();

			self::register_faq_category_taxonomy();

			self::register_faq_tag_taxonomy();

			flush_rewrite_rules( $hard = false );	// Update only the 'rewrite_rules' option, not the .htaccess file.
		}

		private function deactivate_plugin() {

			unregister_post_type( WPSSOFAQ_QUESTION_POST_TYPE );

			unregister_taxonomy( WPSSOFAQ_FAQ_CATEGORY_TAXONOMY );
			
			unregister_taxonomy( WPSSOFAQ_FAQ_TAG_TAXONOMY );

			flush_rewrite_rules( $hard = false );	// Update only the 'rewrite_rules' option, not the .htaccess file.
		}

		private static function uninstall_plugin() {}

		public static function register_question_post_type() {

			$wpsso =& Wpsso::get_instance();

			$is_public = empty( $wpsso->options[ 'faq_public_disabled' ] ) ? true : false;

			$labels = array(
				'name'                     => _x( 'Questions', 'post type general name', 'wpsso-faq' ),
				'singular_name'            => _x( 'Question', 'post type singular name', 'wpsso-faq' ),
				'add_new'                  => __( 'Add Question', 'wpsso-faq' ),
				'add_new_item'             => __( 'Add Question', 'wpsso-faq' ),
				'edit_item'                => __( 'Edit Question', 'wpsso-faq' ),
				'new_item'                 => __( 'New Question', 'wpsso-faq' ),
				'view_item'                => __( 'View Question', 'wpsso-faq' ),
				'view_items'               => __( 'View Questions', 'wpsso-faq' ),
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
				'menu_name'                => _x( 'SSO FAQs', 'admin menu name', 'wpsso-faq' ),
				'filter_items_list'        => __( 'Filter questions', 'wpsso-faq' ),
				'items_list_navigation'    => __( 'Questions list navigation', 'wpsso-faq' ),
				'items_list'               => __( 'Questions list', 'wpsso-faq' ),
				'name_admin_bar'           => _x( 'Question', 'admin bar name', 'wpsso-faq' ),
				'item_published'	   => __( 'Question published.', 'wpsso-faq' ),
				'item_published_privately' => __( 'Question published privately.', 'wpsso-faq' ),
				'item_reverted_to_draft'   => __( 'Question reverted to draft.', 'wpsso-faq' ),
				'item_scheduled'           => __( 'Question scheduled.', 'wpsso-faq' ),
				'item_updated'             => __( 'Question updated.', 'wpsso-faq' ),
			);

			$supports = array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'trackbacks',
				'comments',
				'revisions',
				'page-attributes',	// Supports menu order.
			);

			$taxonomies = array();

			if ( empty( $wpsso->options[ 'faq_category_disabled' ] ) ) {
			
				$taxonomies[] = WPSSOFAQ_FAQ_CATEGORY_TAXONOMY;
			}

			if ( empty( $wpsso->options[ 'faq_tag_disabled' ] ) ) {

				$taxonomies[] = WPSSOFAQ_FAQ_TAG_TAXONOMY;
			}

			$args = array(
				'label'               => _x( 'Question', 'post type label', 'wpsso-faq' ),
				'labels'              => $labels,
				'description'         => _x( 'Question and Answer', 'post type description', 'wpsso-faq' ),
				'exclude_from_search' => false,	// Must be false for get_posts() queries.
				'public'              => $is_public,
				'publicly_queryable'  => $is_public,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'show_in_rest'        => true,
				'menu_position'       => WPSSOFAQ_FAQ_MENU_ORDER,
				'menu_icon'           => 'dashicons-editor-help',
				'capability_type'     => 'page',
				'hierarchical'        => false,
				'supports'            => $supports,
				'taxonomies'          => $taxonomies,
				'has_archive'         => 'faqs',
				'can_export'          => true,
			);

			register_post_type( WPSSOFAQ_QUESTION_POST_TYPE, $args );
		}

		public static function register_faq_category_taxonomy() {

			$wpsso =& Wpsso::get_instance();

			if ( ! empty( $wpsso->options[ 'faq_category_disabled' ] ) ) return;

			$is_public = empty( $wpsso->options[ 'faq_public_disabled' ] ) ? true : false;

			$labels = array(
				'name'                       => __( 'FAQ Categories', 'wpsso-faq' ),
				'singular_name'              => __( 'FAQ Category', 'wpsso-faq' ),
				'menu_name'                  => _x( 'FAQ Categories', 'admin menu name', 'wpsso-faq' ),
				'all_items'                  => __( 'All FAQ Categories', 'wpsso-faq' ),
				'edit_item'                  => __( 'Edit FAQ Category', 'wpsso-faq' ),
				'view_item'                  => __( 'View FAQ Category', 'wpsso-faq' ),
				'update_item'                => __( 'Update FAQ Category', 'wpsso-faq' ),
				'add_new_item'               => __( 'Add New FAQ Category', 'wpsso-faq' ),
				'new_item_name'              => __( 'New FAQ Category Name', 'wpsso-faq' ),
				'parent_item'                => __( 'Parent FAQ Category', 'wpsso-faq' ),
				'parent_item_colon'          => __( 'Parent FAQ Category:', 'wpsso-faq' ),
				'search_items'               => __( 'Search FAQ Categories', 'wpsso-faq' ),
				'popular_items'              => __( 'Popular FAQ Categories', 'wpsso-faq' ),
				'separate_items_with_commas' => __( 'Separate FAQ groups with commas', 'wpsso-faq' ),
				'add_or_remove_items'        => __( 'Add or remove FAQ groups', 'wpsso-faq' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'wpsso-faq' ),
				'not_found'                  => __( 'No FAQ groups found.', 'wpsso-faq' ),
				'back_to_items'              => __( '← Back to FAQ groups', 'wpsso-faq' ),
			);

			$args = array(
				'label'              => _x( 'FAQ Categories', 'taxonomy label', 'wpsso-faq' ),
				'labels'             => $labels,
				'public'             => $is_public,
				'publicly_queryable' => $is_public,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_admin_column'  => true,
				'show_in_quick_edit' => true,
				'show_in_rest'       => true,	// Show this taxonomy in the block editor.
				'show_tagcloud'      => false,
				'description'        => _x( 'FAQ Categories for Questions and Answers', 'taxonomy description', 'wpsso-faq' ),
				'hierarchical'       => true,
			);

			register_taxonomy( WPSSOFAQ_FAQ_CATEGORY_TAXONOMY, array( WPSSOFAQ_QUESTION_POST_TYPE ), $args );
		}

		public static function register_faq_tag_taxonomy() {

			$wpsso =& Wpsso::get_instance();

			if ( ! empty( $wpsso->options[ 'faq_tag_disabled' ] ) ) return;

			$is_public = empty( $wpsso->options[ 'faq_public_disabled' ] ) ? true : false;

			$labels = array(
				'name'                       => __( 'FAQ Tags', 'wpsso-faq' ),
				'singular_name'              => __( 'FAQ Tag', 'wpsso-faq' ),
				'menu_name'                  => _x( 'FAQ Tags', 'admin menu name', 'wpsso-faq' ),
				'all_items'                  => __( 'All FAQ Tags', 'wpsso-faq' ),
				'edit_item'                  => __( 'Edit FAQ Tag', 'wpsso-faq' ),
				'view_item'                  => __( 'View FAQ Tag', 'wpsso-faq' ),
				'update_item'                => __( 'Update FAQ Tag', 'wpsso-faq' ),
				'add_new_item'               => __( 'Add New FAQ Tag', 'wpsso-faq' ),
				'new_item_name'              => __( 'New FAQ Tag Name', 'wpsso-faq' ),
				'parent_item'                => __( 'Parent FAQ Tag', 'wpsso-faq' ),
				'parent_item_colon'          => __( 'Parent FAQ Tag:', 'wpsso-faq' ),
				'search_items'               => __( 'Search FAQ Tags', 'wpsso-faq' ),
				'popular_items'              => __( 'Popular FAQ Tags', 'wpsso-faq' ),
				'separate_items_with_commas' => __( 'Separate FAQ groups with commas', 'wpsso-faq' ),
				'add_or_remove_items'        => __( 'Add or remove FAQ groups', 'wpsso-faq' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'wpsso-faq' ),
				'not_found'                  => __( 'No FAQ groups found.', 'wpsso-faq' ),
				'back_to_items'              => __( '← Back to FAQ groups', 'wpsso-faq' ),
			);

			$args = array(
				'label'              => _x( 'FAQ Tags', 'taxonomy label', 'wpsso-faq' ),
				'labels'             => $labels,
				'public'             => $is_public,
				'publicly_queryable' => $is_public,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'show_in_nav_menus'  => true,
				'show_admin_column'  => true,
				'show_in_quick_edit' => true,
				'show_in_rest'       => true,	// Show this taxonomy in the block editor.
				'show_tagcloud'      => true,
				'description'        => _x( 'FAQ Tags for Questions and Answers', 'taxonomy description', 'wpsso-faq' ),
				'hierarchical'       => false,
			);

			register_taxonomy( WPSSOFAQ_FAQ_TAG_TAXONOMY, array( WPSSOFAQ_QUESTION_POST_TYPE ), $args );
		}
	}
}
