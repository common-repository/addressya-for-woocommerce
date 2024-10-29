<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_Enqueue_Scripts {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 200 );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'public-addressya', ADDRESSYA_PLUGIN_URL . 'assets/css/public-addressya-style.css', array(), ADDRESSYA_PLUGIN_VER );
        wp_enqueue_script( 'public-addressya', ADDRESSYA_PLUGIN_URL . 'assets/js/public-addressya-script.js', array( 'jquery' ), ADDRESSYA_PLUGIN_VER, true );
        wp_localize_script( 'public-addressya', 'addressya', array(
            'ajax_url' => admin_url( 'admin-ajax.php?ver=' . uniqid() ),
        ) );
        
    }

}

new ADDRESSYA_Enqueue_Scripts;