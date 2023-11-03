<?php
/**
 * Plugin Name: IndieAuth Remote
 * Plugin URI: https://github.com/indieweb/wordpress-indieauth-remote/
 * Description: IndieAuth is a way to allow users to use their own domain to sign into other websites and services
 * Version: 4.3.0
 * Author: IndieWebCamp WordPress Outreach Club
 * Author URI: https://indieweb.org/WordPress_Outreach_Club
 * License: MIT
 * License URI: http://opensource.org/licenses/MIT
 * Text Domain: indieauth-remote
 * Domain Path: /languages
 */

add_action( 'upgrader_process_complete', array( 'IndieAuth_Plugin', 'upgrader_process_complete' ), 10, 2 );
add_action( 'indieauth_cleanup', array( 'IndieAuth_Plugin', 'expires' ) );

class IndieAuth_Plugin {
	public static $indieauth = null; // Loaded instance of authorize class
	public static $metadata  = null; // Loaded instance of metadata class
	public static $scopes    = null; // Loaded instance of scopes class

	/*
	 * Process to Trigger on Plugin Update.
	 */
	public static function upgrader_process_complete( $upgrade_object, $options ) {
		$current_plugin_path_name = plugin_basename( __FILE__ );
		if ( ( 'update' === $options['action'] ) && ( 'plugin' === $options['type'] ) ) {
			foreach ( $options['plugins'] as $each_plugin ) {
				if ( $each_plugin === $current_plugin_path_name ) {
					self::schedule();
				}
			}
		}
	}


	public static function plugins_loaded() {
		// Load Core Classes that are always loaded
		self::load(
			array(
				'functions.php', // Global Functions
				'class-oauth-response.php', // OAuth REST Error Class
				'class-token-generic.php', // Token Base Class
				'class-token-user.php',
				'class-indieauth-scope.php', // Scope Class
				'class-indieauth-scopes.php', // Scopes Class
				'class-indieauth-authorize.php', // IndieAuth Authorization Base Class
				'class-token-transient.php',
				'class-web-signin.php',
				'class-indieauth-admin.php', // Administration Class
			)
		);

		static::$scopes = new IndieAuth_Scopes();

		new IndieAuth_Admin();

		// Classes Require for using a Remote Endpoint
		$remotefiles = array(
			'class-indieauth-remote-authorize.php',
		);

				self::load( $remotefiles );
				static::$indieauth = new IndieAuth_Remote_Authorize();
				break;

		if ( WP_DEBUG ) {
			self::load( 'class-indieauth-debug.php' );
			new IndieAuth_Debug();
		}
	}

	// Check that a file exists before loading it and if it does not print to the error log
	public static function load( $files, $dir = 'includes/' ) {
		if ( empty( $files ) ) {
			return;
		}
		$path = plugin_dir_path( __FILE__ ) . $dir;

		if ( is_string( $files ) ) {
			$files = array( $files );
		}
		foreach ( $files as $file ) {
			if ( file_exists( $path . $file ) ) {
				require_once $path . $file;
			} else {
				error_log( // phpcs:ignore
					// translators: 1. Path to file unable to load
					sprintf( __( 'Unable to load: %1s', 'indieauth' ), $path . $file )
				);
			}
		}
	}
}

add_action( 'plugins_loaded', array( 'IndieAuth_Plugin', 'plugins_loaded' ), 2 );
