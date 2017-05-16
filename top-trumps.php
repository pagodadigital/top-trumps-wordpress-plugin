<?php
/*
Plugin Name: Top Trumps Game Plugin
Description: Play Top Trumps With Your Imported Data
Version: 1.0.0
Author: Swellfoundry
Author URI: http://swellfoundry.com
Text Domain: top_trumps_game_text
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Activation hooks
**/
register_activation_hook( __FILE__, array( 'Top_Trumps_Game', 'activate' ) );

/**
 *	Class Top_Trumps
 *
 *	@class		Top_Trumps
 *	@version	1.0.0
 *	@author		Nick Braithwaite
 */
class Top_Trumps_Game  {

    /**
     * Plugin version.
     *
     * @since 1.0.0
     * @var string $version Plugin version number.
     */
    public $version = '1.0.0';

    /**
     * Plugin file.
     *
     * @since 1.0.0
     * @var string $file Plugin file path.
     */
    public $root = __FILE__;


    /**
     * Return the Post Slug.
     *
     * @since 1.0.0
     * @var string $file Plugin file path.
     */
    public $post_type = 'top_trumps';


    /**
     * Return the Post Taxonomy.
     *
     * @since 1.0.0
     * @var object $instance The instance of LGWK.
     */
    public $post_taxonomy  = 'top_trumps_category';

    /**
     * Instance of Plugin.
     *
     * @since 1.0.0
     * @access private
     * @var object $instance The instance of Top_Trumps_Game.
     */
    private static $instance;

    /**
     * Construct.
     *
     * Initialize the class and plugin.
     *
     * @since 1.0.0
     */
    public function __construct() {
        $this->setup();
        $this->init();
        
    }

    /**
     * @return string
     */
    public function init() {
        $this->plugin_dir   = plugin_dir_path( __FILE__ );
        $this->plugin_url   = plugin_dir_url( __FILE__ );
        $this->includes();
        
        add_action( 'wp_enqueue_scripts', array( $this, 'load_js_css' ) );
    }

    /**
     * Create activation hook and log to database plugin has been installed 
     * 
     * @since 1.0.0
     * @return void.
     */
    public static function activate(){
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );
        
        add_option( 'top_trump_plugin_activation', 'plugin_activated' );
    }
    
    /**
     * Load include files and initiate classes so they are accessible from parent ($this) object
     * 
     * @since 1.0.0
     * @return void 
     */
    public function includes() {
        $files = array(
            'class-top-trump-post-types.php', 
            'class-json-trump-card-converter.php',
            'top-trump-card-functions.php'
        );

        foreach ( $files as $file ) {
            require_once( $this->plugin_dir . 'includes/' . $file );
        }

        $this->post_types = new Top_Trumps_Game_Post_Types();
        $this->converter  = new Json_To_Top_Trump_Converter();
    }
    
    /**
     * Setup actions.
     *
     * Installs data found in the assets file
     * 
     *
     * @since 1.0.0
     * @return object Instance of the class.
     */
    public function setup() {   
        add_action( 'admin_init', array( $this, 'plugin_initialize' ), 100 );
    }

    /**
     * Load js /css
     *
     *
     * @since 1.0.0
     * @return void
     */
    public function load_js_css() {   
        wp_enqueue_style( 'trump-card-css', $this->plugin_url.'assets/top-trump.css' );
        
        wp_enqueue_script( 'isotope-js', $this->plugin_url.'vendor/isotope.pkgd.min.js', array('jquery') , false, true );

        wp_enqueue_script( 'trump-card-js', $this->plugin_url.'assets/top-trump.js', array('jquery'), false, true );
    }

    /**
     * hooks into admin to see if the plugin is newly activated and fush rewrite rules..
     *
     * @return void
     */
    function plugin_initialize() {
        if( is_admin() && get_option( 'top_trump_plugin_activation' ) == 'plugin_activated' ) {
            delete_option( 'top_trump_plugin_activation' );
            flush_rewrite_rules();
        }
    }

    /**
     * An global instance of the class. Used to retrieve the instance
     * to use on other files/plugins/themes.
     *
     * @since 1.0.0
     * @return object Instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * For error logging
     *
     * @since 1.0.0
     * @return void.
     */
    public function log( $log ){
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }
}

/**
 * The main function responsible for returning the object above.
 *
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php top_trumps_game_instance()->method_name(); ?>
 *
 * @since 1.0.0
 *
 * @return object Top_Tumps_Game class object.
 */
function top_trumps_game_instance() {
    return Top_Trumps_Game::instance();
}
add_action( 'plugins_loaded', 'top_trumps_game_instance' );