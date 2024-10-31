<?php
/**
 * Fired during plugin activation
 *
 * @link       https://globaldev.app/
 * @since      1.0.0
 *
 * @package    Sales_Booster_Gd_Woocommerce
 * @subpackage Sales_Booster_Gd_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sales_Booster_Gd_Woocommerce
 * @subpackage Sales_Booster_Gd_Woocommerce/includes
 * @author     Globaldev <contact@globaldev.app>
 */
class Sales_Booster_Gd_Woocommerce_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {  
        global $wpdb;
        $sales_booster_gd_woocommerce_db_version = '1.0.1';
        $sales_booster_gd_woocommerce_status = 'Active';
        $sales_booster_gd_woocommerce_expiry_date = date('Y-m-d', strtotime("+30 days"));
        $sales_booster_gd_woocommerce_max_users = '1000';
        $sales_booster_gd_woocommerce_ip = $wpdb->prefix . 'sales_booster_gd_woocommerce_ip';
        $sales_booster_gd_woocommerce_devices = $wpdb->prefix . 'sales_booster_gd_woocommerce_ip_devices';                 
        $collate = '';
        if ( $wpdb->has_cap( 'collation' ) ) {
            $collate = $wpdb->get_charset_collate();
        }
        if( $wpdb->get_var( "SHOW TABLES LIKE '$sales_booster_gd_woocommerce_ip'" ) != $sales_booster_gd_woocommerce_ip ){        
            $sql = "CREATE TABLE IF NOT EXISTS $sales_booster_gd_woocommerce_ip (
                `id` int(11) NOT NULL auto_increment,
                `ip` varchar(255) UNIQUE,
                `date` date NOT NULL,
                `time` time NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;{$collate}";
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    		dbDelta($sql);
        }
        if( $wpdb->get_var( "SHOW TABLES LIKE '$sales_booster_gd_woocommerce_devices'" ) != $sales_booster_gd_woocommerce_devices ){                    
            $sql = "CREATE TABLE IF NOT EXISTS $sales_booster_gd_woocommerce_devices (
                `id` int(11) NOT NULL auto_increment,
                `ipid` int(11) NOT NULL,
                `date` date NOT NULL,
                `time` time NOT NULL,
                `device` varchar(255) NOT NULL,
                `browser` varchar(255) NOT NULL,
                `browserver` varchar(255) NOT NULL,
                `unibrowid` varchar(255) UNIQUE,
                `online` varchar(255) NOT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`ipid`) REFERENCES {$wpdb->prefix}sales_booster_gd_woocommerce_ip(id) ON DELETE CASCADE
            )ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;{$collate}";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );  
        }
        add_option('sales_booster_gd_woocommerce_db_version', $sales_booster_gd_woocommerce_db_version);
        add_option('sales_booster_gd_woocommerce_status', $sales_booster_gd_woocommerce_status);
        add_option('sales_booster_gd_woocommerce_expiry_date', $sales_booster_gd_woocommerce_expiry_date);
        add_option('sales_booster_gd_woocommerce_max_users', $sales_booster_gd_woocommerce_max_users);
        if( !wp_next_scheduled( 'sales_booster_gd_per_minute_event' ) ){
            wp_schedule_event( time(), 'sales_booster_gd_per_minute', 'sales_booster_gd_per_minute_event' );
        }
        
	}
}
