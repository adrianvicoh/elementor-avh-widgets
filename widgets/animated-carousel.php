<?php
/**
 * Animated Carousel — extends Elementor Pro Nested Carousel (content carousel).
 *
 * Requires Elementor Pro and the nested-elements experiment.
 */

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AVH_Animated_Carousel extends \ElementorPro\Modules\NestedCarousel\Widgets\Nested_Carousel {

	public function get_name(): string {
		return 'avh-animated-carousel';
	}

	public function get_title(): string {
		return esc_html__( 'Animated Carousel', 'elementor-avh-widgets' );
	}

	public function get_categories(): array {
		return [ 'elementor-avh-widgets' ];
	}

	public function get_keywords(): array {
		return array_merge( parent::get_keywords(), [ 'animated', 'avh', 'center' ] );
	}

	public function get_style_depends(): array {
		return array_merge( parent::get_style_depends(), [ 'avh-animated-carousel-style' ] );
	}

	public function get_script_depends(): array {
		return array_merge( parent::get_script_depends(), [ 'avh-animated-carousel-script' ] );
	}

	protected function get_html_wrapper_class(): string {
		return parent::get_html_wrapper_class() . ' elementor-widget-avh-animated-carousel';
	}

	protected function register_controls(): void {
		parent::register_controls();

		$this->start_controls_section(
			'section_avh_animated_layout',
			[
				'label' => esc_html__( 'Animated layout', 'elementor-avh-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'avh_active_slide_width',
			[
				'label' => esc_html__( 'Active slide width', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 80,
						'max' => 1400,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 54,
				],
				'tablet_default' => [
					'unit' => '%',
					'size' => 48,
				],
				'mobile_default' => [
					'unit' => '%',
					'size' => 88,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--avh-ac-active-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'avh_adjacent_slide_width',
			[
				'label' => esc_html__( 'Adjacent slides width', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 60,
						'max' => 1200,
					],
					'%' => [
						'min' => 8,
						'max' => 90,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 34,
				],
				'tablet_default' => [
					'unit' => '%',
					'size' => 38,
				],
				'mobile_default' => [
					'unit' => '%',
					'size' => 78,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--avh-ac-adjacent-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'avh_outer_slide_width',
			[
				'label' => esc_html__( 'Outer slides width', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 40,
						'max' => 1000,
					],
					'%' => [
						'min' => 5,
						'max' => 80,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 20,
				],
				'tablet_default' => [
					'unit' => '%',
					'size' => 24,
				],
				'mobile_default' => [
					'unit' => '%',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--avh-ac-outer-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'avh_slide_flex_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Slide content (flex)', 'elementor-avh-widgets' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'avh_slide_flex_direction',
			[
				'label' => esc_html__( 'Direction', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'column' => esc_html__( 'Column', 'elementor-avh-widgets' ),
					'row' => esc_html__( 'Row', 'elementor-avh-widgets' ),
					'column-reverse' => esc_html__( 'Column reverse', 'elementor-avh-widgets' ),
					'row-reverse' => esc_html__( 'Row reverse', 'elementor-avh-widgets' ),
				],
				'default' => 'column',
				'selectors' => [
					'{{WRAPPER}} :where(.swiper-slide) > .e-con' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'avh_slide_justify_content',
			[
				'label' => esc_html__( 'Justify content', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'flex-start' => 'flex-start',
					'center' => 'center',
					'flex-end' => 'flex-end',
					'space-between' => 'space-between',
					'space-around' => 'space-around',
					'space-evenly' => 'space-evenly',
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} :where(.swiper-slide) > .e-con' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'avh_slide_align_items',
			[
				'label' => esc_html__( 'Align items', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'stretch' => 'stretch',
					'flex-start' => 'flex-start',
					'center' => 'center',
					'flex-end' => 'flex-end',
				],
				'default' => 'stretch',
				'selectors' => [
					'{{WRAPPER}} :where(.swiper-slide) > .e-con' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'avh_slide_flex_gap',
			[
				'label' => esc_html__( 'Gap', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 80 ],
				],
				'selectors' => [
					'{{WRAPPER}} :where(.swiper-slide) > .e-con' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}
}
