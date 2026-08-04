<?php
/**
 * Animated Border Beam extension for Elementor elements.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Border Beam controls to an existing Elementor border section.
 *
 * @param \Elementor\Controls_Stack $element Elementor controls stack.
 */
function elementor_avh_add_border_beam_controls( $element ): void {
	if (
		! is_object( $element )
		|| ! method_exists( $element, 'add_control' )
		|| ! method_exists( $element, 'get_controls' )
		|| $element->get_controls( 'avh_border_beam_enabled' )
	) {
		return;
	}

	// Idempotencia garantizada por el check de get_controls() de arriba.
	// Elementor 4.x dispara `common/*` manualmente para stacks `common-optimized`
	// (experimento e_optimized_markup); el hook nunca se ejecuta dos veces sobre
	// el mismo stack, así que no hace falta una guarda de doing_action().

	$condition = [
		'avh_border_beam_enabled' => 'yes',
	];

	$element->add_control(
		'avh_border_beam_heading',
		[
			'label'     => esc_html__( 'Animated Border Beam', 'custom-elementor-widgets' ),
			'type'      => \Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);

	$element->add_control(
		'avh_border_beam_enabled',
		[
			'label'              => esc_html__( 'Enable animated border', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SWITCHER,
			'label_on'           => esc_html__( 'On', 'custom-elementor-widgets' ),
			'label_off'          => esc_html__( 'Off', 'custom-elementor-widgets' ),
			'return_value'       => 'yes',
			'default'            => '',
			'prefix_class'       => 'avh-border-beam--',
			'render_type'        => 'template',
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_border_beam_type',
		[
			'label'              => esc_html__( 'Effect type', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SELECT,
			'default'            => 'md',
			'options'            => [
				'sm'            => esc_html__( 'Compact rotation', 'custom-elementor-widgets' ),
				'md'            => esc_html__( 'Full border rotation', 'custom-elementor-widgets' ),
				'line'          => esc_html__( 'Bottom traveling line', 'custom-elementor-widgets' ),
				'pulse-inner'   => esc_html__( 'Inner pulse', 'custom-elementor-widgets' ),
				'pulse-outside' => esc_html__( 'Outside pulse', 'custom-elementor-widgets' ),
			],
			'condition'          => $condition,
			'prefix_class'       => 'avh-border-beam-type-',
			'render_type'        => 'template',
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_border_beam_color',
		[
			'label'              => esc_html__( 'Color palette', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SELECT,
			'default'            => 'colorful',
			'options'            => [
				'colorful' => esc_html__( 'Colorful', 'custom-elementor-widgets' ),
				'mono'     => esc_html__( 'Monochrome', 'custom-elementor-widgets' ),
				'ocean'    => esc_html__( 'Ocean', 'custom-elementor-widgets' ),
				'sunset'   => esc_html__( 'Sunset', 'custom-elementor-widgets' ),
			],
			'condition'          => $condition,
			'prefix_class'       => 'avh-border-beam-color-',
			'render_type'        => 'template',
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_border_beam_theme',
		[
			'label'              => esc_html__( 'Background theme', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SELECT,
			'default'            => 'dark',
			'options'            => [
				'dark'  => esc_html__( 'Dark', 'custom-elementor-widgets' ),
				'light' => esc_html__( 'Light', 'custom-elementor-widgets' ),
				'auto'  => esc_html__( 'System preference', 'custom-elementor-widgets' ),
			],
			'condition'          => $condition,
			'prefix_class'       => 'avh-border-beam-theme-',
			'render_type'        => 'template',
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_border_beam_duration',
		[
			'label'       => esc_html__( 'Duration (seconds)', 'custom-elementor-widgets' ),
			'description' => esc_html__( 'Leave empty to use the preset: 1.96s for rotation, 3.1s for line, and 2.3s for pulse.', 'custom-elementor-widgets' ),
			'type'        => \Elementor\Controls_Manager::NUMBER,
			'min'         => 0.2,
			'max'         => 30,
			'step'        => 0.05,
			'placeholder' => esc_html__( 'Preset', 'custom-elementor-widgets' ),
			'condition'   => $condition,
			'selectors'   => [
				'{{WRAPPER}}' => '--avh-beam-duration: {{VALUE}}s;',
			],
		]
	);

	$element->add_control(
		'avh_border_beam_strength',
		[
			'label'     => esc_html__( 'Strength', 'custom-elementor-widgets' ),
			'type'      => \Elementor\Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min'  => 0,
					'max'  => 1,
					'step' => 0.05,
				],
			],
			'default'   => [
				'size' => 1,
			],
			'condition' => $condition,
			'selectors' => [
				'{{WRAPPER}}' => '--avh-beam-strength: {{SIZE}};',
			],
		]
	);

	$element->add_control(
		'avh_border_beam_brightness',
		[
			'label'       => esc_html__( 'Brightness', 'custom-elementor-widgets' ),
			'description' => esc_html__( 'Leave empty to use the selected type and theme preset.', 'custom-elementor-widgets' ),
			'type'        => \Elementor\Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 4,
			'step'        => 0.05,
			'placeholder' => esc_html__( 'Preset', 'custom-elementor-widgets' ),
			'condition'   => $condition,
			'selectors'   => [
				'{{WRAPPER}}' => '--avh-beam-brightness: {{VALUE}};',
			],
		]
	);

	$element->add_control(
		'avh_border_beam_saturation',
		[
			'label'       => esc_html__( 'Saturation', 'custom-elementor-widgets' ),
			'description' => esc_html__( 'Leave empty to use the selected type and theme preset.', 'custom-elementor-widgets' ),
			'type'        => \Elementor\Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 4,
			'step'        => 0.05,
			'placeholder' => esc_html__( 'Preset', 'custom-elementor-widgets' ),
			'condition'   => $condition,
			'selectors'   => [
				'{{WRAPPER}}' => '--avh-beam-saturation: {{VALUE}};',
			],
		]
	);

	$element->add_control(
		'avh_border_beam_hue_range',
		[
			'label'       => esc_html__( 'Hue range (degrees)', 'custom-elementor-widgets' ),
			'description' => esc_html__( 'The traveling line is capped at 13 degrees to preserve its palette.', 'custom-elementor-widgets' ),
			'type'        => \Elementor\Controls_Manager::NUMBER,
			'default'     => 30,
			'min'         => 0,
			'max'         => 180,
			'step'        => 1,
			'condition'   => $condition,
			'selectors'   => [
				'{{WRAPPER}}' => '--avh-beam-hue-range: {{VALUE}}deg;',
			],
		]
	);

	$element->add_control(
		'avh_border_beam_static_colors',
		[
			'label'              => esc_html__( 'Static colors', 'custom-elementor-widgets' ),
			'description'        => esc_html__( 'Disables hue shifting while keeping the border motion active.', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SWITCHER,
			'label_on'           => esc_html__( 'Yes', 'custom-elementor-widgets' ),
			'label_off'          => esc_html__( 'No', 'custom-elementor-widgets' ),
			'return_value'       => 'yes',
			'default'            => '',
			'condition'          => $condition,
			'prefix_class'       => 'avh-border-beam-static-',
			'render_type'        => 'template',
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_border_beam_outside_notice',
		[
			'type'            => \Elementor\Controls_Manager::RAW_HTML,
			'raw'             => esc_html__( 'Outside pulse needs visible overflow around the element. Parent overflow settings can clip the halo.', 'custom-elementor-widgets' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			'condition'       => [
				'avh_border_beam_enabled' => 'yes',
				'avh_border_beam_type'    => 'pulse-outside',
			],
		]
	);
}

add_action( 'elementor/element/common/_section_border/before_section_end', 'elementor_avh_add_border_beam_controls', 10, 1 );
add_action( 'elementor/element/container/section_border/before_section_end', 'elementor_avh_add_border_beam_controls', 10, 1 );
add_action( 'elementor/element/section/section_border/before_section_end', 'elementor_avh_add_border_beam_controls', 10, 1 );
add_action( 'elementor/element/column/section_border/before_section_end', 'elementor_avh_add_border_beam_controls', 10, 1 );

/**
 * Enqueue the standalone Border Beam runtime.
 * Only on frontend and preview iframe (not editor panel).
 */
function elementor_avh_enqueue_border_beam_assets(): void {
	$plugin_file = dirname( __DIR__ ) . '/elementor-avh-widgets.php';
	$style_path  = __DIR__ . '/assets/css/border-beam.css';
	$script_path = __DIR__ . '/assets/js/border-beam.js';

	// Frontend real
	wp_enqueue_style(
		'elementor-avh-border-beam',
		plugins_url( 'configs/assets/css/border-beam.css', $plugin_file ),
		[],
		file_exists( $style_path ) ? filemtime( $style_path ) : false
	);

	wp_enqueue_script(
		'elementor-avh-border-beam',
		plugins_url( 'configs/assets/js/border-beam.js', $plugin_file ),
		[],
		file_exists( $script_path ) ? filemtime( $script_path ) : false,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'elementor_avh_enqueue_border_beam_assets', 20 );

// Preview iframe (editor canvas) — sí, panel de edición — no
add_action( 'elementor/preview/enqueue_styles', 'elementor_avh_enqueue_border_beam_assets', 20 );
add_action( 'elementor/preview/enqueue_scripts', 'elementor_avh_enqueue_border_beam_assets', 20 );
