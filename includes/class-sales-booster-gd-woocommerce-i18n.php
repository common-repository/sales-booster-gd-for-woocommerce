<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://globaldev.app/
 * @since      1.0.0
 *
 * @package    Sales_Booster_Gd_Woocommerce
 * @subpackage Sales_Booster_Gd_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sales_Booster_Gd_Woocommerce
 * @subpackage Sales_Booster_Gd_Woocommerce/includes
 * @author     Globaldev <contact@globaldev.app>
 */
class Sales_Booster_Gd_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sales-booster-gd-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
