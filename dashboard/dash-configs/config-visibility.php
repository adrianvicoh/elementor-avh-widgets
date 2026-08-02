<?php
/**
 * Global Elementor configuration visibility settings.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ELEMENTOR_AVH_ENABLED_CONFIGS_OPTION' ) ) {
	define( 'ELEMENTOR_AVH_ENABLED_CONFIGS_OPTION', 'elementor_avh_enabled_configs' );
}

/**
 * Return the global Elementor configurations provided by the plugin.
 *
 * @return array<string, array<string, string>>
 */
function elementor_avh_get_available_configs(): array {
	return [
		'border-beam' => [
			'title'       => esc_html__( 'Animated Border Beam', 'custom-elementor-widgets' ),
			'description' => esc_html__( 'Adds configurable animated border controls to Elementor elements that support borders.', 'custom-elementor-widgets' ),
			'icon'        => 'eicon-animation',
		],
	];
}

/**
 * Return the identifiers of all enabled global configurations.
 *
 * Configurations are opt-in, so none are enabled until an administrator saves
 * the dashboard setting.
 *
 * @return string[]
 */
function elementor_avh_get_enabled_configs(): array {
	$available_configs = array_keys( elementor_avh_get_available_configs() );
	$enabled_configs   = get_option( ELEMENTOR_AVH_ENABLED_CONFIGS_OPTION, [] );

	if ( ! is_array( $enabled_configs ) ) {
		return [];
	}

	$enabled_configs = array_map( 'sanitize_key', $enabled_configs );

	return array_values( array_intersect( $available_configs, $enabled_configs ) );
}

/**
 * Determine whether a global Elementor configuration is enabled.
 */
function elementor_avh_is_config_enabled( string $config_id ): bool {
	return in_array( $config_id, elementor_avh_get_enabled_configs(), true );
}

/**
 * Sanitize enabled configuration identifiers before saving them.
 *
 * @param mixed $value Submitted setting value.
 * @return string[]
 */
function elementor_avh_sanitize_enabled_configs( $value ): array {
	if ( ! is_array( $value ) ) {
		return [];
	}

	$available_configs = array_keys( elementor_avh_get_available_configs() );
	$enabled_configs   = array_unique( array_map( 'sanitize_key', $value ) );

	return array_values( array_intersect( $available_configs, $enabled_configs ) );
}

/**
 * Register the global configuration section with the Settings API.
 */
function elementor_avh_register_config_visibility_settings(): void {
	register_setting(
		ELEMENTOR_AVH_SETTINGS_GROUP,
		ELEMENTOR_AVH_ENABLED_CONFIGS_OPTION,
		[
			'type'              => 'array',
			'sanitize_callback' => 'elementor_avh_sanitize_enabled_configs',
			'default'           => [],
		]
	);

	add_settings_section(
		'elementor_avh_global_configs',
		esc_html__( 'Elementor configurations', 'custom-elementor-widgets' ),
		'elementor_avh_render_config_visibility_description',
		ELEMENTOR_AVH_DASHBOARD_PAGE
	);

	add_settings_field(
		'elementor_avh_enabled_configs',
		esc_html__( 'Available configurations', 'custom-elementor-widgets' ),
		'elementor_avh_render_config_visibility_field',
		ELEMENTOR_AVH_DASHBOARD_PAGE,
		'elementor_avh_global_configs'
	);
}
add_action( 'admin_init', 'elementor_avh_register_config_visibility_settings' );

/**
 * Render the global configuration section description.
 */
function elementor_avh_render_config_visibility_description(): void {
	printf(
		'<p>%s</p>',
		esc_html__( 'Enable or disable features that extend existing Elementor elements. Disabled configurations do not register controls or load frontend assets.', 'custom-elementor-widgets' )
	);

	printf(
		'<input type="hidden" name="%s[]" value="">',
		esc_attr( ELEMENTOR_AVH_ENABLED_CONFIGS_OPTION )
	);
}

/**
 * Render the global configuration checkboxes.
 */
function elementor_avh_render_config_visibility_field(): void {
	$available_configs = elementor_avh_get_available_configs();
	$enabled_configs   = elementor_avh_get_enabled_configs();

	echo '<fieldset>';
	echo '<div class="elementor-avh-widget-grid elementor-avh-config-grid">';

	foreach ( $available_configs as $config_id => $config ) {
		$field_id       = 'elementor-avh-config-' . $config_id;
		$description_id = $field_id . '-description';
		?>
		<label class="elementor-avh-widget-card elementor-avh-config-card" for="<?php echo esc_attr( $field_id ); ?>">
			<span class="elementor-avh-widget-card__icon" aria-hidden="true">
				<i class="<?php echo esc_attr( $config['icon'] ); ?>"></i>
			</span>
			<span class="elementor-avh-widget-card__content">
				<strong class="elementor-avh-widget-card__title"><?php echo esc_html( $config['title'] ); ?></strong>
				<span
					class="elementor-avh-widget-card__description"
					id="<?php echo esc_attr( $description_id ); ?>"
				>
					<?php echo esc_html( $config['description'] ); ?>
				</span>
			</span>
			<span class="elementor-avh-widget-card__control">
				<input
					class="elementor-avh-widget-card__checkbox"
					type="checkbox"
					id="<?php echo esc_attr( $field_id ); ?>"
					name="<?php echo esc_attr( ELEMENTOR_AVH_ENABLED_CONFIGS_OPTION ); ?>[]"
					value="<?php echo esc_attr( $config_id ); ?>"
					aria-describedby="<?php echo esc_attr( $description_id ); ?>"
					<?php checked( in_array( $config_id, $enabled_configs, true ) ); ?>
				>
				<span class="elementor-avh-widget-card__switch" aria-hidden="true"></span>
			</span>
		</label>
		<?php
	}

	echo '</div>';
	echo '</fieldset>';
}
