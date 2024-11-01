<?php

require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

class ADDRESSYA_Admin_Cred_App_Table extends WP_List_Table {

    private $_table;

    /** Class constructor */
    public function __construct() {
        global $wpdb;

        $this->_table = $wpdb->prefix . 'addressya_credit_application';

        parent::__construct( [
            'singular' => 'Application', //singular name of the listed records
            'plural'   => 'Applications', //plural name of the listed records
            'ajax'     => false, //should this table support ajax?
        ] );
    }

    public function prepare_items() {

        $order_by    = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : '';
        $order       = isset( $_GET['order'] ) ? sanitize_text_field( $_GET['order'] ) : '';
        $search_term = isset( $_POST['s'] ) ? sanitize_text_field( $_POST['s'] ) : '';

        $data = $this->wp_list_table_data( $order_by, $order, $search_term );

        $pre_page     = 10;
        $currnet_page = $this->get_pagenum();
        $total_items  = count( $data );

        $this->set_pagination_args( array(
            'total_items' => $total_items,
            'per_page'    => $pre_page,
        ) );

        $columns  = $this->get_columns();
        $hidden   = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->items           = array_slice( $data, (  ( $currnet_page - 1 ) * $pre_page ), $pre_page );
    }

    public function wp_list_table_data( $order_by = '', $order = '', $search_term = '' ) {
        global $wpdb;

        // Search pincode results.
        if ( '' !== $search_term ) {
            $search_term = sanitize_text_field( $search_term );

            return $wpdb->get_results(
                "SELECT *
                FROM $this->_table
                WHERE username LIKE '%$search_term%'
                OR order_id = '$search_term'
                OR status LIKE '%$search_term%'
                OR timestamp LIKE '%$search_term%'",
                ARRAY_A
            );
        } else { // Display all results.
            return $wpdb->get_results(
                "SELECT *
                FROM $this->_table
                ORDER BY id DESC",
                ARRAY_A
            );
        }
    }

    public function get_columns() {
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'username'      => 'Username',
            'order_id'      => 'Order Id',
            'status'        => 'Status',
            'timestamp'    => 'Date',
        );

        return $columns;
    }

    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
        case 'username':
        case 'order_id':
        case 'status':
        case 'timestamp':
            return $item[$column_name];
        default:
            return __( 'No Value', 'woo-pincode-checker' );
        }
    }

    function column_ip( $item ) {
        $actions = array(
            'edit'   => sprintf( '<a href="?page=%s&action=%s&id=%s">%s</a>', intval( $_REQUEST['page'] ), 'edit', $item['id'], 'Edit' ),
            'delete' => sprintf( '<a href="?page=%s&action=%s&id=%s">%s</a>', intval( $_REQUEST['page'] ), 'delete', $item['id'], 'Delete' ),
        );

        return sprintf( '%1$s %2$s', $item['ip'], $this->row_actions( $actions ) );
    }

    public function get_hidden_columns() {
        return array();
    }

    public function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="post[]" value="%s" />', $item['id'] );
    }
}
