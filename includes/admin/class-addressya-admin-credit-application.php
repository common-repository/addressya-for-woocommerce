<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_Admin_Creditapp_Post {

    
    public function __construct() {
        //add_action( 'init', array( $this, 'setup_post_type' ) );
        //add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
        add_action( 'save_post', array( $this, 'save_creditapp_meta_data' ) );
        add_action( 'update_post', array( $this, 'save_creditapp_meta_data' ) );
        add_action( 'wp_trash_post', array( $this, 'delete_creditapp_meta_data' ) );
        add_filter( 'manage_addressya_creditapp_posts_columns', array( $this, 'edit_table_columns' ) );
        add_action( 'manage_addressya_creditapp_posts_custom_column', array( $this, 'manage_table_columns' ), 10, 2 );
 

        //this hook will create a new filter on the admin area for the specified post type
        add_action( 'restrict_manage_posts', function(){
            global $wpdb, $table_prefix;
    
            $post_type = (isset($_GET['post_type'])) ? $_GET['post_type'] : 'post';
     
            if ($post_type == 'addressya_creditapp'){
                
                $values = array();
                $values['approved'] = "Approved";
                $values['pending'] = "Pending";
                $values['declined'] = "Declined";
    
                //give a unique name in the select field
                ?><select name="addressya_credit_status">
                    <option value="">Application Status</option>    
                    <?php 
                    $current_v = isset($_GET['addressya_credit_status'])? $_GET['addressya_credit_status'] : '';
                    foreach ($values as $label => $value) {
                        printf(
                            '<option value="%s"%s>%s</option>',
                            $label,
                            $label == $current_v? ' selected="selected"':'',
                            $value
                        );
                    }
                    ?>
                </select>
                <?php
            }
        });

    
        //this hook will alter the main query according to the user's selection of the custom filter we created above:
        add_filter( 'parse_query_vars', function($query){
            global  $wpdb, $pagenow;
            $post_type = (isset($_GET['post_type'])) ? $_GET['post_type'] : 'post';
    
            if ($post_type == 'addressya_creditapp' && $pagenow=='edit.php' ) {
                die('here');
                //if ($post_type == 'addressya_creditapp' && $pagenow=='edit.php' && isset($_GET['addressya_credit_status']) && !empty($_GET['addressya_credit_status'])) {
                //die("here");
                //$status = $_GET['addressya_credit_status'];
                $query->query_vars['_order_total'] = '149980.00';
                //$query->query_vars['_billing_addressya_credit_status'] = 'approved';
                //$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS m ON p.ID = m.post_id WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' AND meta_key = '_billing_addressya_credit_status' AND meta_value =  '$status'" ) );
                //print_r($wpdb->prepare( "SELECT * FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS m ON p.ID = m.post_id WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' AND meta_key = '_billing_addressya_credit_status' AND meta_value =  '$status'" ));die();
                //$query->query_posts("SELECT * FROM $wpdb->posts AS p LEFT JOIN $wpdb->postmeta AS m ON p.ID = m.post_id WHERE post_type = 'addressya_creditapp' AND post_status = 'publish' AND meta_key = '_billing_addressya_credit_status' AND meta_value =  '$status'");
            }
        });

        /* add_action( 'admin_menu', 'map_view_register' );
        function map_view_register()
        {
            add_menu_page(
                'Map View',     // page title
                'Map View',     // menu title
                'manage_options',   // capability
                'map-view',     // menu slug
                'map_view_credits' // callback function
            );
        }
        function map_view_credits()
        {
            global $title;
    
            print '<div class="wrap">';
            print "<h1>$title</h1>";
    
            $file = plugin_dir_path( __FILE__ ) . "included.html";
    
            if ( file_exists( $file ) )
                require $file;
    
            print "<p class='description'>Included from <code>$file</code></p>";
    
            print '</div>';
        } */
    }    

    public function setup_post_type() {
        $args = array(
            'public'            => true,
            'label'             => 'Credit Application',
            'has_archive'       => true,
            'show_in_menu'      => 'addressya',
            'show_in_admin_bar' => false,
            'supports'          => array( 'thumbnail' ),
        );
        //register_post_type( 'addressya_creditapp', $args );
    }

    public function register_meta_boxes() {
        add_meta_box(
            'addressya-creditapp-metabox',
            '',
            array( $this, 'meta_box' ),
            'addressya_creditapp',
            'normal',
            'high'
        );
    }

    public function meta_box() {
        global $post;
        require_once ADDRESSYA_PLUGIN_PATH . 'includes/admin/views/meta-boxes/html-creditapp-add.php';
    }

    

    public function save_creditapp_meta_data( $post_id ) {
        global $post, $wpdb;
        $startDate = '';
        $endDate = '';
        if ( $post_id == null || empty( $_POST ) ) {
            return;
        }
        if ( ! isset( $_POST['post_type'] ) || 'addressya_creditapp' !== $_POST['post_type'] ) {
            return;
        }
        if ( wp_is_post_revision( $post_id ) ) {
            $post_id = wp_is_post_revision( $post_id );
        }

        if ( isset( $_POST['_billing_addressya_username'] ) ) {
            update_post_meta( $post->ID, '_billing_addressya_username', sanitize_text_field( $_POST['_billing_addressya_username'] ) );
        }
        if ( isset( $_POST['_billing_addressya_credit_status'] ) ) {
            update_post_meta( $post->ID, '_billing_addressya_credit_status', sanitize_text_field( $_POST['_billing_addressya_credit_status'] ) );
        }

        if ( isset( $_POST['_billing_addressya_credit_status'] ) && $_POST['_billing_addressya_credit_status'] == "approved" ) {
            if ( isset( $_POST['_billing_addressya_credit_contractStartDate'] ) ) {
                $startDate = strtotime($_POST['_billing_addressya_credit_contractStartDate']);
                update_post_meta( $post->ID, '_billing_addressya_credit_contractStartDate', sanitize_text_field( $_POST['_billing_addressya_credit_contractStartDate'] ) );
            }

            if ( isset( $_POST['_billing_addressya_credit_contractEndDate'] ) ) {
                $endDate = strtotime($_POST['_billing_addressya_credit_contractEndDate']);
                update_post_meta( $post->ID, '_billing_addressya_credit_contractEndDate', sanitize_text_field( $_POST['_billing_addressya_credit_contractEndDate'] ) );
            }
        } else {
            update_post_meta( $post->ID, '_billing_addressya_credit_contractStartDate', "" );
            update_post_meta( $post->ID, '_billing_addressya_credit_contractEndDate', "" );
        }

        if ( isset( $_POST['_billing_addressya_credit_companyLocationId'] ) ) {
            update_post_meta( $post->ID, '_billing_addressya_credit_companyLocationId', sanitize_text_field( $_POST['_billing_addressya_credit_companyLocationId'] ) );
        }
        $order_id = get_post_meta( $post_id, 'order_id', true );
        $status = $_POST['_billing_addressya_credit_status'];
        $companyLocationId = $_POST['_billing_addressya_credit_companyLocationId'];
        
        $response = ADDRESSYA_Admin_API::update_status_credit_application( $order_id, $status, $_POST['_billing_addressya_credit_contractStartDate'], $_POST['_billing_addressya_credit_contractEndDate']);
        
        $wpdb->update(
            $wpdb->posts,
            array( 'post_title' => sanitize_text_field( $_POST['_billing_addressya_username'] ) ),
            array( 'ID' => $post_id )
        );
    }

    public function edit_table_columns( $columns ) {
        $columns = array(
            '_billing_first_name' => 'Customer Name',
            '_billing_addressya_credit_status' => 'Status',
            '_billing_addressya_credit_contractStartDate' => 'Start Date',
            '_billing_addressya_credit_contractEndDate' => 'End Date',
            '_order_total' => 'Amount',
            'post_id' => 'Application',
            'order_id' => 'Order',
            'date' => 'Applied on',
        );

        return $columns;
    }

    public function manage_table_columns( $column, $post_id ) {
         switch ( $column ) {
            case 'post_id':
                $link = '<p>';
                $link .= '<a href="'. admin_url( 'post.php?post=' . absint(  $post_id ) . '&action=edit' ) .'" >';
                $link .= __( 'View credit application', 'your_domain' );
                $link .= '</a>';
                $link .= '</p>';
                echo $link;
                //echo get_post_meta( $post_id, 'post_id', true );
                break;    
            case '_billing_addressya_credit_status':
                $post_status = get_post_meta( $post_id, '_billing_addressya_credit_status', true );
                if($post_status == 'pending'){
                    echo '<span style="color:#f79d2f; border:solid 1px #f79d2f;padding:0 4px; border-radius:2px">pending</span>';
                } else if($post_status == 'approved'){
                    echo '<span style="color:#069c29; border:solid 1px #069c29;padding:0 4px; border-radius:2px"">approved</span>';
                }else {
                    echo '<span style="color:#cc181b; border:solid 1px #cc181b;padding:0 4px; border-radius:2px"">rejected</span>';
                }
                break;

            case '_billing_addressya_credit_contractStartDate':
                echo get_post_meta( $post_id, '_billing_addressya_credit_contractStartDate', true );
                break;  
                
            case '_billing_addressya_credit_contractEndDate':
                echo get_post_meta( $post_id, '_billing_addressya_credit_contractEndDate', true );
                break;  
                
            case '_billing_addressya_credit_companyLocationId':
                echo get_post_meta( $post_id, '_billing_addressya_credit_companyLocationId', true );
                break;    
                
            case '_billing_first_name':
                echo get_post_meta( $post_id, '_billing_first_name', true ) . " " . get_post_meta( $post_id, '_billing_last_name', true );
                break;    
                  
            case '_order_total':
                echo get_post_meta( $post_id, '_order_total', true );
                break;    

            case 'order_id':
                $link = '<p>';
                $link .= '<a href="'. admin_url( 'post.php?post=' . absint( get_post_meta( $post_id, 'order_id', true ) ) . '&action=edit' ) .'" >';
                $link .= __( 'View order', 'your_domain' );
                $link .= '</a>';
                $link .= '</p>';
                echo $link;
                break;                
             
        }
    }

    public function delete_creditapp_meta_data( $post_id ) {
        global $post, $wpdb;
        
        $order_id = get_post_meta( $post_id, 'order_id', true );
        $response = ADDRESSYA_Admin_API::deleteCreditApplication( $order_id );


        /* $wpdb->update(
            $wpdb->posts,
            array( 'post_title' => sanitize_text_field( $_POST['_billing_addressya_username'] ) ),
            array( 'ID' => $post_id )
        ); */
    }

        

}

new ADDRESSYA_Admin_Creditapp_Post;