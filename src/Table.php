<?php

declare(strict_types=1);

namespace IPLockoutIT;

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Class Table
 * @package IPLockoutIT
 */
class Table extends \WP_List_Table {

    function get_columns(){
        $columns = array(
            'lockout_id'   => 'Lockout ID',
            'lockout_type' => 'Lockout Type',
            'lockout_host' => 'IP Address',
            'lockout_act'  => 'Action'
        );
        return $columns;
    }
    
    function prepareItems() {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        $per_page = $this->get_items_per_page( 'items_per_page', 5 );
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args( [
        'total_items' => $total_items, //WE have to calculate the total number of items
        'per_page' => $per_page //WE have to determine how many items to show on a page
        ] );

        $items = self::getIpAddreses( $per_page, $current_page );
        usort( $items, array( &$this, 'usort_reorder' ) );
        
        $this->items = $items;
    }

    /**
    * Returns the count of records in the database.
    *
    * @return null|string
    */
    public static function record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}itsec_lockouts  where lockout_host is not null";

        return $wpdb->get_var( $sql );
    }

    function column_default( $item, $column_name ) {
        switch( $column_name ) { 
            case 'lockout_id':
            case 'lockout_type':
            case 'lockout_host':
            return $item[ $column_name ];
            case 'lockout_act':
                return '<input type="button" data-id="' . $item['lockout_id'] . '" class="button ip_releaser_it_action" value="Release IP Address">';
            //default:
            //return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    function no_items() {
        _e( 'No Blocked IP address found.' );
    }

    function usort_reorder( $a, $b ) {
        // If no sort, default to title
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'lockout_id';
        // If no order, default to asc
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp( $a[$orderby], $b[$orderby] );
        // Send final sort direction to usort
        return ( $order === 'asc' ) ? $result : -$result;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'lockout_id'  => array('lockout_id',false),
            'lockout_type' => array('lockout_type',false),
            'lockout_host'   => array('lockout_host',false)
        );
        return $sortable_columns;
    }

    /**
    * Retrieve ithemes ip addresses from the database
    *
    * @param int $per_page
    * @param int $page_number
    *
    * @return mixed
    */
    public static function getIpAddreses( $per_page = 5, $page_number = 1 ) {

        global $wpdb;
        
        $sql = "SELECT * FROM {$wpdb->prefix}itsec_lockouts where lockout_host is not null";
        
        if ( ! empty( $_POST['s'] ) ) {
            $sql .= " and lockout_host = '" . esc_sql( $_REQUEST['s'] ) . "'";
        }

        if ( ! empty( $_REQUEST['orderby'] ) ) {
        $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
        $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }
        
        $sql .= " LIMIT $per_page";
        
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        
        return $result;
    }

}