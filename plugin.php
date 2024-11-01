<?php
/**
 * Plugin Name: Simple Captcha for WPForms
 * Plugin URI: https://wpterra.de
 * Description:  Provides a simple question/answer captcha for WP Forms to prevent spam
 * Version: 1.0.0
 * Author: Andreas Münch
 * Author URI: https://andreasmuench.de
 * Text Domain: simple-captcha-wpforms
 * Domain Path: /languages.
 *
 */


namespace simple_captcha_wpforms;

/**
 * Prevent direct access data leaks.
 **/
if (!defined('ABSPATH')) {
    exit;
}


// initiate plugin
Plugin::instance();

/**
 * Main initiation class.
 *
 * @since  NEXT
 */
final class Plugin
{
    /**
     * Singleton instance of plugin.
     */
    private static $_instance = null;

    // the Plugin Name as defined above
    public static $plugin_name;
    // the Plugin Version as defined above
    public static $plugin_version;
    // e.g "example-plugin"
    public static $plugin_basename;
    public static $plugin_dir;
    public static $plugin_url;

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Plugin constructor
     * do stuff here that works immediately, because not all WP functions are loaded at this point
     */
    protected function __construct()
    {
        // get plugin data, get_plugin_data() does not work here and only in admin
        $plugin_data = get_file_data(__FILE__, [
            'Name' => 'WPForms Simple Captcha',
            'Version' => 'Version',
        ], 'plugin');

        self::$plugin_name = $plugin_data['Name'];;
        self::$plugin_version = $plugin_data['Version'];;
        self::$plugin_basename = plugin_basename( __FILE__ );
        self::$plugin_dir = untrailingslashit(plugin_dir_path(__FILE__));
        self::$plugin_url = untrailingslashit(plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));

        // init plugin after all plugins are loaded
        add_action('plugins_loaded', array(__CLASS__, 'init'));

        register_activation_hook(__FILE__, array(__CLASS__, 'plugin_activation'));
        register_deactivation_hook(__FILE__, array(__CLASS__, 'plugin_deactivation'));

    }

    /**
     * init stuff after 'plugins_loaded'
     */
    public static function init()
    {
        // core
        require_once('core/debug.php');
        if(class_exists('WPForms_Field')){
            require_once('wpforms/WPForms_Field_Simple_Captcha.php');
        }
    }


    public static function plugin_activation(){

        error_log(self::$plugin_name.' plugin_activation');
    }

    public static function plugin_deactivation(){

        error_log(self::$plugin_name.' plugin_deactivation');
    }

}
