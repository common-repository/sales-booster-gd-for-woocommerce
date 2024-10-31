<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://globaldev.app/
 * @since      1.0.0
 *
 * @package    Sales_Booster_Gd_Woocommerce
 * @subpackage Sales_Booster_Gd_Woocommerce/admin/partials
 */

class Sales_Booster_Gd_Woocommerce_Options {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'sales_booster_gd_woocommerce_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'sales_booster_gd_woocommerce_page_init' ) );
        add_filter( 'plugin_action_links_sales-booster-gd-for-woocommerce/sales-booster-gd-woocommerce.php', array( $this, 'my_plugin_settings_link' ) );
	}
    public function sales_booster_gd_woocommerce_add_plugin_page() {
		add_menu_page(
			'Sales Booster GD for WooCommerce', // page_title
			'Sales Booster GD', // menu_title
			'manage_options', // capability
			'sales-booster-gd', // menu_slug
			array( $this, 'sales_booster_gd_woocommerce_admin_page' ), // function
			SALES_BOOSTER_GD_WOOCOMMERCE_PLUGIN_URL.'includes/images/logo_globaldev.png', // icon_url
			90 // position
		);
	}
    public function my_plugin_settings_link($links) { 
      $settings_link = '<a href="admin.php?page=sales-booster-gd">Settings</a>'; 
      array_unshift($links, $settings_link); 
      return $links; 
    }
    //$plugin = plugin_basename(__FILE__); 
    
    //INIT
	public function sales_booster_gd_woocommerce_page_init() {
    	
	}
	public function sales_booster_gd_woocommerce_admin_page() {
	    
        global $wpdb;
        //$orders = wc_orders_count('completed');
        $sales_booster_gd_woocommerce_ip = $wpdb->prefix . 'sales_booster_gd_woocommerce_ip';
        $sales_booster_gd_woocommerce_devices = $wpdb->prefix . 'sales_booster_gd_woocommerce_ip_devices';
              
        $count_query = "select count(*) from $sales_booster_gd_woocommerce_devices";
        //$count_online = "select count(*) from $sales_booster_gd_woocommerce_devices GROUP BY ipid";
        $count_online = "select count(*) from $sales_booster_gd_woocommerce_devices";
        $user_order = "select * from $sales_booster_gd_woocommerce_ip";
        $user_order_c = "select count(*) from $sales_booster_gd_woocommerce_ip";
        $num = $wpdb->get_var($count_query);
        $online = $wpdb->get_var($count_online);
        $user_order_var = $wpdb->get_results($user_order);
        $user_order_count = $wpdb->get_var($user_order_c);
        $sales_booster_gd_woocommerce_status = get_option('sales_booster_gd_woocommerce_status');
        $sales_booster_gd_woocommerce_expiry_date = get_option('sales_booster_gd_woocommerce_expiry_date');
        $sales_booster_gd_woocommerce_max_users = get_option('sales_booster_gd_woocommerce_max_users');
        
        ?>
            <div class="wrap">
            
            <div class="gd_wrap" >
                <h2>Sales Booster GD for WooCommerce</h2>
                    <div class="main-content ">
                        <div class="gd-row">
                            <div class="gd-column-8">
                                <div class="gd-row">
                                    <div class="map_wrap gd-column-4">
                                        <?php settings_errors(); ?>
                                        <h3><?php esc_html_e( 'Map', 'sales-booster-gd-woocommerce' ); ?></h3>
                                        <div class="map_wrap_inner background-gd border-gd text-center-gd">
                                            <?php
                                                //include map
                                                include_once(SALES_BOOSTER_GD_WOOCOMMERCE_PLUGIN_DIR . 'includes/map.php');
                                            ?>
                                        </div>
                                    </div>
                                    <div class="countries_wrap gd-column-4">
                                        <h3><?php esc_html_e( 'IP', 'sales-booster-gd-woocommerce' ); ?></h3>
                                        <div class="countries_wrap_inner background-gd border-gd">
                                            <ul>
                                                <?php
                                                    $countName = "SELECT  ip, COUNT(*) FROM $sales_booster_gd_woocommerce_ip GROUP BY ip HAVING COUNT(*) > 0";
                                                    $single_countries_names = $wpdb->get_results($countName);
                                                    //print_r($single_countries_names);
                                                    if($countName){
                                                        foreach($single_countries_names as $single_countries_name){
                                                            $items = array();
                                                            foreach($single_countries_name as $key => $single_countries_count){
                                                                $items[]= $single_countries_count;
                                                            }
                                                            ?>
                                                            <li><p style='margin-bottom:0;margin-top:0;'><?php echo esc_html($single_countries_name->ip. ": ".$items[1]); ?></p></li>
                                                            <?php
                                                        }
                                                    }else{
                                                        esc_html_e( 'Nothing to show', 'sales-booster-gd-woocommerce' );
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="available-pops gd-column-4">
                                        <h3><?php esc_html_e( 'Devices', 'sales-booster-gd-woocommerce' ); ?> </h3>
                                        <div class="countries_wrap_inner background-gd border-gd">
                                            <ul>
                                                <?php
                                                    $countName2 = "SELECT  device, COUNT(*) FROM $sales_booster_gd_woocommerce_devices GROUP BY device HAVING COUNT(*) > 0";
                                                    $single_countries_names2 = $wpdb->get_results($countName2);
                                                    //print_r($single_countries_names);
                                                    if($countName2){
                                                        foreach($single_countries_names2 as $single_countries_name){
                                                            $items = array();
                                                            foreach($single_countries_name as $key => $single_countries_count){
                                                                $items[]= $single_countries_count;
                                                            }
                                                            ?>
                                                            <li><p style='margin-bottom:0;margin-top:0;'><?php echo esc_html($single_countries_name->device. ": ".$items[1]); ?></p></li>
                                                            <?php
                                                        }
                                                    }else{
                                                        _e( 'Nothing to show', 'sales-booster-gd-woocommerce' );
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="gd-row">
                                    <div class="gd-column-12">
                                        <p><?php esc_html_e( 'Please keep in mind that the unique visitors will reset every 30 days. 
                                        If you reach your traffic limit within this interval the pop-up will stop showing and you need to wait for the 30 days reset.', 'sales-booster-gd-woocommerce' ); ?></p>
                                        <p><a href="https://globaldev.app/elements/pricing/" target="_blank" class="plugin-link"><?php esc_html_e( 'Or purchase premium version.', 'sales-booster-gd-woocommerce' ); ?></a></p>
                                    </div>
                                </div>
                                <div class="gd-row">
                                    <div class="table-wrap-gd gd-column-12">
                                        <table class="widefat fixed" cellspacing="0">
                                            <thead>
                                                <tr>
                                                  <th><?php esc_html_e( 'Status', 'sales-booster-gd-woocommerce' ); ?></th>
                                                  <th><?php esc_html_e( 'Counter Reset', 'sales-booster-gd-woocommerce' ); ?></th>
                                                  <th><?php esc_html_e( 'Unique Visits', 'sales-booster-gd-woocommerce' ); ?></th>
                                                  <th><?php esc_html_e( 'Max Users', 'sales-booster-gd-woocommerce' ); ?></th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr>
                                                  <td>
                                                        <?php
                                                        if($sales_booster_gd_woocommerce_status=='Active'){
                                                        ?>    
                                                        
                                                            <svg height="20" width="20" style="">
                                                              <circle cx="12" cy="12" r="7" fill="green"></circle>
                                                            </svg>
                                                            <p style="padding-left: 13px;"><?php echo esc_html($sales_booster_gd_woocommerce_status); ?></p>
                                                        
                                                        <?php    
                                                        }else if($sales_booster_gd_woocommerce_status=='Inactive'){
                                                        ?>
                                                            
                                                            <svg height="20" width="20" style="">
                                                              <circle cx="12" cy="12" r="7" fill="red"></circle>
                                                            </svg>
                                                            <p style="padding-left: 13px;"><?php echo esc_html($sales_booster_gd_woocommerce_status); ?></p>
                                                            
                                                        <?php
                                                        }
                                                        ?>
                                                  </td>
                                                  <td>
                                        
                                                        <p style="padding-left: 13px;"><?php echo esc_html($sales_booster_gd_woocommerce_expiry_date); ?></p>
                                                    
                                                  </td>

                                                  <td>
                                                        <p style="padding-left: 13px;"><?php echo esc_html($online); ?></p>
                                                  </td>

                                                  
                                                  <td>
                                                    <p style="padding-left: 13px;"><?php echo esc_html($online.'/'.$sales_booster_gd_woocommerce_max_users); ?></p>
                                                  </td>
                                                </tr>
                                              </tbody>
                                        </table>
                            			<form method="post" action="options.php">
                            				<?php
                            					settings_fields( 'sales_booster_gd_woocommerce_option_group' );
                            					do_settings_sections( 'sales-booster-gd-admin' );
                            					submit_button();
                            				?>
                            			</form>
                                    </div>
                                </div>
                            </div>
                            <div class="gd-column-4">
                                <div class="gd-row" >
                                    <div class="gd-column-12">
                                        <div class="gd-side">
                                            <p><?php esc_html_e( 'Are You " All or Nothing" Type? Then get the full experience with the Premium Features of the Sales Booster GD!', 'sales-booster-gd-woocommerce' ); ?> 
                                            <p><?php esc_html_e( 'It will definitely worth your money and time!', 'sales-booster-gd-woocommerce' ); ?> 
                                            <p><?php esc_html_e( 'Premium version includes extra fields & features that`ll convert visitors into leads and sales at a decent price. Every plan will determine the number of unique visitors /month on your website.', 'sales-booster-gd-woocommerce' ); ?> 
                                            <p><a href="https://globaldev.app/elements/pricing/" target="_blank" class="plugin-link"><?php esc_html_e( 'Click to see the premium features that will power up your website.', 'sales-booster-gd-woocommerce' ); ?></a></p>
                                            <h6><a href="https://www.globaldev.app/"><img src="<?php echo SALES_BOOSTER_GD_WOOCOMMERCE_PLUGIN_URL.'admin/partials/images/logo_globaldev.png'; ?>"/>GlobalDev</a></h6>
                                        </div>
                                        <div>
                                            <?php  
                                            $start_date2 = strtotime(date('Y-m-d') . ' - 1 month');
                                            $end_date2 = strtotime(date('Y-m-d') . ' + 1 day');
                                            $orders = wc_get_orders(array(
                                                'limit'=>-1,
                                                'type'=> 'shop_order',
                                                'status' => array('wc-completed','wc-processing'),
                                                'date_created' => $start_date2.'...'.$end_date2,
                                                //'date_created' => '<' . ( time() - $start_date2 ),
                                                'return' => 'ids'
                                            ));
                                            //print_r(count($orders));
                                            ?>
                                            <div class="">
                                                <h3><?php esc_html_e( 'Available orders', 'sales-booster-gd-woocommerce' ); ?> - <?php echo esc_html(count($orders)); ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
			</div>
		</div>
	<?php }
	
	public function gd_sales_pop_section_info() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
	}
}
if ( is_admin() )
	$gd_sales_pop = new Sales_Booster_Gd_Woocommerce_Options();