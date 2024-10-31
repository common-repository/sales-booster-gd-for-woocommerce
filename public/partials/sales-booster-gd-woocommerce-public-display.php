<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://globaldev.app/
 * @since      1.0.0
 *
 * @package    Sales_Booster_Gd_Woocommerce
 * @subpackage Sales_Booster_Gd_Woocommerce/public/partials
 */
class Sales_Booster_Gd_Woocommerce_Pu {
	public function __construct() {
	    add_action( 'wp_ajax_gd_run_popup_script', array( $this, 'gd_run_popup_script' ) ) ;
        add_action( 'wp_ajax_nopriv_gd_run_popup_script', array( $this, 'gd_run_popup_script' ) ); 
       
		add_action( 'wp_ajax_sales_booster_add_ip', array( $this, 'sales_booster_add_ip' ) ) ;
        add_action( 'wp_ajax_nopriv_sales_booster_add_ip', array( $this, 'sales_booster_add_ip' ) );
        
        add_action( 'wp_ajax_sales_booster_add_device', array( $this, 'sales_booster_add_device' ) ) ;
        add_action( 'wp_ajax_nopriv_sales_booster_add_device', array( $this, 'sales_booster_add_device' ) );
	}
    public function sales_booster_add_ip(){      
        global $wpdb; 
        $CurrIp = $_SERVER['REMOTE_ADDR'];
        
        $date = date('Y-m-d');
        $time = date('H:i:s', time());
        
        $db_table_name = $wpdb->prefix . 'sales_booster_gd_woocommerce_ip';  // table name
        $gd_woo_pop_devices = $wpdb->prefix . 'sales_booster_gd_woocommerce_ip_devices';
        
        if ( isset($CurrIp) && filter_var($CurrIp, FILTER_VALIDATE_IP) ) {
            $exists = $wpdb->get_var( $wpdb->prepare(
                "SELECT * FROM $db_table_name WHERE ip = '%s'", $CurrIp
              ) );
            if ( ! $exists ) {
                $sql = $wpdb->insert( $db_table_name, array('ip'=>$CurrIp, 'date'=> $date, 'time'=> $time), array( '%s', '%s', '%s' ) );
                $wpdb->query($sql);
            }else{
                //print_r($exists);
            }
        }
        wp_die();
    }
    
    public function sales_booster_add_device(){      
        global $wpdb; 
        $CurrIp = $_SERVER['REMOTE_ADDR'];
        $deVices = isset($_POST['deVices']) && !empty($_POST['deVices']) ? sanitize_text_field($_POST['deVices']) : ''; // info printed on label
        $broWser = isset($_POST['broWser']) && !empty($_POST['broWser']) ? sanitize_text_field($_POST['broWser']) : ''; // info printed on label
        $browserver = isset($_POST['broWserVer']) && !empty($_POST['broWserVer']) ? sanitize_text_field($_POST['broWserVer']) : ''; // info printed on label
        $broWUid = isset($_POST['broWUid']) && !empty($_POST['broWUid']) ? sanitize_text_field($_POST['broWUid']) : ''; // info printed on label
        $onLine = isset($_POST['codref']) && !empty($_POST['codref']) ? sanitize_text_field($_POST['codref']) : ''; // info printed on label
        $date = date('Y-m-d');
        $time = date('H:i:s', time());
        $db_table_name = $wpdb->prefix . 'sales_booster_gd_woocommerce_ip';  // table name
        $gd_woo_pop_devices = $wpdb->prefix . 'sales_booster_gd_woocommerce_ip_devices';
        
        //$sql = $wpdb->prepare("INSERT INTO `$db_table_name` (`ip`, `date`, `devices`) values (%s, %s, %s, %s)", $CurrIp, $date, $deVices);
        
        if ( isset($CurrIp) && filter_var($CurrIp, FILTER_VALIDATE_IP) ) {
            $exists = $wpdb->get_var( $wpdb->prepare(
                "SELECT * FROM $db_table_name WHERE ip = '%s'", $CurrIp
              ) );
            if ( $exists ) {
                if ( isset($broWUid) ){
                $existsD = $wpdb->get_var( $wpdb->prepare(
                    "SELECT * FROM $gd_woo_pop_devices WHERE unibrowid = '%s'", $broWUid
                  ) );
                  if ( ! $existsD ) {
                    $sql = $wpdb->insert( $gd_woo_pop_devices, array('ipid'=>$exists, 'date'=> $date, 'time'=> $time, 'device'=> $deVices, 'browser'=> $broWser, 'browserver'=> $browserver, 'unibrowid'=> $broWUid, 'onLine'=> $onLine), array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' ) );
                    $wpdb->query($sql);
                  }else{
                    //print_r($existsD);
                  }
                 }  
            }
        }
        wp_die();
    }
    /**
     * Pop up function
     */
    public function gd_run_popup_script() {
        global $wpdb;
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
        
        if($orders){
            $ran_order_id = $orders[array_rand($orders)];
        
            $order = wc_get_order( $ran_order_id );
            
            $items = $order->get_items();
            
            $order_data = $order->get_data(); 
            //order status
            $order_status = $order_data['status'];
    
            $order->get_billing_first_name();
            $order->get_billing_last_name();
            $now = time();
            
            $order_date = strtotime($order->order_date);
            $order_time = $order->order_date;
        
            $datediff = abs($now-$order_date);
            //$get_dates = floor($datediff / (365*60 * 60 * 24));
            $years = floor($datediff / (365*60*60*24));
            $months = floor(($datediff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($datediff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
            
            $curr_diff = floor($datediff / (60));
            if($curr_diff<60){
                //$pri_time = $curr_diff." minutes ago";
                $pri_time = __( 'Recently bought', 'sales-booster-gd-woocommerce' );
            }else if($curr_diff<=1440){
                $pri_time = sprintf( __( '%s hours ago', 'sales-booster-gd-woocommerce' ), round($curr_diff/(60)) );
            }else if($curr_diff<=2880){
                $pri_time = __( 'Yesterday', 'sales-booster-gd-woocommerce' );
            }else {
                $pri_time = sprintf( __( '%s days ago', 'sales-booster-gd-woocommerce' ), $days );
            }
    
            $print_time2 = $years."years, ".$months."months, ".$days."days ago";
            
            $animation_array = array('backInLeft', 'bounce', 'rubberBand', 'shakeX', 'headShake', 'bounceInUp', 'bounceInLeft');
            
            $product_id = array();
            foreach ( $items as $key => $item ) {
                $product_name = $item->get_name();
                $product_id[] = $item->get_product_id();
                $product_variation_id = $item->get_variation_id();
            }
            $product_all_id = $product_id[array_rand($product_id)];
            $ani_rand = $animation_array[array_rand($animation_array)];
            
            $title_p = esc_attr(get_the_title($product_all_id));
            
            $title_out = strlen($title_p) > 30 ? substr($title_p,0,30)."..." : $title_p;
            
            $outpus = "";
            $outpus .= "<div class='pop-div animate__animated animate__".$ani_rand."'>
            <a href='#' class='close-sbgd'>x</a>
            <div class='pop-div-inner'>"
            
            //."<div  class='globaldev-img'><img src='".plugin_dir_url( __FILE__ )."/public/images/logo_globaldev.png'/></div>"
            ."<div  class='globaldev-img'>".get_the_post_thumbnail($product_all_id)."</div>"
            ."<div class='pop-div-text-wrap'><div class='pop-div-top'><h4>".esc_attr($order->get_billing_first_name()).' '.__( 'from', 'sales-booster-gd-woocommerce' ).' '.esc_attr($order->get_billing_city()).", ".esc_attr($order->get_billing_country())."</h4></div>"
            ."<div class='pop-div-low'><h5><a href=".get_the_permalink($product_all_id).">" .__( 'Bought', 'sales-booster-gd-woocommerce' )." ".$title_p. "</a></h5></div>
            <div class='pop-div-footer'>
            <div class='pop-div-f-l'>
            
            <p>".$pri_time."</p>
            </div>
            <div class='pop-div-f-r'>
            <a href='https://www.globaldev.app/' target='_blank'>".__( 'By', 'sales-booster-gd-woocommerce' )."<img src='".esc_url(SALES_BOOSTER_GD_WOOCOMMERCE_PLUGIN_URL)."/includes/images/logo_globaldev.png'/> Globaldev</a>
            </div>
            </div>
            </div>
            </div>
            </div>" ;
            
            print $outpus;
        }
        
        
        die(); // this is required to return a proper result
    }
}
$sales_booster_gd_woocommerce_pu = new Sales_Booster_Gd_Woocommerce_Pu();