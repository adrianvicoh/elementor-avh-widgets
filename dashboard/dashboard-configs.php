<?php
/**
 * Admin dashboard for Elementor AVH Widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ELEMENTOR_AVH_DASHBOARD_PAGE' ) ) {
	define( 'ELEMENTOR_AVH_DASHBOARD_PAGE', 'elementor-avh-widgets' );
}

if ( ! defined( 'ELEMENTOR_AVH_SETTINGS_GROUP' ) ) {
	define( 'ELEMENTOR_AVH_SETTINGS_GROUP', 'elementor_avh_settings' );
}

require_once __DIR__ . '/dash-configs/widget-visibility.php';

/**
 * Register the plugin dashboard page.
 */
function elementor_avh_register_dashboard_menu(): void {
	add_menu_page(
		esc_html__( 'Elementor AVH Widgets', 'custom-elementor-widgets' ),
		esc_html__( 'AVH Widgets', 'custom-elementor-widgets' ),
		'manage_options',
		ELEMENTOR_AVH_DASHBOARD_PAGE,
		'elementor_avh_render_dashboard_page',
		'dashicons-screenoptions'
	);
}
add_action( 'admin_menu', 'elementor_avh_register_dashboard_menu' );

/**
 * Render the plugin dashboard page.
 */
function elementor_avh_render_dashboard_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<p><?php esc_html_e( 'Manage the behavior of the widgets included with this plugin.', 'custom-elementor-widgets' ); ?></p>

		<?php settings_errors(); ?>

		<form action="options.php" method="post">
			<?php
			settings_fields( ELEMENTOR_AVH_SETTINGS_GROUP );
			do_settings_sections( ELEMENTOR_AVH_DASHBOARD_PAGE );
			submit_button( esc_html__( 'Save changes', 'custom-elementor-widgets' ) );
			?>
		</form>
	</div>
	<?php
}
