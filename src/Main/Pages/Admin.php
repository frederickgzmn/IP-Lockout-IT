<?php

declare(strict_types=1);

namespace IPLockoutIT\Main\Pages;

use IPLockoutIT\Table;

/**
 * Class Admin
 * @package IPLockoutIT\Pages
 */
class Admin {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'ipLockoutITAdminMenu' ] );
    }

    function ipLockoutITAdminMenu() {
		add_menu_page(
			__( 'IP Lockout IT', 'IPLockoutIT-menu' ),
			__( 'IP Lockout IT', 'IPLockoutIT-menu-text' ),
			'manage_options',
			'ip-releaser-it',
			[ $this, 'renderPage' ],
			'dashicons-schedule',
			3
		);
	}

	function renderPage() {

		?>
			<h1>
				<?php esc_html_e( 'IP Address Lockout List', 'ip-lockout-it-header' ); ?>
			</h1>

			<p>
				<div class="notice notice-success is-dismissible" style="display: none">
					<p><?php _e( 'IP address released!', 'it-releaser-it-success' ); ?></p>
				</div>
			</p>

			
		<?php
        
        $wp_table = new Table();
        $wp_table->prepareItems();
        
        ?>
            <form method="post">
            <input type="hidden" name="page" value="ip_lockout_it" />
            <?php $wp_table->search_box('Find by IP', 'lockout_host'); ?>
            </form>
        <?php

        $wp_table->display();

	}
}
