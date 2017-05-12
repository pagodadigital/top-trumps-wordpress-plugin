<?php
/*
Plugin Name: Top Trumps Game Plugin
Description: Play Top Trumps With Your Imported Data
Version: 1.0.0
Author: Swellfoundry
Author URI: http://swellfoundry.com
Text Domain: top-trumps
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
    public static $base = ABSPATH;

    /**
     * Plugin file.
     *
     * @since 1.0.0
     * @var string $file Plugin file path.
     */
    public $root = __FILE__;

    /**
     * Instance of Plugin.
     *
     * @since 1.0.0
     * @access private
     * @var object $instance The instance of LGWK.
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
        $this->init();
    }

    /**
     * @return string
     */
    public function init() {

        $this->plugin_dir   = plugin_dir_path( __FILE__ );
        $this->plugin_url   = plugin_dir_url( __FILE__ );
        $this->includes();
    }


    public function includes() {
        $files = array(
        );

        foreach ( $files as $file ) {
            require_once( $this->plugin_dir . 'includes/' . $file );
        }
    }

    /**
     * Instance.
     *
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