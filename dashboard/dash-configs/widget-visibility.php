<?php
/**
 * Widget visibility settings.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ELEMENTOR_AVH_ENABLED_WIDGETS_OPTION' ) ) {
	define( 'ELEMENTOR_AVH_ENABLED_WIDGETS_OPTION', 'elementor_avh_enabled_widgets' );
}

/**
 * Return the widgets provided by the plugin.
 *
 * This registry is shared by Elementor registration and the dashboard so a new
 * widget only needs to be added in one place.
 *
 * @return array<string, array<string, string>>
 */
function elementor_avh_get_available_widgets(): array {
	$plugin_root = dirname( __DIR__, 2 );

	return [
		'coverflow-slider' => [
			'title'       => esc_html__( 'Coverflow Slider', 'custom-elementor-widgets' ),
			'description' => esc_html__( 'Image slider with a three-dimensional coverflow effect.', 'custom-elementor-widgets' ),
			'icon'        => 'eicon-slider-3d',
			'class'       => 'Coverflow_Slider',
			'file'        => $plugin_root . '/widgets/coverflow-slider.php',
		],
		'toggle-content' => [
			'title'       => esc_html__( 'Toggle Content', 'custom-elementor-widgets' ),
			'description' => esc_html__( 'Button-controlled content reveal widget.', 'custom-elementor-widgets' ),
			'icon'        => 'eicon-toggle',
			'class'       => 'Toggle_Content',
			'file'        => $plugin_root . '/widgets/toggle-content.php',
		],
	];
}

/**
 * Return the identifiers of all enabled widgets.
 *
 * All widgets remain enabled until the settings form is saved for the first
 * time, preserving the plugin's existing behavior.
 *
 * @return string[]
 */
function elementor_avh_get_enabled_widgets(): array {
	$available_widgets = array_keys( elementor_avh_get_available_widgets() );
	$enabled_widgets   = get_option( ELEMENTOR_AVH_ENABLED_WIDGETS_OPTION, false );

	if ( false === $enabled_widgets ) {
		return $available_widgets;
	}

	if ( ! is_array( $enabled_widgets ) ) {
		return [];
	}

	$enabled_widgets = array_map( 'sanitize_key', $enabled_widgets );

	return array_values( array_intersect( $available_widgets, $enabled_widgets ) );
}

/**
 * Determine whether a widget is enabled.
 */
function elementor_avh_is_widget_enabled( string $widget_id ): bool {
	return in_array( $widget_id, elementor_avh_get_enabled_widgets(), true );
}

/**
 * Sanitize the enabled widget identifiers before saving them.
 *
 * @param mixed $value Submitted setting value.
 * @return string[]
 */
function elementor_avh_sanitize_enabled_widgets( $value ): array {
	if ( ! is_array( $value ) ) {
		return [];
	}

	$available_widgets = array_keys( elementor_avh_get_available_widgets() );
	$enabled_widgets   = array_unique( array_map( 'sanitize_key', $value ) );

	return array_values( array_intersect( $available_widgets, $enabled_widgets ) );
}

/**
 * Register the widget visibility section with the WordPress Settings API.
 */
function elementor_avh_register_widget_visibility_settings(): void {
	register_setting(
		ELEMENTOR_AVH_SETTINGS_GROUP,
		ELEMENTOR_AVH_ENABLED_WIDGETS_OPTION,
		[
			'type'              => 'array',
			'sanitize_callback' => 'elementor_avh_sanitize_enabled_widgets',
		]
	);

	add_settings_section(
		'elementor_avh_widget_visibility',
		esc_html__( 'Elementor widgets', 'custom-elementor-widgets' ),
		'elementor_avh_render_widget_visibility_description',
		ELEMENTOR_AVH_DASHBOARD_PAGE
	);

	add_settings_field(
		'elementor_avh_enabled_widgets',
		esc_html__( 'Available widgets', 'custom-elementor-widgets' ),
		'elementor_avh_render_widget_visibility_field',
		ELEMENTOR_AVH_DASHBOARD_PAGE,
		'elementor_avh_widget_visibility'
	);
}
add_action( 'admin_init', 'elementor_avh_register_widget_visibility_settings' );

/**
 * Render the widget visibility section description.
 */
function elementor_avh_render_widget_visibility_description(): void {
	printf(
		'<p>%s</p>',
		esc_html__( 'Choose which widgets are registered and shown in the Elementor editor. Disabling a widget can affect pages where it is already in use.', 'custom-elementor-widgets' )
	);

	printf(
		'<input type="hidden" name="%s[]" value="">',
		esc_attr( ELEMENTOR_AVH_ENABLED_WIDGETS_OPTION )
	);
}

/**
 * Render the widget visibility checkboxes.
 */
function elementor_avh_render_widget_visibility_field(): void {
	$available_widgets = elementor_avh_get_available_widgets();
	$enabled_widgets   = elementor_avh_get_enabled_widgets();

	echo '<fieldset>';
	echo '<div class="elementor-avh-widget-grid">';

	foreach ( $available_widgets as $widget_id => $widget ) {
		$field_id       = 'elementor-avh-widget-' . $widget_id;
		$description_id = $field_id . '-description';
		?>
		<label class="elementor-avh-widget-card" for="<?php echo esc_attr( $field_id ); ?>">
			<span class="elementor-avh-widget-card__icon" aria-hidden="true">
				<i class="<?php echo esc_attr( $widget['icon'] ); ?>"></i>
			</span>
			<span class="elementor-avh-widget-card__content">
				<strong class="elementor-avh-widget-card__title"><?php echo esc_html( $widget['title'] ); ?></strong>
				<span
					class="elementor-avh-widget-card__description"
					id="<?php echo esc_attr( $description_id ); ?>"
				>
					<?php echo esc_html( $widget['description'] ); ?>
				</span>
			</span>
			<span class="elementor-avh-widget-card__control">
				<input
					class="elementor-avh-widget-card__checkbox"
					type="checkbox"
					id="<?php echo esc_attr( $field_id ); ?>"
					name="<?php echo esc_attr( ELEMENTOR_AVH_ENABLED_WIDGETS_OPTION ); ?>[]"
					value="<?php echo esc_attr( $widget_id ); ?>"
					aria-describedby="<?php echo esc_attr( $description_id ); ?>"
					<?php checked( in_array( $widget_id, $enabled_widgets, true ) ); ?>
				>
				<span class="elementor-avh-widget-card__switch" aria-hidden="true"></span>
			</span>
		</label>
		<?php
	}

	echo '</div>';
	echo '</fieldset>';
}
