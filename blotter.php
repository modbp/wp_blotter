<?php
/**
 * Blotter
 *
 * @package       BLOTTER
 * @author        Modern Bit and Pixel LLC
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Blotter
 * Plugin URI:    https://useblotter.com
 * Description:   Blog comments powered by Twitter
 * Version:       1.0.0
 * Author:        Modern Bit and Pixel LLC
 * Author URI:    https://modernbitandpixel.com
 * Text Domain:   blotter
 * Domain Path:   /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This file contains the main information about the plugin.
 * It is used to register all components necessary to run the plugin.
 * 
 * The comment above contains all information about the plugin 
 * that are used by WordPress to differenciate the plugin and register it properly.
 * It also contains further PHPDocs parameter for a better documentation
 * 
 * The function BLOTTER() is the main function that you will be able to 
 * use throughout your plugin to extend the logic. Further information
 * about that is available within the sub classes.
 * 
 * HELPER COMMENT END
 */

// Plugin name
define( 'BLOTTER_NAME',			'Blotter' );

// Plugin version
define( 'BLOTTER_VERSION',		'1.0.0' );

// Plugin Root File
define( 'BLOTTER_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'BLOTTER_PLUGIN_BASE',	plugin_basename( BLOTTER_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'BLOTTER_PLUGIN_DIR',	plugin_dir_path( BLOTTER_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'BLOTTER_PLUGIN_URL',	plugin_dir_url( BLOTTER_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */
require_once BLOTTER_PLUGIN_DIR . 'core/class-blotter.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Modern Bit and Pixel LLC
 * @since   1.0.0
 * @return  object|Blotter
 */
function BLOTTER() {
	return Blotter::instance();
}

BLOTTER();
