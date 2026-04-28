<?php
/**
 * AVH Coverflow Slider — image-based Swiper slider with 3D coverflow effect.
 *
 * Mirrors the configuration of the developer reference snippet (Swiper 11
 * coverflow effect with rotate/stretch/depth/modifier/slideShadows, three
 * responsive breakpoints, fraction pagination, navigation arrows) but exposes
 * every parameter as an Elementor control so the slider can be configured
 * visually without touching code.
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AVH_Coverflow_Slider extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'avh-coverflow-slider';
	}

	public function get_title(): string {
		return esc_html__( 'AVH Coverflow Slider', 'elementor-avh-widgets' );
	}

	public function get_icon(): string {
		return 'eicon-slider-3d';
	}

	public function get_categories(): array {
		return [ 'elementor-avh-widgets' ];
	}

	public function get_keywords(): array {
		return [ 'coverflow', 'swiper', 'slider', '3d', 'carousel', 'avh' ];
	}

	public function get_style_depends(): array {
		return [ 'avh-coverflow-slider-style' ];
	}

	public function get_script_depends(): array {
		return [ 'avh-coverflow-slider-script' ];
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

		$repeater->add_control(
			'slide_alt',
			[
				'label' => esc_html__( 'Alt text', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
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

		/* ── Content tab: Coverflow effect ── */

		$this->start_controls_section(
			'section_coverflow',
			[
				'label' => esc_html__( 'Coverflow effect', 'elementor-avh-widgets' ),
			]
		);

		$this->add_control(
			'rotate',
			[
				'label' => esc_html__( 'Rotate', 'elementor-avh-widgets' ) . ' (deg)',
				'type' => Controls_Manager::NUMBER,
				'default' => 50,
				'min' => -180,
				'max' => 180,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'stretch',
			[
				'label' => esc_html__( 'Stretch', 'elementor-avh-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'depth',
			[
				'label' => esc_html__( 'Depth', 'elementor-avh-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
				'min' => 0,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'modifier',
			[
				'label' => esc_html__( 'Modifier', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'step' => 0.1,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slide_shadows',
			[
				'label' => esc_html__( 'Slide shadows', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		/* ── Content tab: Slider settings ── */

		$this->start_controls_section(
			'section_slider_settings',
			[
				'label' => esc_html__( 'Slider settings', 'elementor-avh-widgets' ),
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'elementor-avh-widgets' ),
					'vertical' => esc_html__( 'Vertical', 'elementor-avh-widgets' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => esc_html__( 'Loop', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'grab_cursor',
			[
				'label' => esc_html__( 'Grab cursor', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'centered_slides',
			[
				'label' => esc_html__( 'Centered slides', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Transition speed', 'elementor-avh-widgets' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'min' => 0,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		/* ── Content tab: Breakpoints ── */

		$this->start_controls_section(
			'section_breakpoints',
			[
				'label' => esc_html__( 'Breakpoints', 'elementor-avh-widgets' ),
			]
		);

		$this->add_control(
			'breakpoints_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => esc_html__( 'These map 1:1 to Swiper breakpoints (min-width thresholds): 0px, 767px, 1024px.', 'elementor-avh-widgets' ),
				'content_classes' => 'elementor-control-field-description',
			]
		);

		$this->add_control(
			'bp_desktop_heading',
			[
				'label' => esc_html__( 'Desktop (≥1024px)', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'bp_desktop_slides',
			[
				'label' => esc_html__( 'Slides per view', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'min' => 1,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'bp_desktop_gap',
			[
				'label' => esc_html__( 'Space between', 'elementor-avh-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'bp_tablet_heading',
			[
				'label' => esc_html__( 'Tablet (≥767px)', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'bp_tablet_slides',
			[
				'label' => esc_html__( 'Slides per view', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 3,
				'min' => 1,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'bp_tablet_gap',
			[
				'label' => esc_html__( 'Space between', 'elementor-avh-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'bp_mobile_heading',
			[
				'label' => esc_html__( 'Mobile (≥0px)', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'bp_mobile_slides',
			[
				'label' => esc_html__( 'Slides per view', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 1,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'bp_mobile_gap',
			[
				'label' => esc_html__( 'Space between', 'elementor-avh-widgets' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		/* ── Content tab: Navigation & pagination ── */

		$this->start_controls_section(
			'section_nav_pag',
			[
				'label' => esc_html__( 'Navigation & pagination', 'elementor-avh-widgets' ),
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
			'prev_label',
			[
				'label' => esc_html__( 'Previous label', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Prev', 'elementor-avh-widgets' ),
				'condition' => [ 'arrows' => 'yes' ],
			]
		);

		$this->add_control(
			'next_label',
			[
				'label' => esc_html__( 'Next label', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Next', 'elementor-avh-widgets' ),
				'condition' => [ 'arrows' => 'yes' ],
			]
		);

		$this->add_control(
			'pagination',
			[
				'label' => esc_html__( 'Pagination', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fraction',
				'options' => [
					'' => esc_html__( 'None', 'elementor-avh-widgets' ),
					'bullets' => esc_html__( 'Bullets', 'elementor-avh-widgets' ),
					'fraction' => esc_html__( 'Fraction', 'elementor-avh-widgets' ),
					'progressbar' => esc_html__( 'Progress bar', 'elementor-avh-widgets' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		/* ── Style tab: Slide ── */

		$this->start_controls_section(
			'section_style_slide',
			[
				'label' => esc_html__( 'Slide', 'elementor-avh-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'slide_min_height',
			[
				'label' => esc_html__( 'Slide min height', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [ 'min' => 100, 'max' => 1200 ],
					'vh' => [ 'min' => 10, 'max' => 100 ],
				],
				'default' => [ 'unit' => 'px', 'size' => 300 ],
				'selectors' => [
					'{{WRAPPER}} .avh-coverflow-swiper-slide' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slide_border_radius',
			[
				'label' => esc_html__( 'Border radius', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .avh-coverflow-swiper-slide' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->end_controls_section();

		/* ── Style tab: Pagination ── */

		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'Pagination', 'elementor-avh-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [ 'pagination!' => '' ],
			]
		);

		$this->add_responsive_control(
			'pagination_max_width',
			[
				'label' => esc_html__( 'Max width', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [ 'min' => 1, 'max' => 100 ],
					'px' => [ 'min' => 20, 'max' => 600 ],
				],
				'default' => [ 'unit' => '%', 'size' => 10 ],
				'selectors' => [
					'{{WRAPPER}} .avh-coverflow-swiper-pagination' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_align',
			[
				'label' => esc_html__( 'Alignment', 'elementor-avh-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'elementor-avh-widgets' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor-avh-widgets' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'elementor-avh-widgets' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .avh-coverflow-swiper-pagination-wrap' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Build the JSON payload consumed by the frontend script.
	 */
	private function build_settings_payload( array $settings ): array {
		return [
			'rotate' => isset( $settings['rotate'] ) ? (float) $settings['rotate'] : 50,
			'stretch' => isset( $settings['stretch'] ) ? (float) $settings['stretch'] : 0,
			'depth' => isset( $settings['depth'] ) ? (float) $settings['depth'] : 100,
			'modifier' => isset( $settings['modifier'] ) ? (float) $settings['modifier'] : 1,
			'slideShadows' => 'yes' === ( $settings['slide_shadows'] ?? '' ),
			'direction' => in_array( $settings['direction'] ?? '', [ 'horizontal', 'vertical' ], true )
				? $settings['direction']
				: 'horizontal',
			'loop' => 'yes' === ( $settings['loop'] ?? '' ),
			'grabCursor' => 'yes' === ( $settings['grab_cursor'] ?? '' ),
			'centeredSlides' => 'yes' === ( $settings['centered_slides'] ?? '' ),
			'speed' => isset( $settings['speed'] ) ? max( 0, (int) $settings['speed'] ) : 500,
			'arrows' => 'yes' === ( $settings['arrows'] ?? '' ),
			'pagination' => in_array( $settings['pagination'] ?? '', [ 'bullets', 'fraction', 'progressbar' ], true )
				? $settings['pagination']
				: '',
			'breakpoints' => [
				'0' => [
					'slidesPerView' => max( 1, (int) ( $settings['bp_mobile_slides'] ?? 1 ) ),
					'spaceBetween' => max( 0, (int) ( $settings['bp_mobile_gap'] ?? 0 ) ),
				],
				'767' => [
					'slidesPerView' => max( 1, (int) ( $settings['bp_tablet_slides'] ?? 3 ) ),
					'spaceBetween' => max( 0, (int) ( $settings['bp_tablet_gap'] ?? 0 ) ),
				],
				'1024' => [
					'slidesPerView' => max( 1, (int) ( $settings['bp_desktop_slides'] ?? 3 ) ),
					'spaceBetween' => max( 0, (int) ( $settings['bp_desktop_gap'] ?? 0 ) ),
				],
			],
		];
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$slides = $settings['slides'] ?? [];

		if ( empty( $slides ) ) {
			return;
		}

		$payload = $this->build_settings_payload( $settings );
		$show_arrows = ! empty( $payload['arrows'] );
		$pagination_type = $payload['pagination'];
		$direction = is_rtl() ? 'rtl' : 'ltr';

		$this->add_render_attribute( 'wrapper', [
			'class' => 'avh-coverflow-swiper',
			'dir' => $direction,
			'role' => 'region',
			'aria-roledescription' => 'carousel',
			'aria-label' => esc_attr__( 'Coverflow Slider', 'elementor-avh-widgets' ),
			'data-settings' => wp_json_encode( $payload ),
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="avh-coverflow-swiper-wrapper">
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
					$alt = $slide['slide_alt'] ?? '';
					if ( ! $alt ) {
						$alt = $slide['slide_title'] ?? '';
					}
				?>
				<div class="avh-coverflow-swiper-slide" data-slide="<?php echo esc_attr( (string) $slide_count ); ?>" role="group" aria-roledescription="slide" aria-label="<?php printf( esc_attr__( '%1$s of %2$s', 'elementor-avh-widgets' ), $slide_count, count( $slides ) ); ?>">
					<?php if ( $image_url ) : ?>
					<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" />
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>

			<?php if ( $pagination_type && count( $slides ) > 1 ) : ?>
			<div class="avh-coverflow-swiper-pagination-wrap">
				<div class="avh-coverflow-swiper-pagination" data-type="<?php echo esc_attr( $pagination_type ); ?>"></div>
			</div>
			<?php endif; ?>

			<?php if ( $show_arrows && count( $slides ) > 1 ) : ?>
			<div class="avh-coverflow-swiper-nav">
				<div class="avh-coverflow-swiper-prev" role="button" tabindex="0" aria-label="<?php echo esc_attr( $settings['prev_label'] ?? __( 'Previous', 'elementor-avh-widgets' ) ); ?>">
					<span class="avh-coverflow-swiper-arrow-icon" aria-hidden="true">
						<i class="eicon-chevron-left"></i>
					</span>
					<span class="avh-coverflow-swiper-arrow-label"><?php echo esc_html( $settings['prev_label'] ?? __( 'Prev', 'elementor-avh-widgets' ) ); ?></span>
				</div>
				<div class="avh-coverflow-swiper-next" role="button" tabindex="0" aria-label="<?php echo esc_attr( $settings['next_label'] ?? __( 'Next', 'elementor-avh-widgets' ) ); ?>">
					<span class="avh-coverflow-swiper-arrow-label"><?php echo esc_html( $settings['next_label'] ?? __( 'Next', 'elementor-avh-widgets' ) ); ?></span>
					<span class="avh-coverflow-swiper-arrow-icon" aria-hidden="true">
						<i class="eicon-chevron-right"></i>
					</span>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
