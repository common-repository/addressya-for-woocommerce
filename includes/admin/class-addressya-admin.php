<?php
defined('ABSPATH') || exit;

class Addressya_Admin
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        // add_action('addressya_save_credentials', array($this, 'addressya_save_credentials_function'));
        add_action( 'admin_init', array( $this, 'register_plugin_settings' ), 20 );
        add_action( 'wp_ajax_addressya_save_credentials', array( $this, 'addressya_save_credentials' ) );
        add_action( 'wp_ajax_nopriv_addressya_save_credentials', array( $this, 'addressya_save_credentials' ) );
        
        add_action( 'wp_ajax_addressya_verify_credentials', array( $this, 'addressya_verify_credentials' ) );
        add_action( 'wp_ajax_nopriv_addressya_verify_credentials', array( $this, 'addressya_verify_credentials' ) );
        add_action( 'wp_ajax_addressya_request_address_score', array( $this, 'addressya_request_address_score' ) );
        add_action( 'wp_ajax_nopriv_addressya_request_address_score', array( $this, 'addressya_request_address_score' ) );
        add_action( 'wp_ajax_addressya_get_location', array( $this, 'get_location' ) );
        add_action( 'wp_ajax_nopriv_addressya_get_location', array( $this, 'get_location' ) ); 
        
        add_action( 'wp_ajax_addressya_get_location_score', array( $this, 'get_location_score' ) );
        add_action( 'wp_ajax_nopriv_addressya_get_location_score', array( $this, 'get_location_score' ) );
        add_action( 'wp_ajax_addressya_get_credits', array( $this, 'get_credits' ) );
        add_action( 'wp_ajax_nopriv_addressya_get_credits', array( $this, 'get_credits' ) ); 
        
        add_action( 'wp_ajax_addressya_update_credit', array( $this, 'update_credits' ) );
        add_action( 'wp_ajax_nopriv_addressya_update_credit', array( $this, 'update_credits' ) ); 
        
    }
     

    /* public function admin_enqueue_scripts( $hook ) {
        
        wp_enqueue_script( 'jquery-ui', '//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css' );
        wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.13.1/jquery-ui.js' );
        wp_enqueue_script( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/js/admin-addressya-script.js', array( 'jquery' ), ADDRESSYA_PLUGIN_VER, true );
        wp_enqueue_style( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/css/admin-addressya-style.css', array(), ADDRESSYA_PLUGIN_VER );
        wp_localize_script( 'admin-addressya', 'addressyaAdmin', array(
            'ajax_url' => admin_url( 'admin-ajax.php?ver=' . uniqid() ),
        ) );

    } */

    public function admin_menu()
    {
        add_menu_page(
            'Addressya',
            'Addressya',
            'manage_options',
            'addressya',
            '',
            ADDRESSYA_PLUGIN_URL . 'assets/images/addressya_flower.svg',
            array($this, 'admin_settings_page')
        );
        
        add_submenu_page('addressya', 'Driver', 'Driver', 'manage_options', 'driver', array($this, 'admin_driver'));

        add_submenu_page('addressya', 'Addressya Login', 'Addressya Login', 'manage_options', 'addressya-login', array($this, 'admin_addressya_login'));
        add_submenu_page('addressya', 'Credit Application', 'Credit Application', 'manage_options', 'credit-app-map-view', array($this, 'admin_credit_app_map_view'));
        // add_submenu_page('addressya', 'Enable Features', 'Enable Features', 'manage_options', 'addressya-submenu2', array($this, 'admin_submenu_2_page'));
        add_submenu_page('addressya', 'Addressya Setting', 'Addressya Setting', 'manage_options', 'addressya-submenu3', array($this, 'admin_submenu_3_page'));
        add_submenu_page('addressya', null, null, 'manage_options', 'credit-app-update', array($this, 'admin_credit_app_update'));
        add_submenu_page('addressya', null, null, 'manage_options', 'add-driver', array($this, 'admin_add_driver'));
        add_submenu_page('addressya', null, null, 'manage_options', 'update-driver', array($this, 'admin_update_driver'));

    }

    public function get_location(){        
        $uid = $_REQUEST['uid'];
        $companyLid = $_REQUEST['companyLid'];
        $response = ADDRESSYA_Admin_API::getLocation( $uid, $companyLid );
        return $response;
        //var_dump($response);
    }

    public function get_credits(){        
        $order_id = $_REQUEST['order_id'];
        $response = ADDRESSYA_Admin_API::getCredit( $order_id );
        return $response;
        //var_dump($response);
    }

    public function get_location_score(){        
        $uid = $_REQUEST['uid'];
        $locationLid = $_REQUEST['locationLid'];
        $response = ADDRESSYA_Admin_API::getLocation( $uid, $locationLid );
        return $response;
        //var_dump($response);
    }

    public function update_credits(){        
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];
        $credit_contractStartDate = strtotime($_POST['credit_contractStartDate']) * 1000;
        $credit_contractEndDate = strtotime($_POST['credit_contractEndDate']) * 1000;
       /*  echo $credit_contractStartDate;
        echo "<br>";
        echo $credit_contractEndDate;die(); */

        $response = ADDRESSYA_Admin_API::update_status_credit_application( $order_id, $status, $credit_contractStartDate, $credit_contractEndDate);
        return $response;
    }


    public function admin_settings_page()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-settings-page.php';
    }

    public function admin_addressya_login()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-addressya-login.php';
    }

    public function admin_credit_app_map_view()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-credit-app-map-view.php';
    }

    public function admin_driver()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-driver.php';
    }

    public function admin_credit_app_update()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-credit-app-update.php';
    }
    public function admin_add_driver()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-add-driver.php';
    }
    public function admin_update_driver()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-update-driver.php';
    }
    public function admin_submenu_3_page()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-submenu3-settings-page.php';
    }

    public function admin_cred_app() {
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/html-cred-app.php';
    }


    public function addressya_verify_credentials()
    {
        //return "addressya_check_login";
        /* $addressya_business_name = $_REQUEST['addressya_business_name'];
        $addressya_email = $_REQUEST['addressya_email'];
        $addressya_client_id = $_REQUEST['addressya_client_id'];
        $addressya_password = $_REQUEST['addressya_password']; */
        $loginCheck = ADDRESSYA_Admin_API::get_business_detail( $_REQUEST );
        //echo print_r($loginCheck);wp_die();
        //$loginCheck = $this->model_extension_module_addressyalogin->checkLogin($this->request->post);
        if (empty($loginCheck['Organization'])) {
            $json['error']['warning'] = $this->language->get('error_credentials');
        }
        if (isset($loginCheck['access_token'])) {
            //wp_die("access_token");
            if(!empty($loginCheck['Organization'])){
                $json['user_uid'] = $loginCheck['user_uid'];
                $json['organizationData'] = $loginCheck['Organization'];
                $json['message'] = "Your credentials are verified. Please select organization and save your details!";
            }
        } else {
            $json['error']['warning'] = $this->language->get('error_credentials');
        }
        //echo print_r($json);wp_die();
        //return $json;
        wp_send_json_success($json);
    }

    public function addressya_request_address_score()
    {
        $request = ADDRESSYA_Admin_API::requestScore( $_REQUEST['cus_id'], $_REQUEST['loc_id'] );
        //echo print_r($request);wp_die();             
        //echo print_r($json);wp_die();
        //return $json;
        wp_send_json_success($request);
    }
    
   

    public function addressya_save_credentials()
    {
        update_option( 'addressya_user_uid', $_REQUEST['addressya_user_uid'] );
        update_option( 'addressya_login_email', $_REQUEST['addressya_email'] );
        update_option( 'addressya_client_id', $_REQUEST['addressya_client_id'] );
        update_option( 'addressya_password', $_REQUEST['addressya_password'] );
        update_option( 'addressya_business_name', $_REQUEST['addressya_business_name'] );
        update_option( 'addressya_business_id', $_REQUEST['addressya_business_id'] );

    }

    public function register_plugin_settings() {
        register_setting( 'addressya_credentials', 'addressya_business_name','sanitize_text_field' );
        register_setting( 'addressya_credentials', 'addressya_email','sanitize_text_field' );
        register_setting( 'addressya_credentials', 'addressya_client_id','sanitize_text_field' );
        register_setting( 'addressya_credentials', 'addressya_password','sanitize_text_field' );

        register_setting( 'adressya_features', 'addressya_driver_enable','sanitize_text_field' );
        register_setting( 'adressya_features', 'addressya_vehicle_enable','sanitize_text_field' );
        register_setting( 'adressya_features', 'addressya_ecom_enable','sanitize_text_field' );
        register_setting( 'adressya_features', 'addressya_credit_enable','sanitize_text_field' );

    }

   
      
    public function sanitize_addressya_credentials( $input ) {
        $input['addressya_business_name']       = sanitize_textarea_field( $input['addressya_business_name'] );
        $input['addressya_email']  = sanitize_textarea_field( $input['addressya_email'] );
        $input['addressya_client_id']  = sanitize_textarea_field( $input['addressya_client_id'] );
        $input['addressya_password']  = sanitize_textarea_field( $input['addressya_password'] );
        return $input;
    }
}



return new Addressya_Admin();
