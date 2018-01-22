<?php

/**
 * The front end mortgage calculator widget.
 *
 * @link       https://davebonds.com
 * @since      1.0.0
 *
 * @package    Simple_Mortgage_Calculator
 * @subpackage Simple_Mortgage_Calculator/includes
 * @author     Dave Bonds <db@davebonds.com>
 */

class Simple_Mortgage_Calculator_Widget extends WP_Widget {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$widget_ops  = array(
			'classname' => $this->plugin_name,
			'description' => __( 'Display a mortgage calculator.', 'simple-mortgage-calculator' ),
			'customize_selective_refresh' => true,
		);
		$control_ops = array(
			'width' => 300,
			'height' => 350,
		);

		parent::__construct(
			$this->plugin_name,
			__( 'Simple Mortgage Calculator', 'simple-mortgage-calculator' ),
			$widget_ops,
			$control_ops
		);

	}

	/**
	 * Widget defaults.
	 *
	 * @var array
	 */
	public $defaults = array(
		'title'        => 'Mortgage Calulator',
		'principal'    => 350000,
		'down_payment' => 35000,
		'term'         => 30,
		'apr'          => '4.33',
	);

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']        = strip_tags( $new_instance['title'] );
		$instance['principal']    = (int) $new_instance['principal'];
		$instance['down_payment'] = (int) $new_instance['down_payment'];
		$instance['term']         = (int) $new_instance['term'];
		$instance['apr']          = (float) $new_instance['apr'];

		return $instance;
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		wp_enqueue_style( $this->plugin_name );
		wp_enqueue_script( 'material-design-lite', 'https://code.getmdl.io/1.3.0/material.min.js' );
		wp_enqueue_script( $this->plugin_name );

		$defaults = $this->defaults;

		$instance = wp_parse_args( (array) $instance, $defaults );

		if ( empty( $instance ) ) {
			$instance = $this->defaults;
		}

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}
		?>
		<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" class="<?php echo $this->plugin_name; ?>" method="post" name="<?php echo $this->plugin_name; ?>">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label principal">
				<input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="principal" <?php echo ( ! empty( $instance['principal'] ) ) ? 'placeholder="' . $instance['principal'] . '"' : ''; ?>>
				<label class="mdl-textfield__label" for="principal">Principal</label>
				<span class="mdl-textfield__error">Principal is not a number!</span>
			</div>
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label down-payment">
				<input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="down_payment" <?php echo ( ! empty( $instance['down_payment'] ) ) ? 'placeholder="' . $instance['down_payment'] . '"' : ''; ?>>
				<label class="mdl-textfield__label" for="down_payment">Down Payment</label>
				<span class="mdl-textfield__error">Down Payment is not a number!</span>
			</div>
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label term">
				<input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="term" <?php echo ( ! empty( $instance['term'] ) ) ? 'placeholder="' . $instance['term'] . '"' : ''; ?>>
				<label class="mdl-textfield__label" for="term">Term (in years)</label>
				<span class="mdl-textfield__error">Term (in years) is not a number!</span>
			</div>
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label apr">
				<input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="apr" <?php echo ( ! empty( $instance['apr'] ) ) ? 'placeholder="' . $instance['apr'] . '"' : ''; ?>>
				<label class="mdl-textfield__label" for="apr">APR %</label>
				<span class="mdl-textfield__error">APR is not a number!</span>
			</div>
			<button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect calculate" type="submit">
				Calculate
			</button>
			<div class="monthly-payment">
				Estimated monthly payment is:
				<div class="amount">$
					<input class="result" readonly></input>
					<div class="loader" style="display: none;">
						<div class="line"></div>
						<div class="line"></div>
						<div class="line"></div>
						<div class="line"></div>
					</div>
				</div>
			</div>
		</form>
		<?php

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$defaults = $this->defaults;

		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'simple-mortgage-calculator' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name('title' ); ?>" type="text" value="<?php esc_attr_e( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'principal' ); ?>"><?php _e('Default Loan Principal', 'simple-mortgage-calculator' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'principal' ); ?>" name="<?php echo $this->get_field_name('principal' ); ?>" type="text" value="<?php esc_attr_e( $instance['principal'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'down_payment' ); ?>"><?php _e('Default Down Payment', 'simple-mortgage-calculator' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'down_payment' ); ?>" name="<?php echo $this->get_field_name('down_payment' ); ?>" type="text" value="<?php esc_attr_e( $instance['down_payment'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'term' ); ?>"><?php _e('Default Term (in years)', 'simple-mortgage-calculator' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'term' ); ?>" name="<?php echo $this->get_field_name('term' ); ?>" type="text" value="<?php esc_attr_e( $instance['term'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'apr' ); ?>"><?php _e('Default APR %', 'simple-mortgage-calculator' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'apr' ); ?>" name="<?php echo $this->get_field_name('apr' ); ?>" type="text" value="<?php esc_attr_e( $instance['apr'] ); ?>" />
		</p>

		<?php
	}


}
