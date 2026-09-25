<?php
/**
 * Animated background extension for Elementor background-capable elements.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add animated background controls to an Elementor background section.
 *
 * @param \Elementor\Controls_Stack $element Elementor controls stack.
 */
function elementor_avh_add_animated_background_controls( $element ): void {
	if (
		! is_object( $element )
		|| ! method_exists( $element, 'add_control' )
		|| ! method_exists( $element, 'get_controls' )
		|| $element->get_controls( 'avh_animated_background_enabled' )
	) {
		return;
	}

	$condition = [
		'avh_animated_background_enabled' => 'yes',
	];

	$element->add_control(
		'avh_animated_background_heading',
		[
			'label'     => esc_html__( 'Animated Background', 'custom-elementor-widgets' ),
			'type'      => \Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);

	$element->add_control(
		'avh_animated_background_enabled',
		[
			'label'              => esc_html__( 'Enable animated background', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SWITCHER,
			'label_on'           => esc_html__( 'On', 'custom-elementor-widgets' ),
			'label_off'          => esc_html__( 'Off', 'custom-elementor-widgets' ),
			'return_value'       => 'yes',
			'default'            => '',
			'prefix_class'       => 'avh-animated-background--',
			'render_type'        => 'template',
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_background_motion',
		[
			'label'              => esc_html__( 'Movement', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SELECT,
			'default'            => 'cursor',
			'options'            => [
				'static' => esc_html__( 'Static', 'custom-elementor-widgets' ),
				'cursor' => esc_html__( 'Follow cursor', 'custom-elementor-widgets' ),
			],
			'condition'          => $condition,
			'prefix_class'       => 'avh-animated-background-motion-',
			'render_type'        => 'template',
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_background_start_color',
		[
			'label'              => esc_html__( 'Start color', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::COLOR,
			'default'            => '#ff96c8',
			'condition'          => $condition,
			'selectors'          => [
				'{{WRAPPER}}' => '--avh-animated-background-start-color: {{VALUE}};',
			],
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_background_end_color',
		[
			'label'              => esc_html__( 'End color', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::COLOR,
			'default'            => '#cdaaff',
			'condition'          => $condition,
			'selectors'          => [
				'{{WRAPPER}}' => '--avh-animated-background-end-color: {{VALUE}};',
			],
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_background_extension',
		[
			'label'              => esc_html__( 'Extension', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SLIDER,
			'range'              => [
				'%' => [
					'min'  => 25,
					'max'  => 200,
					'step' => 1,
				],
			],
			'default'            => [
				'unit' => '%',
				'size' => 100,
			],
			'condition'          => $condition,
			'selectors'          => [
				'{{WRAPPER}}' => '--avh-animated-background-extension: {{SIZE}}%;',
			],
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_background_blur',
		[
			'label'              => esc_html__( 'Blur', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SLIDER,
			'range'              => [
				'px' => [
					'min'  => 0,
					'max'  => 240,
					'step' => 1,
				],
			],
			'default'            => [
				'unit' => 'px',
				'size' => 95,
			],
			'condition'          => $condition,
			'selectors'          => [
				'{{WRAPPER}}' => '--avh-animated-background-blur: {{SIZE}}px;',
			],
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_background_speed',
		[
			'label'              => esc_html__( 'Follow speed', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SLIDER,
			'range'              => [
				'px' => [
					'min'  => 0.01,
					'max'  => 0.5,
					'step' => 0.01,
				],
			],
			'default'            => [
				'size' => 0.18,
			],
			'condition'          => $condition,
			'selectors'          => [
				'{{WRAPPER}}' => '--avh-animated-background-speed: {{SIZE}};',
			],
			'frontend_available' => true,
		]
	);
}

add_action( 'elementor/element/common/_section_background/before_section_end', 'elementor_avh_add_animated_background_controls', 10, 1 );
add_action( 'elementor/element/container/section_background/before_section_end', 'elementor_avh_add_animated_background_controls', 10, 1 );
add_action( 'elementor/element/section/section_background/before_section_end', 'elementor_avh_add_animated_background_controls', 10, 1 );
add_action( 'elementor/element/column/section_background/before_section_end', 'elementor_avh_add_animated_background_controls', 10, 1 );

/**
 * Enqueue the animated background runtime on the frontend and editor preview.
 */
function elementor_avh_enqueue_animated_background_assets(): void {
	$plugin_file = dirname( __DIR__ ) . '/elementor-avh-widgets.php';
	$style_path  = __DIR__ . '/assets/css/animated-background.css';
	$script_path = __DIR__ . '/assets/js/animated-background.js';

	wp_enqueue_style(
		'elementor-avh-animated-background',
		plugins_url( 'configs/assets/css/animated-background.css', $plugin_file ),
		[],
		file_exists( $style_path ) ? filemtime( $style_path ) : false
	);

	wp_enqueue_script(
		'elementor-avh-animated-background',
		plugins_url( 'configs/assets/js/animated-background.js', $plugin_file ),
		[],
		file_exists( $script_path ) ? filemtime( $script_path ) : false,
		true
	);
}

add_action( 'wp_enqueue_scripts', 'elementor_avh_enqueue_animated_background_assets', 20 );
add_action( 'elementor/preview/enqueue_styles', 'elementor_avh_enqueue_animated_background_assets', 20 );
add_action( 'elementor/preview/enqueue_scripts', 'elementor_avh_enqueue_animated_background_assets', 20 );
