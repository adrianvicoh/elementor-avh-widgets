<?php
/**
 * Animated Carousel — image-based carousel with centered active slide.
 *
 * Requires Elementor Pro (for Swiper assets and carousel base styles).
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AVH_Animated_Carousel extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'avh-animated-carousel';
	}

	public function get_title(): string {
		return esc_html__( 'Animated Carousel', 'elementor-avh-widgets' );
	}

	public function get_icon(): string {
		return 'eicon-slides';
	}

	public function get_categories(): array {
		return [ 'elementor-avh-widgets' ];
	}

	public function get_keywords(): array {
		return [ 'carousel', 'animated', 'slider', 'image', 'center', 'avh' ];
	}

	public function get_style_depends(): array {
		return [ 'e-swiper', 'avh-animated-carousel-style' ];
	}

	public function get_script_depends(): array {
		return [ 'swiper', 'avh-animated-carousel-script' ];
	}

	protected function register_controls(): void {

		/* ── Content tab: Slides ── */

		$this->start_controls_section(
			'section_slides',
			[
				'label' => esc_html__( 'Slides', 'elementor-avh-widgets' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'slide_image',
			[
				'label' => esc_html__( 'Image', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'slide_title',
			[
				'label' => esc_html__( 'Title (navigator)', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Slide', 'elementor-avh-widgets' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'slides',
			[
				'label' => esc_html__( 'Slides', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'slide_title' => esc_html__( 'Slide #1', 'elementor-avh-widgets' ) ],
					[ 'slide_title' => esc_html__( 'Slide #2', 'elementor-avh-widgets' ) ],
					[ 'slide_title' => esc_html__( 'Slide #3', 'elementor-avh-widgets' ) ],
					[ 'slide_title' => esc_html__( 'Slide #4', 'elementor-avh-widgets' ) ],
					[ 'slide_title' => esc_html__( 'Slide #5', 'elementor-avh-widgets' ) ],
				],
				'title_field' => '{{{ slide_title }}}',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'slide_image_size',
				'default' => 'large',
			]
		);

		$this->end_controls_section();

		/* ── Content tab: Settings ── */

		$this->start_controls_section(
			'section_carousel_settings',
			[
				'label' => esc_html__( 'Settings', 'elementor-avh-widgets' ),
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay speed', 'elementor-avh-widgets' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [ 'autoplay' => 'yes' ],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on hover', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [ 'autoplay' => 'yes' ],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'infinite',
			[
				'label' => esc_html__( 'Infinite scroll', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Transition duration', 'elementor-avh-widgets' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'arrows',
			[
				'label' => esc_html__( 'Arrows', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pagination',
			[
				'label' => esc_html__( 'Pagination', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => [
					'' => esc_html__( 'None', 'elementor-avh-widgets' ),
					'bullets' => esc_html__( 'Dots', 'elementor-avh-widgets' ),
					'fraction' => esc_html__( 'Fraction', 'elementor-avh-widgets' ),
					'progressbar' => esc_html__( 'Progress bar', 'elementor-avh-widgets' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		/* ── Style tab: Animated layout ── */

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
					'px' => [ 'min' => 80, 'max' => 1400 ],
					'%'  => [ 'min' => 10, 'max' => 100 ],
				],
				'default' => [ 'unit' => '%', 'size' => 54 ],
				'tablet_default' => [ 'unit' => '%', 'size' => 48 ],
				'mobile_default' => [ 'unit' => '%', 'size' => 88 ],
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
					'px' => [ 'min' => 60, 'max' => 1200 ],
					'%'  => [ 'min' => 8,  'max' => 90 ],
				],
				'default' => [ 'unit' => '%', 'size' => 34 ],
				'tablet_default' => [ 'unit' => '%', 'size' => 38 ],
				'mobile_default' => [ 'unit' => '%', 'size' => 78 ],
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
					'px' => [ 'min' => 40, 'max' => 1000 ],
					'%'  => [ 'min' => 5,  'max' => 80 ],
				],
				'default' => [ 'unit' => '%', 'size' => 20 ],
				'tablet_default' => [ 'unit' => '%', 'size' => 24 ],
				'mobile_default' => [ 'unit' => '%', 'size' => 70 ],
				'selectors' => [
					'{{WRAPPER}}' => '--avh-ac-outer-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'avh_slide_height',
			[
				'label' => esc_html__( 'Slide height', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [ 'min' => 80, 'max' => 1200 ],
					'vh' => [ 'min' => 10, 'max' => 100 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 400 ],
				'selectors' => [
					'{{WRAPPER}} .avh-ac-slide' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'avh_slide_gap',
			[
				'label' => esc_html__( 'Gap between slides', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 10 ],
				'frontend_available' => true,
				'render_type' => 'none',
			]
		);

		$this->add_responsive_control(
			'avh_slide_border_radius',
			[
				'label' => esc_html__( 'Border radius', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .avh-ac-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$slides = $settings['slides'] ?? [];

		if ( empty( $slides ) ) {
			return;
		}

		$has_autoplay = 'yes' === ( $settings['autoplay'] ?? '' );
		$show_arrows = 'yes' === ( $settings['arrows'] ?? '' );
		$pagination = $settings['pagination'] ?? '';
		$direction = is_rtl() ? 'rtl' : 'ltr';

		$this->add_render_attribute( 'wrapper', [
			'class' => 'avh-ac-carousel swiper',
			'dir' => $direction,
			'role' => 'region',
			'aria-roledescription' => 'carousel',
			'aria-label' => esc_html__( 'Animated Carousel', 'elementor-avh-widgets' ),
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="swiper-wrapper" aria-live="<?php echo $has_autoplay ? 'off' : 'polite'; ?>">
				<?php foreach ( $slides as $index => $slide ) :
					$image = $slide['slide_image'] ?? [];
					$image_url = Group_Control_Image_Size::get_attachment_image_src(
						$image['id'] ?? 0,
						'slide_image_size',
						$settings
					);
					if ( ! $image_url && ! empty( $image['url'] ) ) {
						$image_url = $image['url'];
					}
					$slide_count = $index + 1;
				?>
				<div class="swiper-slide" data-slide="<?php echo esc_attr( $slide_count ); ?>" role="group" aria-roledescription="slide" aria-label="<?php printf( esc_attr__( '%1$s of %2$s', 'elementor-avh-widgets' ), $slide_count, count( $slides ) ); ?>">
					<div class="avh-ac-slide">
						<?php if ( $image_url ) : ?>
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $slide['slide_title'] ?? '' ); ?>" loading="lazy" />
						<?php endif; ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<?php if ( $show_arrows && count( $slides ) > 1 ) : ?>
			<div class="elementor-swiper-button elementor-swiper-button-prev" role="button" tabindex="0" aria-label="<?php echo esc_attr__( 'Previous', 'elementor-avh-widgets' ); ?>">
				<i class="eicon-chevron-left" aria-hidden="true"></i>
			</div>
			<div class="elementor-swiper-button elementor-swiper-button-next" role="button" tabindex="0" aria-label="<?php echo esc_attr__( 'Next', 'elementor-avh-widgets' ); ?>">
				<i class="eicon-chevron-right" aria-hidden="true"></i>
			</div>
			<?php endif; ?>
			<?php if ( $pagination && count( $slides ) > 1 ) : ?>
			<div class="swiper-pagination"></div>
			<?php endif; ?>
		</div>
		<?php
	}
}
