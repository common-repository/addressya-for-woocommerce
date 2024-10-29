<?php
defined('ABSPATH') || exit;

class ADDRESSYA_Init
{

    public function init()
    {

        add_action('init', array($this, 'install_updates'), 1);

        /* require_once ADDRESSYA_PLUGIN_PATH . 'includes/addressya-functions.php'; */
        if (is_admin()) {
            require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin.php';
            require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin-scripts.php';
            require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin-api.php';

            if (get_option('addressya_driver_enable') != null &&  get_option('addressya_driver_enable') == 'on') {
                require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin-driver-post.php';
                require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin-driver.php';
            }
            if (get_option('addressya_vehicle_enable') != null &&  get_option('addressya_vehicle_enable') == 'on') {
                require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin-vehicle-post.php';
            }

            if (get_option('addressya_credit_enable') != null &&  get_option('addressya_credit_enable') == 'on') {
                require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin-credit-application.php';
                require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin-dashboard-widget.php';

                
               // require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin-cred-app.php';
            }
            
           // 
        }
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/class-addressya.php';
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/class-addressya-api.php';
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/class-addressya-scripts.php';
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin-driver-functions.php';
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/class-addressya-admin-credit-mapview.php';
    }

    public function install_updates()
    {
        if (ADDRESSYA_PLUGIN_VER == get_option('addressya_plugin_version')) {
            return;
        }

        addressya_plugin_installation();
    }
}

new ADDRESSYA_Init;
