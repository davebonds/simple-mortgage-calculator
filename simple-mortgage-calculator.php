<?php

/**
 * This is a simple mortgage calculator that will give a monthly payment based on the principal of the loan / the term + apr
 *
 * @link              https://davebonds.com
 * @since             1.0.0
 * @package           Simple_Mortgage_Calculator
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Mortgage Calculator
 * Plugin URI:        https://github.com/davebonds/simple-mortgage-caclulator
 * Description:       A simple mortgage calculator widget.
 * Version:           1.0.0
 * Author:            Dave Bonds
 * Author URI:        https://davebonds.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-mortgage-calculator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin version.
 */
define( 'SMC_VERSION', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-simple-mortgage-calculator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_simple_mortgage_calculator() {

	$plugin = new Simple_Mortgage_Calculator();
	$plugin->run();

}
run_simple_mortgage_calculator();
