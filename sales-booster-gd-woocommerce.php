<?php

/**
 * The plugin Sales Booster GD for WooCommerce main file
 *
 *
 * @link              https://globaldev.app/
 * @since             1.0.0
 * @package           Sales_Booster_Gd_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Sales Booster GD for WooCommerce
 * Plugin URI:        https://globaldev.app/pricing/
 * Description:       Show recent sales popups on your website & Build trust in your Business
 * Version:           1.0.0
 * Author:            Globaldev
 * Author URI:        https://globaldev.app/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sales-booster-gd-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SALES_BOOSTER_GD_WOOCOMMERCE_VERSION', '1.0.0' );

define('SALES_BOOSTER_GD_WOOCOMMERCE_REVISION',			'1.0.0');
define('SALES_BOOSTER_GD_WOOCOMMERCE_PLUGIN_PATH',		plugin_dir_path(__FILE__));
define('SALES_BOOSTER_GD_WOOCOMMERCE_PLUGIN_DIR',       dirname(__FILE__) . '/');
define('SALES_BOOSTER_GD_WOOCOMMERCE_PLUGIN_URL',       plugins_url('/', __FILE__));
define('SALES_BOOSTER_GD_WOOCOMMERCE_PLUGIN_FILE',      __FILE__);
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sales-booster-gd-woocommerce-activator.php
 */
function activate_sales_booster_gd_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sales-booster-gd-woocommerce-activator.php';
	Sales_Booster_Gd_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sales-booster-gd-woocommerce-deactivator.php
 */
function deactivate_sales_booster_gd_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sales-booster-gd-woocommerce-deactivator.php';
	Sales_Booster_Gd_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sales_booster_gd_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_sales_booster_gd_woocommerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sales-booster-gd-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sales_booster_gd_woocommerce() {

	$plugin = new Sales_Booster_Gd_Woocommerce();
	$plugin->run();

}
run_sales_booster_gd_woocommerce();

// The schedule filter hook
function sales_booster_gd_cron_job_recurrence( $schedules ) {
    $schedules['sales_booster_gd_per_minute'] = array(
        'interval' => 60,
        'display' => __('Every minute', 'textdomain' )
    );
    $schedules['sales_booster_gd_every_hour'] = array(
        'interval'  => 3600,
        'display'   => __( 'Every hour', 'textdomain' )
    );
    $schedules['sales_booster_gd_every_day'] = array(
        'interval' => 86400,
        'display' => __('Every day', 'textdomain' )
    );  
    return $schedules;
}
add_filter( 'cron_schedules', 'sales_booster_gd_cron_job_recurrence' );

// The WP Cron event callback function
function sales_booster_gd_per_minute_event_func() {
    //update_option("sales_booster_gd_woocommerce_status", "Active");
    global $wpdb;
    $sales_booster_gd_woocommerce_devices = $wpdb->prefix . 'sales_booster_gd_woocommerce_ip_devices';
    $sales_booster_gd_woocommerce_ip = $wpdb->prefix . 'sales_booster_gd_woocommerce_ip';
    $count_online = "select count(*) from $sales_booster_gd_woocommerce_devices";
    $online = $wpdb->get_var($count_online);
    $pdate  = strtotime(date("Y-m-d"));
    $mydate = strtotime(get_option('sales_booster_gd_woocommerce_expiry_date'));
    $maxUsers = get_option('sales_booster_gd_woocommerce_max_users');

    if($wpdb->get_var("SHOW TABLES LIKE '$sales_booster_gd_woocommerce_ip'") == $sales_booster_gd_woocommerce_ip) {
        update_option("sales_booster_gd_woocommerce_status", "Active");
    } else {
        update_option("sales_booster_gd_woocommerce_status", "Inactive");
    }   
    
    if ($pdate>=$mydate){
        update_option("sales_booster_gd_woocommerce_expiry_date", date('Y-m-d', strtotime("+30 days") ) );
        $wpdb->query("TRUNCATE TABLE $sales_booster_gd_woocommerce_devices");
        $wpdb->query("TRUNCATE TABLE $sales_booster_gd_woocommerce_ip");
    }else{
        update_option("sales_booster_gd_woocommerce_status", "Active");
    }
    if ($online>$maxUsers){
        update_option("sales_booster_gd_woocommerce_status", "Inactive");
    }else{
        update_option("sales_booster_gd_woocommerce_status", "Active");
    }
}
add_action( 'sales_booster_gd_per_minute_event', 'sales_booster_gd_per_minute_event_func' );
//footer scripts
function sales_booster_gd_woocommerce_ft_script() {
    $activity = get_option("sales_booster_gd_woocommerce_status");
?>
<script type="text/javascript">
  var status = '<?php echo $activity ?>';
  var curStatus;
  if (  status == 'Active' ) {
    curStatus = 'Active';
  } else {
    curStatus = 'Inactive';
  }
</script>
<?php
}
add_action( 'wp_footer', 'sales_booster_gd_woocommerce_ft_script' );