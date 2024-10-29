<?php
defined('ABSPATH') || exit;

class ADDRESSYA_Admin_Vehicle_Post
{

    public function __construct()
    {
        add_action('init', array($this, 'setup_post_type'));
        add_action('add_meta_boxes', array($this, 'register_meta_boxes'));
        add_action('save_post', array($this, 'save_vehicle_meta_data'));
        add_filter( 'manage_edit-addressya_vehicle_columns', array( $this, 'edit_table_columns' ) );
        add_action('manage_addressya_vehicle_posts_custom_column', array($this, 'manage_table_columns'), 10, 2);
    }

    public function setup_post_type()
    {
        $args = array(
            'public'            => true,
            'label'             => 'Vehicles',
            'has_archive'       => true,
            'show_in_menu'      => 'addressya',
            'show_in_admin_bar' => false,
            'supports'          => array('thumbnail'),
        );
        register_post_type('addressya_vehicle', $args);
    }

    public function register_meta_boxes()
    {
        add_meta_box(
            'addressya-vehicle-metabox',
            'Vehicle Data',
            array($this, 'meta_box'),
            'addressya_vehicle',
            'normal',
            'high'
        );
    }

    public function meta_box()
    {
        global $post;
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/meta-boxes/html-vehicle-add.php';
    }

    public function save_vehicle_meta_data($post_id)
    {
        global $post, $wpdb;

        if ($post_id == null || empty($_POST)) {
            return;
        }
        if (!isset($_POST['post_type']) || 'addressya_vehicle' !== $_POST['post_type']) {
            return;
        }
        if (wp_is_post_revision($post_id)) {
            $post_id = wp_is_post_revision($post_id);
        }

        if (isset($_POST['addressya_vehicle_name'])) {
            update_post_meta($post->ID, 'addressya_vehicle_name', sanitize_text_field($_POST['addressya_vehicle_name']));
        }


        $wpdb->update(
            $wpdb->posts,
            array('post_title' => sanitize_text_field($_POST['addressya_vehicle_name'])),
            array('ID' => $post_id)
        );
    }

    public function edit_table_columns($columns)
    {
        $columns = array(
            'addressya_vehicle_name'        => 'Vehicle Username',
        );

        return $columns;
    }

    public function manage_table_columns($column, $post_id)
    {
        switch ($column) {
            case 'addressya_vehicle_name':
                echo get_post_meta($post_id, 'addressya_vehicle_name', true);
                break;
        }
    }
}

new ADDRESSYA_Admin_Vehicle_Post;
