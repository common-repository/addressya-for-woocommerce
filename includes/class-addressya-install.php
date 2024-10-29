<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_Install {

    public static function install() {
        do_action( 'addressya_before_install' );
        self::register_settings();
        self::create_database();
        update_option( 'addressya_plugin_version', ADDRESSYA_PLUGIN_VER );
        do_action( 'addressya_after_install' );
    }

    public static function register_settings() {
        foreach ( self::default_options() as $option => $value ) {
            $db_options = get_option( $option );
            if ( ! $db_options ) { // Run when install
                update_option( $option, $value );
            } else { // Run when update
                $merged_option = self::parse_args_r( $db_options, $value );
                update_option( $option, $merged_option );
            }
        }
    }

    public static function default_options() {
        return array(
            'addressya_appearance_settings' => array(
                'background_color'          => '#075367',
                'text_color'                => '#ffffff',
                'about_message'             => 'Duis porta, ligula rhoncus euismod pretium, nisi tellus eleifend odio, luctus viverra sem dolor id sem. Maecenas a venenatis enim',
                'trigger_btn_text'          => 'How can we help?',
                'custom_offer'              => '',
            ),
        );
    }

    public static function create_database() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        
        $table = $wpdb->prefix . 'addressya_credit_application';

        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table'" ) != $table ) {

            $table_sql = "CREATE TABLE $table (
                id BIGINT NOT NULL AUTO_INCREMENT ,
                order_id VARCHAR(64) NOT NULL ,
                uid VARCHAR(64) NOT NULL ,
                company_location_id VARCHAR(128) NOT NULL ,
                contract_start_sate VARCHAR(128) NOT NULL ,
                contract_end_date VARCHAR(128) NOT NULL ,
                status VARCHAR(64) NOT NULL ,
                timestamp VARCHAR(64) NOT NULL ,
                PRIMARY KEY (id)
            ) $charset_collate";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';

            dbDelta( $table_sql );
        }
    }

    public static function parse_args_r( &$args, $defaults ) {
        $a      = (array) $args;
        $b      = (array) $defaults;
        $result = $b;
        foreach ( $a as $k => &$v ) {
            if ( is_array( $v ) && isset( $result[$k] ) ) {
                $result[$k] = self::parse_args_r( $v, $result[$k] );
            } else {
                $result[$k] = $v;
            }
        }
        return $result;
    }
}