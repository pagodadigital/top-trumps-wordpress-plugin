<?php
/**
* Top trump card functions
**/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Shortcode function
* @since 1.0 
* @return  void 
**/
function output_top_trumps( $atts ){

	if( !function_exists( 'top_trumps_game_instance' ) ){
		return false; 
	}

  	$args = shortcode_atts( array(
		'category'		=> '', //required
		'card_number'	=> '', //required
    ), $atts );

  	if( empty( $args['category'] ) ){
  		return printf( 'Error please enter a category for your top trumps' );
  	}
	$category = $args['category'];
   	$query_args = array(
  		'post_type' => top_trumps_game_instance()->post_type,
		'tax_query' => array(
			'taxonomy' => top_trumps_game_instance()->post_taxonomy,
			'field'    => 'name',
			'terms'    => $category,
		),
		'posts_per_page' => -1
  	);
  	
  	$query = new WP_Query( $query_args );

  	if( !$query->have_posts() ){
  		return false; 
  	}

	ob_start();

		include( dirname( top_trumps_game_instance()->root ).'/templates/view.php' );

	$output = ob_get_clean(); 
	
	echo $output;
}
add_shortcode( 'top_trumps', 'output_top_trumps', 10, 1 );

/**
* Finds all postmeta that is prefixed with current category for a singular post
* @since 1.0 
* @return array  
**/
function meta_finder( $category, $post_id ){

	$category_prefix = format_category_name( $category );

	$postmetas = get_post_meta( $post_id );

	$output_meta = array();

	foreach ( $postmetas as $meta_key => $meta_value ) {
		if( strpos( $meta_key, $category_prefix ) !== false ){
			
			$meta_value = array_shift( $meta_value );

			$output_name = format_meta_name( $meta_key, $category_prefix );
			$output_type = meta_value_type( $meta_value);

			if( empty( $output_name ) ){
				continue; 
			}

			$formatted_meta = new stdClass; 

			$formatted_meta->name 	= $output_name; 
			$formatted_meta->value 	= $meta_value;
			$formatted_meta->type 	= $output_type;

			$output_meta[ $meta_key ] = $formatted_meta; 
			
			if( $output_type == 'image' ){
				$output_meta['top_trump_image'] = $formatted_meta->value;
			} 
		}
	}
	return $output_meta;
}

/**
* Removes underscores from meta key and captilizes for front end output
* @since 1.0 
* @return string  
**/
function format_meta_name( $meta_key, $category ){
	//remove top trump category prefix

	$value_name = str_replace( $category, '', $meta_key );
	$value_name = str_replace( '_', ' ', $value_name );
	$value_name = ucwords( $value_name );
	return $value_name;
}

/**
* Removes underscores from meta key and captilizes for front end output
* @since 1.0 
* @return string  
**/
function format_category_name( $category_name ){
	$value_name = str_replace( ' ', '_', $category_name );
	$value_name = strtolower( $value_name );
	return $value_name;
}

/**
* Checks to see what type of data we have to play with
* @since 1.0 
* @return string type of data   
**/
function meta_value_type( $meta_value ){
	//integer
	if( is_numeric( $meta_value ) ){
		return 'number';
	}

	//image	
	if( preg_match("/.(jpg|png|gif|jpeg)/", $meta_value ) ){
		return 'image';
	}

	if( strlen ( $meta_value  ) > 100 ){
		return 'long-meta';
	}
	return 'string';
}	

/**
* Checks to if is trump card data
* @since 1.0 
* @return  
**/
function is_trump_card_meta( $name, $type, $title, $key ){
	//$disallowed_meta = array( 'long-meta', 'image' );
	if( $type == 'long-meta' || $type == 'image' || $name == $title || $key == 'top_trump_image'  ){
		return false; 
	}
	return true; 
}