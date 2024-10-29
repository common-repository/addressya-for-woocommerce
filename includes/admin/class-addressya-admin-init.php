<?php
defined('ABSPATH') || exit;

class Addressya_Admin_Init
{

    public function __construct()
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        // add_action('addressya_save_credentials', array($this, 'addressya_save_credentials_function'));
        add_action( 'admin_init', array( $this, 'register_plugin_settings' ), 20 );
        add_action( 'wp_ajax_addressya_save_credentials', array( $this, 'addressya_save_credentials' ) );
        add_action( 'wp_ajax_nopriv_addressya_save_credentials', array( $this, 'addressya_save_credentials' ) );
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
        

        add_submenu_page('addressya', 'Addressya Login', 'Addressya Login', 'manage_options', 'addressya-login', array($this, 'admin_addressya_login'));
        // add_submenu_page('addressya', 'Enable Features', 'Enable Features', 'manage_options', 'addressya-submenu2', array($this, 'admin_submenu_2_page'));
        add_submenu_page('addressya', 'Addressya Setting', 'Addressya Setting', 'manage_options', 'addressya-submenu3', array($this, 'admin_submenu_3_page'));
        /* add_submenu_page( 
            'addressya', 
            'Cred App', 
            'Cred App', 
            'manage_options', 
            'addressya_cred-app', 
            array( $this, 'admin_cred_app' ) 
        ); */
    }

    public function admin_settings_page()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-settings-page.php';
    }

    public function admin_addressya_login()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-addressya-login.php';
    }

    public function admin_submenu_2_page()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-submenu2-settings-page.php';
    }

    public function admin_submenu_3_page()
    {
        include_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/admin-submenu3-settings-page.php';
    }

    public function admin_cred_app() {
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/html-cred-app.php';
    }




    public function addressya_save_credentials()
    {
        return "addressya_check_login";
        $addressya_business_name = $_REQUEST['addressya_business_name'];
        $addressya_email = $_REQUEST['addressya_email'];
        $addressya_client_id = $_REQUEST['addressya_client_id'];
        $addressya_password = $_REQUEST['addressya_password'];

        $post_id = null;
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



return new Addressya_Admin_Init();
