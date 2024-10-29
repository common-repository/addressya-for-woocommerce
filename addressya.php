<?php
/**
* Addressya for E-commerce
*
* @author      Addressya
* @copyright   2020 Addressya
* @license     GPL-2.0-or-later
*
* @wordpress-plugin
* Plugin Name: Addressya for E-commerce
* Plugin URI:  https://developer.addressya.com/addressya-for-woocommerce
* Description: Addressya provides shoppers with a smoother checkout process on your site with the one field address checkout and simplified driver dispatch for more efficient home deliveries. The Addressya for WooCommerce plugin can only be activated when the business has already installed WooCommerce to its site.
* Version:     3.1.1
* Author:      Addressya
* Author URI:  https://addressya.com
* Text Domain: addressya
* License:     GPL v2 or later
* Domain Path: /languages/
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'ADDRESSYA_PLUGIN_FILE' ) ) {
    define( 'ADDRESSYA_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'ADDRESSYA_PLUGIN_PATH' ) ) {
    define( 'ADDRESSYA_PLUGIN_PATH', plugin_dir_path( ADDRESSYA_PLUGIN_FILE ) );
}

if ( ! defined( 'ADDRESSYA_PLUGIN_URL' ) ) {
    define( 'ADDRESSYA_PLUGIN_URL', plugin_dir_url( ADDRESSYA_PLUGIN_FILE ) );
}

if ( ! defined( 'ADDRESSYA_PLUGIN_VER' ) ) {
    define( 'ADDRESSYA_PLUGIN_VER', '3.1.1' );
}

if ( ! defined( 'ADDRESSYA_ENV' ) ) {
    define( 'ADDRESSYA_ENV', 'prod' );
}

function addressya_plugin_installation() {
    require_once ADDRESSYA_PLUGIN_PATH . 'includes/class-addressya-install.php';
    ADDRESSYA_Install::install();
}
register_activation_hook( ADDRESSYA_PLUGIN_FILE, 'addressya_plugin_installation' );

function addressya_plugin_init() {
    require_once ADDRESSYA_PLUGIN_PATH . 'includes/class-addressya-init.php';
    $addressya = new ADDRESSYA_Init;
    return $addressya->init();
}
add_action( 'plugins_loaded', 'addressya_plugin_init', 50 );

//  Disable payment gateway
add_filter( 'woocommerce_cart_needs_payment', 'addressya_payment_method_disable' );

function addressya_payment_method_disable($this_total_0) {
    if (get_option('addressya_credit_enable') != null &&  get_option('addressya_credit_enable') == 'on') {
        return false;
    }
    return $this_total_0;
}

// For adding addressya username in admin order detail for billing
add_action('woocommerce_admin_order_data_after_billing_address', 'addressya_add_addressya_username_admin_bill');

function addressya_add_addressya_username_admin_bill($order) {
    $admin = new ADDRESSYA_Admin_API();
    $admin->add_addressya_username_admin_bill($order);
}

// add the action 
add_action( 'woocommerce_admin_order_data_after_order_details', 'action_woocommerce_admin_order_data_after_order_details', 10, 1 ); 

function action_woocommerce_admin_order_data_after_order_details( $order ) { 
    if (get_option('addressya_credit_enable') != null &&  get_option('addressya_credit_enable') == 'on') {
        $admin = new ADDRESSYA_Admin_API();
        $admin->show_credit_info($order);
    }
}      


// add the action 
add_action( 'woocommerce_checkout_process', 'action_woocommerce_checkout_process', 10, 1 ); 
// define the woocommerce_checkout_process callback 
function action_woocommerce_checkout_process( $wccs_custom_checkout_field_pro_process ) { 
    if($_POST['addressya_checkout_option'] == 'addressya_address'){
        if($_POST['addressya_billing_first_name'] == ''){
            wc_add_notice('First name is required field.', 'error');
        }
        if($_POST['addressya_billing_last_name'] == ''){
            wc_add_notice('Last name is required field.', 'error');
        }
        if($_POST['addressya_billing_email'] == ''){
            wc_add_notice('Email is required field.', 'error');
        }
        if($_POST['addressya_billing_phone'] == ''){
            wc_add_notice('Phone is required field.', 'error');
        }
        if($_POST['addressya_username'] == ''){
            wc_add_notice('Addressya username is required field.', 'error');
        } else if($_POST['addressya_username_verified'] != "true"){
            wc_add_notice('Addressya username is not verified.', 'error');
        } else if($_POST['addressya_location_confirmed'] != "true"){
            wc_add_notice('Please confirm the Addressya location.', 'error');
        }
    } else if($_POST['addressya_checkout_option'] == 'default_address'){

    }
}; 

// Set billing address fields to not required
add_filter( 'woocommerce_checkout_fields', 'unrequire_checkout_fields' );

function unrequire_checkout_fields( $fields ) {
    
    if(isset($_POST['addressya_checkout_option']) && $_POST['addressya_checkout_option'] == 'addressya_address'){
        
        $fields['billing']['billing_company']['required']   = false;
        $fields['billing']['billing_city']['required']      = false;
        $fields['billing']['billing_postcode']['required']  = false;
        $fields['billing']['billing_country']['required']   = false;
        $fields['billing']['billing_state']['required']     = false;
        $fields['billing']['billing_address_1']['required'] = false;
        $fields['billing']['billing_address_2']['required'] = false;
        $fields['billing']['billing_first_name']['required'] = false;
        $fields['billing']['billing_last_name']['required'] = false;
        $fields['billing']['billing_email']['required'] = false;
        $fields['billing']['billing_phone']['required'] = false;
        $_POST['billing_first_name'] = $_POST['addressya_billing_first_name'];
        $_POST['billing_last_name'] = $_POST['addressya_billing_last_name'];
        $_POST['billing_email'] = $_POST['addressya_billing_email'];
        $_POST['billing_phone'] = $_POST['addressya_billing_phone'];

        
    }
    return $fields;
} 

