<?php
defined( 'ABSPATH' ) || exit;

class ADDRESSYA_Admin_Dashboard_Widget {

    public function __construct() {
        add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );
        add_action( 'admin_head', array( $this, 'admin_head_css' ) );
    }

    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'addressya_dashboard_credit_application_widget',
            'Credit Applications',
            array( $this, 'dashboard_credit_application' )
        ); 
    }

    public function dashboard_credit_application() {
        ?>

            <?php
                $args = array(
                    'post_type'      => 'addressya_creditapp',
                    'posts_per_page' => '10',
                    'post_status'    => 'any'
                );
                
                $orders = get_posts( $args );
            ?>

            <?php if ( $orders ) : ?>
                <table class="addressya-dashboard-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Application Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( $orders as $order ) : ?>
                        <?php //$order = get_post_meta( $order->ID ); ?>
                        <tr>
                            <td><?php echo get_post_meta( $order->ID, '_billing_first_name', true ) . " " . get_post_meta( $order->ID, '_billing_last_name', true ); ?></td>
                            <td><?php echo get_post_meta( $order->ID, '_billing_addressya_credit_status', true ); ?></td>                            
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                
            <?php else : ?>
                <p style="text-align: center">No credit application found!</p>
            <?php endif; ?>
        <?php
    }

    public function admin_head_css() {
        $current_Screen = get_current_screen();
        
        if ( 'dashboard' !== $current_Screen->id ) {
            return;
        }

        ?>
        <style>
            .addressya-dashboard-table {
                table-layout: fixed;
                width: 100%;
            }

            .addressya-dashboard-table th,
            .addressya-dashboard-table td {
                text-align: left;
                padding-bottom: 10px;
            }

            .addressya-woo-order-btn {
                background-color: #25D366;
                color: #fff;
                padding: 5px 10px;
                display: inline-flex;
                border-radius: 2px;
                cursor: pointer !important;
            }

            .addressya-woo-order-btn svg {
                width: 14px;
                margin-right: 5px;
            }

            .addressya-woo-order-btn p {
                color: #fff !important;
                padding: 0;
                margin: 0;
                font-size: 14px;
            }

            .addressya-woo-order-popup {
                width: 400px;
                max-width: 98%;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate( -50%, -50% );
                background-color: #fff;
                z-index: 9999;
                padding: 15px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-shadow: 0 0 30px rgba( 0,0,0,.2 );
                display: none;
            }

            .addressya-woo-order-popup form > div {
                margin-bottom: 10px;
            }

            .addressya-woo-order-popup form > div > label {
                display: block;
                margin-bottom: 5px;
            }

            .addressya-woo-order-popup form input[type="number"],
            .addressya-woo-order-popup form textarea {
                width: 100%;
            }

            .addressya-woo-order-popup form textarea {
                height: 100px;
            }

            .addressya-woo-order-popup form input[type="submit"] {
                padding: 5px 10px;
                background-color: #25D366;
                border: 1px solid #25D366;
                color: #fff;
                font-weight: 700;
                cursor: pointer;
            }

            .addressya-woo-order-popup form a {
                text-decoration: none;
                padding: 5px 10px;
                color: #333 !important;
                font-weight: 700;
                cursor: pointer;
            }

            .addressya-woo-order-popup form .desc {
                color: #999;
                padding: 0;
                margin: 0;
                font-style: italic;
            }
        </style>
        <?php
    }

}

new ADDRESSYA_Admin_Dashboard_Widget;