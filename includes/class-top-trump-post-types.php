<?php
/**
 * Top Trumps Class Post Types.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Top_Trumps_Game_Post_Types {

 	public function __construct(){
 		add_action( 'init', array( $this, 'create_post_types' ), 0 );
 	}

	/**
	 * create_post_types function.
	 *
	 * @access public
	 * @return void
	 */
	public function create_post_types(){
		if ( post_type_exists( 'top_trumps' ) )
			return; 

		// Register Custom Taxonomy
		$labels = array(
			'name'                       => _x( 'Top Trumps Categories', 'Taxonomy General Name', 'top_trumps_game_text' ),
			'singular_name'              => _x( 'Top Trumps Category', 'Taxonomy Singular Name', 'top_trumps_game_text' ),
			'menu_name'                  => __( 'Top Trump Categories', 'top_trumps_game_text' ),
			'all_items'                  => __( 'All Categories', 'top_trumps_game_text' ),
			'parent_item'                => __( 'Parent Category', 'top_trumps_game_text' ),
			'parent_item_colon'          => __( 'Parent Category:', 'top_trumps_game_text' ),
			'new_item_name'              => __( 'New Category', 'top_trumps_game_text' ),
			'add_new_item'               => __( 'Add New Category', 'top_trumps_game_text' ),
			'edit_item'                  => __( 'Edit Category', 'top_trumps_game_text' ),
			'update_item'                => __( 'Update Category', 'top_trumps_game_text' ),
			'view_item'                  => __( 'View Category', 'top_trumps_game_text' ),
			'separate_items_with_commas' => __( 'Separate categories with commas', 'top_trumps_game_text' ),
			'add_or_remove_items'        => __( 'Add or remove categories', 'top_trumps_game_text' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'top_trumps_game_text' ),
			'popular_items'              => __( 'Popular Categories', 'top_trumps_game_text' ),
			'search_items'               => __( 'Search Categories', 'top_trumps_game_text' ),
			'not_found'                  => __( 'Not Found', 'top_trumps_game_text' ),
			'no_terms'                   => __( 'No categories', 'top_trumps_game_text' ),
			'items_list'                 => __( 'Categories list', 'top_trumps_game_text' ),
			'items_list_navigation'      => __( 'Categories list navigation', 'top_trumps_game_text' ),
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => true,
			'show_ui'                    => true,
			'show_admin_column'          => true,
			'show_in_nav_menus'          => true,
			'show_tagcloud'              => true,
		);
		register_taxonomy( 'top_trumps_category', array( 'top_trumps' ), apply_filters( 'register_taxonomy_top_trumps_category_args', $args ) );

		// Register Custom Post Type
		$labels = array(
			'name'                  => _x( 'Top Trumps', 'Post Type General Name', 'top_trumps_game_text' ),
			'singular_name'         => _x( 'Top Trump', 'Post Type Singular Name', 'top_trumps_game_text' ),
			'menu_name'             => __( 'Top Trumps', 'top_trumps_game_text' ),
			'name_admin_bar'        => __( 'Top Trump', 'top_trumps_game_text' ),
			'archives'              => __( 'Top Trump Archives', 'top_trumps_game_text' ),
			'attributes'            => __( 'Top Trump Attributes', 'top_trumps_game_text' ),
			'parent_item_colon'     => __( 'Parent Top Trump:', 'top_trumps_game_text' ),
			'all_items'             => __( 'All Top Trumps', 'top_trumps_game_text' ),
			'add_new_item'          => __( 'Add New Top Trump', 'top_trumps_game_text' ),
			'add_new'               => __( 'Add New', 'top_trumps_game_text' ),
			'new_item'              => __( 'New Top Trump', 'top_trumps_game_text' ),
			'edit_item'             => __( 'Edit Top Trump', 'top_trumps_game_text' ),
			'update_item'           => __( 'Update Top Trump', 'top_trumps_game_text' ),
			'view_item'             => __( 'View Top Trump', 'top_trumps_game_text' ),
			'view_items'            => __( 'View Top Trumps', 'top_trumps_game_text' ),
			'search_items'          => __( 'Search Top Trump', 'top_trumps_game_text' ),
			'not_found'             => __( 'Not found', 'top_trumps_game_text' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'top_trumps_game_text' ),
			'featured_image'        => __( 'Featured Image', 'top_trumps_game_text' ),
			'set_featured_image'    => __( 'Set featured image', 'top_trumps_game_text' ),
			'remove_featured_image' => __( 'Remove featured image', 'top_trumps_game_text' ),
			'use_featured_image'    => __( 'Use as featured image', 'top_trumps_game_text' ),
			'insert_into_item'      => __( 'Insert into top trump', 'top_trumps_game_text' ),
			'uploaded_to_this_item' => __( 'Uploaded to this top trump', 'top_trumps_game_text' ),
			'items_list'            => __( 'Items list', 'top_trumps_game_text' ),
			'items_list_navigation' => __( 'Items list navigation', 'top_trumps_game_text' ),
			'filter_items_list'     => __( 'Filter items list', 'top_trumps_game_text' ),
		);
		$rewrite = array(
			'slug'                  => 'top-trump',
			'with_front'            => true,
			'pages'                 => true,
			'feeds'                 => true,
		);
		$args = array(
			'label'                 => __( 'Top Trump', 'top_trumps_game_text' ),
			'description'           => __( 'Top Trump Description', 'top_trumps_game_text' ),
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', ),
			'taxonomies'            => array( 'top_trumps_category' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-page',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'rewrite'               => $rewrite,
			'capability_type'       => 'page',
		);
		register_post_type( 'top_trumps', apply_filters( 'top_trumps_post_args', $args ) );		
	}
}