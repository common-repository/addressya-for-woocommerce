<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA {

    public function __construct() {
        add_action( 'woocommerce_before_checkout_billing_form', array( $this, 'addressya_checkout_form' ) );
        add_action( 'woocommerce_before_checkout_billing_form', array( $this, 'addressya_checkout_verify_otp_form' ) );
        add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'addressya_checkout_create_addressya_account' ) );        
        //add_action( 'woocommerce_after_checkout_billing_form', array( $this, 'action_woocommerce_after_checkout_billing_form' ) );        

        add_action('woocommerce_checkout_update_order_meta',function( $order_id, $posted ) {
            $addressya_username = WC()->session->get( 'addressya_sess_username' );
            $locationId = WC()->session->get( 'locationId' );
            $addressyaUid = WC()->session->get( 'addressya_user_uid' );

            if($addressya_username != ''){
                $order = wc_get_order( $order_id );
                $order->update_meta_data( '_billing_addressya_username', $addressya_username );
                $order->update_meta_data( '_billing_addressya_locationId', $locationId );
                $order->update_meta_data( '_billing_addressya_uid', $addressyaUid );
                $location_data = ADDRESSYA_API::getLocationForOrder( $addressyaUid, $locationId );
                
                $order->save();
                $post_id = wp_insert_post(
                    array(
                        'post_type'      => 'addressya_creditapp',
                        'post_status'    => 'publish',
                        'post_title'     => $addressya_username,                        
                    ),
                    true
                );

                if ( $post_id && ! is_wp_error( $post_id ) ) {
                    update_post_meta( $post_id, 'order_id', wp_slash(  $order_id ) );
                    update_post_meta( $post_id, '_order_total', wp_slash(  get_post_meta( $order_id, '_order_total', true ) ) );
                    update_post_meta( $post_id, '_billing_first_name', wp_slash(  get_post_meta( $order_id, '_billing_first_name', true ) ) );
                    update_post_meta( $post_id, '_billing_last_name', wp_slash(  get_post_meta( $order_id, '_billing_last_name', true ) ) );
                    update_post_meta( $post_id, '_billing_addressya_username', wp_slash(  $addressya_username ) );
                    update_post_meta( $post_id, '_billing_addressya_uid', wp_slash(  $addressyaUid ) );
                    update_post_meta( $post_id, '_billing_addressya_credit_status', wp_slash( 'pending' ) );
                    update_post_meta( $post_id, '_billing_addressya_credit_contractStartDate', '' );
                    update_post_meta( $post_id, '_billing_addressya_credit_contractEndDate', '' ); 
                    update_post_meta( $post_id, '_billing_addressya_location_lat', $location_data['data'][0]['position']['latitude'] ); 
                    update_post_meta( $post_id, '_billing_addressya_location_lon', $location_data['data'][0]['position']['longitude'] ); 
                    update_post_meta( $post_id, '_billing_addressya_location_city', $location_data['data'][0]['details']['city'] ); 
                    update_post_meta( $post_id, '_billing_addressya_location_region', $location_data['data'][0]['details']['region'] ); 
                    update_post_meta( $post_id, '_billing_addressya_location_country', $location_data['data'][0]['details']['country'] ); 
                    
                    if (get_option('addressya_credit_enable') != null &&  get_option('addressya_credit_enable') == 'on') {
                        $companyLocationId = WC()->session->get( 'companyLocationId' );
                        $billingFirstName = get_post_meta( $order_id, '_billing_first_name', true );
                        $billingLastName = get_post_meta( $order_id, '_billing_last_name', true );
                        $orderAmount = get_post_meta( $order_id, '_order_total', true );
                        update_post_meta( $post_id, '_billing_addressya_credit_companyLocationId', wp_slash( $companyLocationId ) );   
                        $response = ADDRESSYA_API::update_credit_application( $addressya_username, $billingFirstName, $billingLastName,'pending', $addressyaUid, $order_id, $orderAmount, $locationId, $companyLocationId );              
                    }
                    $message = "You have placed an order with " . get_option( 'addressya_business_name' ) . ". Order reference : " . $order_id;
                    ADDRESSYA_API::sendNotification($locationId, $addressyaUid, '', $addressya_username, 'order-placed', $message);
                }
            }
        } , 10, 2);
        
        add_action( 'wp_ajax_addressya_send_otp', array( $this, 'send_otp' ) );
        add_action( 'wp_ajax_nopriv_addressya_send_otp', array( $this, 'send_otp' ) );

        add_action( 'wp_ajax_addressya_verify_otp', array( $this, 'verify_otp' ) );
        add_action( 'wp_ajax_nopriv_addressya_verify_otp', array( $this, 'verify_otp' ) );
        
        add_action( 'wp_ajax_addressya_add_user_address', array( $this, 'add_user_address' ) );
        add_action( 'wp_ajax_nopriv_addressya_add_user_address', array( $this, 'add_user_address' ) );

        add_action( 'wp_ajax_addressya_update_emp_detail', array( $this, 'update_emp_detail' ) );
        add_action( 'wp_ajax_nopriv_addressya_update_emp_detail', array( $this, 'update_emp_detail' ) );

        add_action( 'wp_ajax_addressya_confirm_location', array( $this, 'confirm_location' ) );
        add_action( 'wp_ajax_nopriv_addressya_confirm_location', array( $this, 'confirm_location' ) );
        
        add_action( 'wp_ajax_addressya_create_account', array( $this, 'create_account' ) );
        add_action( 'wp_ajax_nopriv_addressya_create_account', array( $this, 'create_account' ) );    
        
    }

    public function addressya_checkout_form() {

        require_once ADDRESSYA_PLUGIN_PATH . 'views/html-checkout.php';
    }

    public function addressya_checkout_verify_otp_form() {
        //require_once ADDRESSYA_PLUGIN_PATH . 'views/html-checkout-verify-otp.php';
    }

    public function addressya_checkout_create_addressya_account() {
        require_once ADDRESSYA_PLUGIN_PATH . 'views/html-addressya-account.php';
    }

    public function send_otp(){
        $username = $_REQUEST['username'];
        $response = ADDRESSYA_API::send_otp_v2( $username );
        return $response;
    }

    public function verify_otp(){
        
        $response = ADDRESSYA_API::verify_otp_v2( $_REQUEST );
        return $response;
    }

    public function add_user_address(){        
        $response = ADDRESSYA_API::add_user_address_v2( $_REQUEST );
        return $response;
    }

    public function update_emp_detail(){        
        $response = ADDRESSYA_API::update_emp_detail_v2( $_REQUEST );
        return $response;
    }

    public function confirm_location(){        
        $response = ADDRESSYA_API::confirm_location_v2( $_REQUEST );
        return $response;
    }
/* 
    public static function addressya_account(){
        echo "<pre>";
        print_r($_REQUEST);
        echo "addressya_account";
        return;
    }
    
    // define the woocommerce_checkout_process callback 
    function action_woocommerce_after_checkout_billing_form( $wccs_custom_checkout_field_pro_process ) { 
        
        ADDRESSYA::addressya_account();

    };  */

    function createNewAddressyaUser($data) {
        if (empty($data['streetName']) || empty($data['city']) || empty($data['country']) || empty($data['firstName']) || empty($data['lastName'])) {
            return 'Please fill all the required details above';
            //wc_add_notice('Please fill all the required details above.', 'error');
        }
        $userNameAvailable = ADDRESSYA_API::is_user_available( $data['userName']);
        if ($userNameAvailable['status'] != 200) {
            return $userNameAvailable;
        } 
        $response = ADDRESSYA_API::create_account($data);
        if(isset($response) && $response['message'] == 'Account created') {
            WC()->session->set('locationId', $response['location_id']);
            WC()->session->set('addressya_user_uid', $response['user_uid']);

            if (get_option('addressya_credit_enable') != null &&  get_option('addressya_credit_enable') == 'on') {
                $data['addressya_user_bearer_token'] = $response['token'];
                $data['addressya_username'] = $data['userName'];
                $response = ADDRESSYA_API::update_emp_detail_v2($data);
                if(isset($response_emp_detail) && $response_emp_detail['message'] == 'Success') {
                    WC()->session->set('companyLocationId', $response_emp_detail['companyLocation']['lid']);
                }
            }
            return $response;
        } else {
            $response['status'] = 422;
            $response['success'] = false;
            $response['message'] = 'Email Already Exists';
            return $response;
        }
    }

    public function create_account() { 

        $newUserObj = array(
            'firstName'    => sanitize_text_field($_REQUEST['firstname']),
            'lastName'     => sanitize_text_field($_REQUEST['lastname']),
            'email'        => sanitize_text_field($_REQUEST['addressya_email']),
            'password'     => sanitize_text_field($_REQUEST['addressya_password']),
            'displayEmail' => sanitize_text_field($_REQUEST['addressya_email']),
            'phoneNumber'  => sanitize_text_field($_REQUEST['addressya_telephone']),
            'userName'     => sanitize_text_field($_REQUEST['addressya_username']),
            'isd'          => "+263",
            'city'         => sanitize_text_field($_REQUEST['addressya_city']),
            'country'      => sanitize_text_field($_REQUEST['addressya_country']),
            'region'       => sanitize_text_field($_REQUEST['addressya_state']),
            'apartment'    => sanitize_text_field($_REQUEST['addressya_address_2']),
            'streetName'   => sanitize_text_field($_REQUEST['addressya_address_1']),
            'addressya_company_name'        => sanitize_text_field($_REQUEST['company_name']),
            'addressya_company_country'     => sanitize_text_field($_REQUEST['company_country']),
            'addressya_company_city'        => sanitize_text_field($_REQUEST['company_city']),
            'addressya_company_state'       => sanitize_text_field($_REQUEST['company_state']),
            'addressya_company_address1'    => sanitize_text_field($_REQUEST['company_address1']),
            'addressya_company_address2'    => sanitize_text_field($_REQUEST['company_address2'])
        );
        $results = self::createNewAddressyaUser($newUserObj);
        wp_send_json_success($results);
        
    }

    public function search_by_username($username){
        //$username = $_REQUEST['username'];
        $response = ADDRESSYA_API::is_user_exists( $username );
        
        return $response;
    }

}

return new ADDRESSYA();