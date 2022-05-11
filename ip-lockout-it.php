<?php

declare(strict_types=1);

/*
Plugin Name: IP Lockout IT
Plugin URI: https://nilbug.com
Description: IP releaser for ithemes
Version: 1.2
Author: Frederic Guzman
Author URI: https://nilbug.com
License: GNU GENERAL
*/

use IPLockoutIT\Main;

require __DIR__ . '/vendor/autoload.php';

defined('ABSPATH') or die();

if (!class_exists('IPLockoutIT\Main') || !class_exists('IPLockoutIT\Table')) {
    $autoloader = require_once('autoload.php');

    $autoloader('IPLockoutIT\\Main', __DIR__ . '/src/Main');
    $autoloader('IPLockoutIT\\Table', __DIR__ . '/src/Table');
}

if (!class_exists('\Twig\Loader\FilesystemLoader')) {
    \esc_html('<div class="error notice"> <p><strong>Please run composer first</strong></p></div>');
}

// check for plugin using plugin name
add_action('plugins_loaded', function () {
    // check for plugin using plugin name
    if ( class_exists( 'ITSEC_Core' ) ) {
            Main::init();
    } else {
        ?>
            <p>
                <div class="notice notice-error">
                    <p><?php _e( '<strong>IP Lockout IT plugin</strong> is not available because the <strong>iThemes plugin</strong> is not installed | activated', 'it-releaser-it-success' ); ?></p>
                </div>
            </p>
        <?php
    }
});

