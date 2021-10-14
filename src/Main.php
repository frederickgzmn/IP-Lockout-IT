<?php

declare(strict_types=1);

namespace IPLockoutIT;

use IPLockoutIT\Main\Pages;

use \WP_Error;

/**
 * Class Main
 * @package IPLockoutIT
 */
class Main
{
    /**
     * @var self Plugin instance.
     */
    private static $instance;

    /**
     * @return self Plugin instance.
     */
    public static function init(): self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private static $version = '1.0.0';
    private $url;
    private $path;

    /**
     * Plugin constructor.
     */
    public function __construct() {
        $this->url = plugin_dir_url(dirname(__FILE__));
        $this->path = plugin_dir_path(dirname(__FILE__));

        add_action('wp_ajax_release_ip_action', [ $this, 'release_ip_action' ] );

        new Pages\Admin();
        add_action('admin_enqueue_scripts', [$this, 'adminScripts']);
    }

    /* 
    Ajax trigger request
    */
    function release_ip_action() {
        // Check for nonce security      
        if ( wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
            
            $id = (int)$_POST['ip_id'];

            if( is_numeric( $id ) ) {
                self::deleteIpRecord( $_POST['ip_id'] );
                die ( 'true');
            }
        }
    }

    /**
     * Delete a customer record.
     *
     * @param int $id IP Address ID
     */
    public static function deleteIpRecord( $id ) {
        global $wpdb;

        $wpdb->delete(
            "{$wpdb->prefix}itsec_lockouts",
            ['lockout_id' => $id],
            ['%d']
        );
    }

    /**
     * Register scripts.
     */
    public function adminScripts() {
        //main js
        $jsc = $this->url . 'assets/custom/js/main.js';
        wp_enqueue_script('IPLockoutIT-main', $jsc, ['jquery'], self::$version);
        wp_localize_script('IPLockoutIT-main', 'ajax_var', [ 'nonce' => wp_create_nonce('ajax-nonce'), 'url' => admin_url('admin-ajax.php') ]);
    }
}
