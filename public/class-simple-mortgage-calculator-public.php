<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://davebonds.com
 * @since      1.0.0
 *
 * @package    Simple_Mortgage_Calculator
 * @subpackage Simple_Mortgage_Calculator/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Simple_Mortgage_Calculator
 * @subpackage Simple_Mortgage_Calculator/public
 * @author     Dave Bonds <db@davebonds.com>
 */
class Simple_Mortgage_Calculator_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of the plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action( 'widgets_init', array( $this, 'register_widget' ) );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/simple-mortgage-calculator-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/simple-mortgage-calculator-public.min.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'SMC_Ajax',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	/**
	 * Registers the widget.
	 *
	 * @since    1.0.0
	 */
	public function register_widget() {

		$plugin_widget = new Simple_Mortgage_Calculator_Widget( $this->plugin_name, $this->version );

		register_widget( $plugin_widget );

	}

	/**
	 * AJAX callback to pass calculated payment to JS.
	 *
	 * @since    1.0.0
	 */
	public function smc_ajax_callback() {
		$principal    = intval( $_POST['principal'] );
		$down_payment = intval( $_POST['down_payment'] );
		$term         = intval( $_POST['term'] );
		$apr          = floatval( $_POST['apr'] );

		$payment = $this->calculate_monthly_payment( $principal, $down_payment, $term, $apr );

		echo wp_json_encode( $payment );
		wp_die();
	}

	/**
	 * Returns calculated monthly payment.
	 *
	 * @since    1.0.0
	 * @param    int   $principal    The loan amount.
	 * @param    int   $down_payment The down payment amount.
	 * @param    int   $term         The loan term in years.
	 * @param    float $apr          The annual percentage rate.
	 * @return   float               The calculated monthly payment.
	 */
	public function calculate_monthly_payment( $principal, $down_payment, $term, $apr ) {
		$term_in_months = $term * 12;
		$rate = $apr / 100;
		$monthly_rate = $rate / 12;
		$total_amount = $principal - $down_payment;
		$payment = ( $total_amount * $monthly_rate ) / ( 1 - pow( ( 1 + $monthly_rate ), -$term_in_months ) );

		return number_format( $payment, 2 );
	}

	/**
	 * Output mortgage calculator using shortcode.
	 *
	 * @uses   Simple_Mortgage_Calculator_Widget
	 * @param  array   $atts Widget arguments.
	 * @return string        HTML widget output.
	 */
	public function shortcode( $atts ) {

		// Default widget args.
		$args = array(
			'before_widget' => '<section class="widget widget-area ' . $this->plugin_name . ' shortcode">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="widget-title widgettitle">',
			'after_title'   => '</h4>',
		);

		// Merge atts with defaults
		$instance = shortcode_atts(
			array(
				'title'        => '',
				'principal'    => 350000,
				'down_payment' => 35000,
				'term'         => 30,
				'apr'          => 3.9,
			),
			$atts
		);

		// Instantiate widget class
		$widget = new Simple_Mortgage_Calculator_Widget( $this->plugin_name, $this->version );

		// Return widget
		return $widget->widget( $args, $instance );

	}

}
