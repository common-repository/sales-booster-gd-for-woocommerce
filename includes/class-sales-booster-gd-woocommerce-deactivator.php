<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://globaldev.app/
 * @since      1.0.0
 *
 * @package    Sales_Booster_Gd_Woocommerce
 * @subpackage Sales_Booster_Gd_Woocommerce/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Sales_Booster_Gd_Woocommerce
 * @subpackage Sales_Booster_Gd_Woocommerce/includes
 * @author     Globaldev <contact@globaldev.app>
 */
class Sales_Booster_Gd_Woocommerce_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        if( wp_next_scheduled( 'sales_booster_gd_per_minute_event' ) ){
            wp_clear_scheduled_hook( 'sales_booster_gd_per_minute_event' );
        }
	}

}
