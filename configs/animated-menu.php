<?php
/**
 * Animated menu background extension for Elementor Pro Nav Menu widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add animated background controls to Elementor Pro's Nav Menu main-menu section.
 *
 * @param \Elementor\Controls_Stack $element Elementor controls stack.
 */
function elementor_avh_add_animated_menu_controls( $element ): void {
	if (
		! is_object( $element )
		|| ! method_exists( $element, 'add_control' )
		|| ! method_exists( $element, 'get_controls' )
		|| $element->get_controls( 'avh_animated_menu_enabled' )
	) {
		return;
	}

	$condition = [
		'avh_animated_menu_enabled' => 'yes',
	];

	$element->add_control(
		'avh_animated_menu_heading',
		[
			'label'     => esc_html__( 'Animated Menu Background', 'custom-elementor-widgets' ),
			'type'      => \Elementor\Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);

	$element->add_control(
		'avh_animated_menu_enabled',
		[
			'label'              => esc_html__( 'Enable animated menu background', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SWITCHER,
			'label_on'           => esc_html__( 'On', 'custom-elementor-widgets' ),
			'label_off'          => esc_html__( 'Off', 'custom-elementor-widgets' ),
			'return_value'       => 'yes',
			'default'            => '',
			'prefix_class'       => 'avh-animated-menu--',
			'render_type'        => 'template',
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_menu_motion',
		[
			'label'              => esc_html__( 'Movement', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SELECT,
			'default'            => 'cursor',
			'options'            => [
				'static' => esc_html__( 'Static', 'custom-elementor-widgets' ),
				'cursor' => esc_html__( 'Follow cursor', 'custom-elementor-widgets' ),
			],
			'condition'          => $condition,
			'prefix_class'       => 'avh-animated-menu-motion-',
			'render_type'        => 'template',
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_menu_start_color',
		[
			'label'              => esc_html__( 'Start color', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::COLOR,
			'default'            => '#96dc96',
			'condition'          => $condition,
			'selectors'          => [
				'{{WRAPPER}}' => '--avh-animated-menu-start-color: {{VALUE}};',
			],
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_menu_end_color',
		[
			'label'              => esc_html__( 'End color', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::COLOR,
			'default'            => '#5ab464',
			'condition'          => $condition,
			'selectors'          => [
				'{{WRAPPER}}' => '--avh-animated-menu-end-color: {{VALUE}};',
			],
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_menu_extension',
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
				'{{WRAPPER}}' => '--avh-animated-menu-extension: {{SIZE}}%;',
			],
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_menu_blur',
		[
			'label'              => esc_html__( 'Blur', 'custom-elementor-widgets' ),
			'type'               => \Elementor\Controls_Manager::SLIDER,
			'range'              => [
				'px' => [
					'min'  => 0,
					'max'  => 120,
					'step' => 1,
				],
			],
			'default'            => [
				'unit' => 'px',
				'size' => 22,
			],
			'condition'          => $condition,
			'selectors'          => [
				'{{WRAPPER}}' => '--avh-animated-menu-blur: {{SIZE}}px;',
			],
			'frontend_available' => true,
		]
	);

	$element->add_control(
		'avh_animated_menu_speed',
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
				'{{WRAPPER}}' => '--avh-animated-menu-speed: {{SIZE}};',
			],
			'frontend_available' => true,
		]
	);
}

add_action( 'elementor/element/nav-menu/section_style_main-menu/before_section_end', 'elementor_avh_add_animated_menu_controls', 10, 1 );

/**
 * Enqueue the animated menu runtime on the frontend and editor preview.
 */
function elementor_avh_enqueue_animated_menu_assets(): void {
	$plugin_file = dirname( __DIR__ ) . '/elementor-avh-widgets.php';
	$style_path  = __DIR__ . '/assets/css/animated-menu.css';
	$script_path = __DIR__ . '/assets/js/animated-menu.js';

	wp_enqueue_style(
		'elementor-avh-animated-menu',
		plugins_url( 'configs/assets/css/animated-menu.css', $plugin_file ),
		[],
		file_exists( $style_path ) ? filemtime( $style_path ) : false
	);

	wp_enqueue_script(
		'elementor-avh-animated-menu',
		plugins_url( 'configs/assets/js/animated-menu.js', $plugin_file ),
		[],
		file_exists( $script_path ) ? filemtime( $script_path ) : false,
		true
	);
}

add_action( 'wp_enqueue_scripts', 'elementor_avh_enqueue_animated_menu_assets', 20 );
add_action( 'elementor/preview/enqueue_styles', 'elementor_avh_enqueue_animated_menu_assets', 20 );
add_action( 'elementor/preview/enqueue_scripts', 'elementor_avh_enqueue_animated_menu_assets', 20 );
