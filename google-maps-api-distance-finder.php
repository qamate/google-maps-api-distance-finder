<?php
/**
 * Plugin Name: Google Maps API distance finder
 * Description: This Plugin will expose an endpoint on WordPress. When called, will calculate and provide the nearest point from origin.
 * Plugin URI: 
 * Version: 1.0.0
 * Author: qamate
 * License: GPLv2 or later
 */
 
 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
 define( 'SMP__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 
 
?>