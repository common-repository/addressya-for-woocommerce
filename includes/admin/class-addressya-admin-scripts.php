<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_Admin_Enqueue_Scripts {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 200 );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'jquery-ui', '//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css' );
        wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.13.1/jquery-ui.js' );
        //wp_enqueue_script( 'moment', ADDRESSYA_PLUGIN_URL . 'assets/js/moment-timezone.js', array( 'jquery' ), ADDRESSYA_PLUGIN_VER, true );
        wp_enqueue_script( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/js/admin-addressya-script.js', array( 'jquery' ), ADDRESSYA_PLUGIN_VER, true );
        
        //wp_enqueue_script( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/MDB/js/jquery.min.js', array( 'jquery' ), ADDRESSYA_PLUGIN_VER, true );
        //wp_enqueue_script( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/MDB/js/popper.min.js', array( 'jquery' ), ADDRESSYA_PLUGIN_VER, true );
        //wp_enqueue_script( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/MDB/js/bootstrap.min.js', array( 'jquery' ), ADDRESSYA_PLUGIN_VER, true );
        //wp_enqueue_script( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/MDB/js/mdb.min.js', array( 'jquery' ), ADDRESSYA_PLUGIN_VER, true );
        //wp_enqueue_script( 'admin-addressya', ADDRESSYA_PLUGIN_URL . './assets/MDB/js/addons/datatables.min.js', array( 'jquery' ), ADDRESSYA_PLUGIN_VER, true );


        wp_enqueue_style( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/css/admin-addressya-style.css', array(), ADDRESSYA_PLUGIN_VER );

        //wp_enqueue_style( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/MDB/css/bootstrap.min.css', array(), ADDRESSYA_PLUGIN_VER );
        //wp_enqueue_style( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/MDB/css/mdb.min.css', array(), ADDRESSYA_PLUGIN_VER );
        //wp_enqueue_style( 'admin-addressya', ADDRESSYA_PLUGIN_URL . './assets/MDB/css/addons/datatables.min.css', array(), ADDRESSYA_PLUGIN_VER );
        //wp_enqueue_style( 'admin-addressya', ADDRESSYA_PLUGIN_URL . 'assets/MDB/css/style.css', array(), ADDRESSYA_PLUGIN_VER );
        
        wp_localize_script( 'admin-addressya', 'addressya', array(
            'ajax_url' => admin_url( 'admin-ajax.php?ver=' . uniqid() ),
        ) );
        
    }

}

new ADDRESSYA_Admin_Enqueue_Scripts;