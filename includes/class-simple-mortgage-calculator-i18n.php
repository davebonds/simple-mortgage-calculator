<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://davebonds.com
 * @since      1.0.0
 *
 * @package    Simple_Mortgage_Calculator
 * @subpackage Simple_Mortgage_Calculator/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Simple_Mortgage_Calculator
 * @subpackage Simple_Mortgage_Calculator/includes
 * @author     Dave Bonds <db@davebonds.com>
 */
class Simple_Mortgage_Calculator_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'simple-mortgage-calculator',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
