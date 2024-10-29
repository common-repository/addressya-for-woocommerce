<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_Admin_Driver_Post {

    public function __construct() {
        //add_action( 'init', array( $this, 'setup_post_type' ) );
        //add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
       // add_action( 'save_post', array( $this, 'save_driver_meta_data' ) );
       // add_filter( 'manage_edit-addressya_driver_columns', array( $this, 'edit_table_columns' ) );
        //add_action( 'manage_addressya_driver_posts_custom_column', array( $this, 'manage_table_columns' ), 10, 2 );
    }

    public function setup_post_type() {
        $args = array(
            'public'            => true,
            'label'             => 'Drivers',
            'has_archive'       => true,
            'show_in_menu'      => 'addressya',
            'show_in_admin_bar' => false,
            'supports'          => array( 'thumbnail' ),
        );
        register_post_type( 'addressya_driver', $args );
    }

    public function register_meta_boxes() {
        add_meta_box(
            'addressya-driver-metabox',
            'Driver Detail',
            array( $this, 'meta_box' ),
            'addressya_driver',
            'normal',
            'high'
        );
    }

    public function meta_box() {
        global $post;
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/meta-boxes/html-driver-add.php';
    }

    public function save_driver_meta_data( $post_id ) {
        global $post, $wpdb;
        if ( $post_id == null || empty( $_POST ) ) {
            return;
        }
        if ( ! isset( $_POST['post_type'] ) || 'addressya_driver' !== $_POST['post_type'] ) {
            return;
        }
        if ( wp_is_post_revision( $post_id ) ) {
            $post_id = wp_is_post_revision( $post_id );
        }
        if ( !isset( $_POST['addressya_driver_firstname'] ) && $_POST['addressya_driver_firstname'] == '' ) {
            echo "<pre>";
        echo "error";
        wp_die();
            $error[] = "Please enter first name";
        }
        //WC()->session->set('addressya_firstname', $error);
        WC()->session->set('addressya_firstname','asfsdfsdf');
        echo "<pre>";
        print_r($error);
        wp_die();
         $timeZone =  strval($_POST['addressya_driver_timezone']);
        
        if ( isset( $_POST['addressya_driver_username'] ) ) {
            $userNameAvailable = ADDRESSYA_API::is_user_exists( $_POST['addressya_driver_username'] );
            if ($userNameAvailable['status'] != 200) {
                /* echo "<pre>";
                print_r($userNameAvailable);
                wp_die(); */
                return $userNameAvailable['message'];
            } else {
                

                $response = ADDRESSYA_Admin_API::addDriverUnderOrganization($userNameAvailable['data']['id'], $_POST['addressya_driver_start_time'], $_POST['addressya_driver_end_time'], $timeZone);
                /* echo "<pre>";
                print_r($response);
                wp_die(); */
            }
            update_post_meta( $post->ID, 'addressya_driver_username', sanitize_text_field( $_POST['addressya_driver_username'] ) );
            update_post_meta( $post->ID, 'addressya_driver_uid', sanitize_text_field( $userNameAvailable['data']['id'] ) );
        }
        if ( isset( $_POST['addressya_driver_firstname'] ) ) {
            update_post_meta( $post->ID, 'addressya_driver_firstname', sanitize_text_field( $_POST['addressya_driver_firstname'] ) );
        }

        if ( isset( $_POST['addressya_driver_lastname'] ) ) {
            update_post_meta( $post->ID, 'addressya_driver_lastname', sanitize_text_field( $_POST['addressya_driver_lastname'] ) );
        }

        if ( isset( $_POST['addressya_driver_email'] ) ) {
            update_post_meta( $post->ID, 'addressya_driver_email', sanitize_text_field( $_POST['addressya_driver_email'] ) );
        }

        if ( isset( $_POST['addressya_driver_vehicle'] ) ) {
            update_post_meta( $post->ID, 'addressya_driver_vehicle', sanitize_text_field( $_POST['addressya_driver_vehicle'] ) );
        }

        if ( isset( $_POST['addressya_driver_vehicle_number'] ) ) {
            update_post_meta( $post->ID, 'addressya_driver_vehicle_number', sanitize_text_field( $_POST['addressya_driver_vehicle_number'] ) );
        }

        if ( isset( $_POST['addressya_driver_start_time'] ) ) {
            update_post_meta( $post->ID, 'addressya_driver_start_time', sanitize_text_field( $_POST['addressya_driver_start_time'] ) );
        }
        if ( isset( $_POST['addressya_driver_end_time'] ) ) {
            update_post_meta( $post->ID, 'addressya_driver_end_time', sanitize_text_field( $_POST['addressya_driver_end_time'] ) );
        }
        if ( isset( $_POST['addressya_driver_timezone'] ) ) {
            update_post_meta( $post->ID, 'addressya_driver_timezone', sanitize_text_field( $_POST['addressya_driver_timezone'] ) );
        }
        if ( isset( $_POST['addressya_driver_status'] ) ) {
            update_post_meta( $post->ID, 'addressya_driver_status', sanitize_text_field( $_POST['addressya_driver_status'] ) );
        }

        if ( isset( $_POST['addressya_driver_number'] ) ) {
            update_post_meta( $post->ID, 'addressya_driver_number', sanitize_text_field( $_POST['addressya_driver_number'] ) );
        }

        //addDriverUnderOrganization

        $wpdb->update(
            $wpdb->posts,
            array( 'post_title' => sanitize_text_field( $_POST['addressya_driver_username'] ) ),
            array( 'ID' => $post_id )
        );
    }

    public function edit_table_columns( $columns ) {
        /* $columns['addressya_driver_username']   = 'Username';
        $columns['addressya_driver_firstname']  = 'First Name';
        $columns['addressya_driver_lastname'] = 'Lastname';
        $columns['addressya_driver_email']   = 'Email';
        $columns['addressya_driver_vehicle']   = 'Vehicle';
        $columns['addressya_driver_vehicle_number']   = 'Vehicle Number';
        $columns['addressya_driver_number']   = 'Number';
        $columns['date']   = 'Date';
 */
        $columns = array(
            'title' => 'Username',
            'addressya_driver_firstname' => 'First Name',
            'addressya_driver_lastname' => 'Last Name',
            'addressya_driver_number' => 'Number',
            'addressya_driver_status' => 'Status',
            'date' => 'Date',
        );

        return $columns;
    }

    public function manage_table_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'addressya_driver_username':
                echo get_post_meta( $post_id, 'addressya_driver_username', true );
                break;  

            case 'addressya_driver_firstname':
                echo get_post_meta( $post_id, 'addressya_driver_firstname', true );
                break;  
                          
            case 'addressya_driver_lastname':
                echo get_post_meta( $post_id, 'addressya_driver_lastname', true );
                break;  

            case 'addressya_driver_email':
                echo get_post_meta( $post_id, 'addressya_driver_email', true );
                break;   

            case 'addressya_driver_vehicle':
                echo get_post_meta( $post_id, 'addressya_driver_vehicle', true );
                break;  

            case 'addressya_driver_vehicle_number':
                echo get_post_meta( $post_id, 'addressya_driver_vehicle_number', true );
                break; 

            case 'addressya_driver_number':
                echo get_post_meta( $post_id, 'addressya_driver_number', true );
                break;  

            case 'addressya_driver_email':
                echo get_post_meta( $post_id, 'addressya_driver_email', true );
                break;            
        }
    }
}

new ADDRESSYA_Admin_Driver_Post;