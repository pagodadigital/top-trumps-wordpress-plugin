<?php 
/**
* Handles loading of an json formatted top trump data
* Takes object name and uses that as the category name, then loads the first array and uses objects as the post types
**/

class Json_To_Top_Trump_Converter extends Top_Trumps_Game {

	public function  __construct(){
		add_action( 'init', array( $this, 'check_for_new_files' ), 100 ); 
	}

	/**
	 * See if new json file loaded .
	 *
	 * @access public
	 * @return boolean if not a new file or object decoded json data of the data file
	 */	
	public function check_for_new_files(){
		$directory = pathinfo( $this->root )['dirname'].'/assets/data/';
		$files = scandir( $directory );
		
		//if data already loaded do nothing
		if( !$this->is_new_data( $files[2] ) ){
			return false; 
		}
		
		$data = file_get_contents( $directory.$files[2] ); 
		
		$this->import_data( $data ); 
	}
	
	/**
	 * Create a new category in top trumps and import the data 
	 *
	 * @access public
	 * @return void
	 */	
	public function import_data( $data ){
		$result = json_decode( $data );

		if ( json_last_error() != JSON_ERROR_NONE ){
		    return $this->log( 'Invalid JSON format in Json_To_Top_Trump_Converter->import_data()');
		}	

		$this->trump_card_slug = $this->format_slug( $result->Name );

		//should check to see if term exists
		wp_insert_term( $result->Name, 'top_trumps_category', array( 
			'slug' => $this->trump_card_slug, 
		) );

		foreach( $result->Data as $key => $trump_card_object ){
			
			$trump_data_variables = get_object_vars( $trump_card_object ); 
			//count the number of objects in data set
			$data_count = count( $trump_data_variables );
			
			$trump_title = array_shift( $trump_data_variables ); 

			//another check to see if trump card exists
			if( $this->post_exists( $trump_title ) ){
				return false;
			}

			//first item is the post name
			$post_id = wp_insert_post( array(
				'post_type' => 'top_trumps', 
				'post_title' => $trump_title, 
				'post_content'=> '',
				'post_status'=> 'publish'
			)); 

			foreach ( $trump_data_variables as $trump_card_meta_key => $trump_card_meta_value ) {

				$trump_card_meta_key = $this->format_meta_key( $trump_card_meta_key ); 

				if( $trump_card_meta_key ){
					update_post_meta( $post_id, $trump_card_meta_key, $trump_card_meta_value, '' );
				}
			}
		}
	}

	/**
	 * Format meta key for import
	 *
	 * @access public
	 * @return string of meta_key_name if valid
	 */	
	public function format_meta_key( $meta_key ){
		if( empty( $meta_key ) ){
			return false; 
		}
		$meta_key = str_replace( ' ', '_', $meta_key ); 
		$meta_key = strtolower( $meta_key );

		//prefix meta data with trump card slug to separate out data types
		$meta_key = $this->trump_card_slug.'_'.$meta_key;

		return $meta_key; 
	}

	/**
	 * Format meta key for import
	 *
	 * @access public
	 * @return string of slug if valid
	 */	
	public function format_slug( $slug ){
		if( empty( $slug ) ){
			return false; 
		}
		$slug = str_replace( ' ', '_', $slug ); 
		$slug = strtolower( $slug );

		return $slug; 
	}

	/**
	 * Check to see if is a new json file, compare against name of previousky uploaded files .
	 *
	 * @access public
	 * @return void
	 */	
	public function is_new_data( $file ){
		$loaded_files = get_option( 'loaded_json_files');

		if( !is_array( $loaded_files ) ){
			$loaded_files = array();
		}

		if( !in_array( $file, $loaded_files ) ){
			$loaded_files[] = $file; 
			//update_option( 'loaded_json_files', $loaded_files );
			return true; 
		}

		//the file has already been loaded
		return false; 
	}

	/**
	 * Check to see post title already exists, take from wordpress core as not available this early on
	 *
	 * @access public
	 * @return void
	 */	
	public function post_exists( $title ) {
	    global $wpdb;
	 
	    $post_title = wp_unslash( sanitize_post_field( 'post_title', $title, 0, 'db' ) );
	 
	    $query = "SELECT ID FROM $wpdb->posts WHERE 1=1";
	    $args = array();
	 
	 
	    if ( !empty ( $title ) ) {
	        $query .= ' AND post_title = %s';
	        $args[] = $post_title;
	    }
	 
	    if ( !empty ( $args ) )
	        return (int) $wpdb->get_var( $wpdb->prepare($query, $args) );
	 
	    return 0;
	}
}