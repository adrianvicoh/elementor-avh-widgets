<?php
/**
 * Draggable Boxes — freely moveable boxes constrained to their canvas.
 */

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Draggable_Boxes extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'draggable-boxes';
	}

	public function get_title(): string {
		return esc_html__( 'Draggable Boxes', 'custom-elementor-widgets' );
	}

	public function get_icon(): string {
		return 'eicon-drag-n-drop';
	}

	public function get_categories(): array {
		return [ 'custom-elementor-widgets' ];
	}

	public function get_keywords(): array {
		return [ 'drag', 'drop', 'boxes', 'interactive', 'cards' ];
	}

	public function get_style_depends(): array {
		return [ 'draggable-boxes-widget-style' ];
	}

	public function get_script_depends(): array {
		return [ 'draggable-boxes-widget-script' ];
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_boxes',
			[
				'label' => esc_html__( 'Boxes', 'custom-elementor-widgets' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'box_title',
			[
				'label'       => esc_html__( 'Title', 'custom-elementor-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Draggable box', 'custom-elementor-widgets' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'box_content',
			[
				'label'   => esc_html__( 'Content', 'custom-elementor-widgets' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Click or touch and drag me.', 'custom-elementor-widgets' ),
				'rows'    => 3,
			]
		);

		$repeater->add_control(
			'box_x',
			[
				'label'   => esc_html__( 'Horizontal position', 'custom-elementor-widgets' ) . ' (%)',
				'type'    => Controls_Manager::NUMBER,
				'default' => 10,
				'min'     => 0,
				'max'     => 100,
			]
		);

		$repeater->add_control(
			'box_y',
			[
				'label'   => esc_html__( 'Vertical position', 'custom-elementor-widgets' ) . ' (%)',
				'type'    => Controls_Manager::NUMBER,
				'default' => 10,
				'min'     => 0,
				'max'     => 100,
			]
		);

		$repeater->add_control(
			'box_background_color',
			[
				'label'   => esc_html__( 'Background color', 'custom-elementor-widgets' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#2D6CDF',
			]
		);

		$repeater->add_control(
			'box_text_color',
			[
				'label'   => esc_html__( 'Text color', 'custom-elementor-widgets' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
			]
		);

		$this->add_control(
			'boxes',
			[
				'label'       => esc_html__( 'Boxes', 'custom-elementor-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'box_title'            => esc_html__( 'Plan', 'custom-elementor-widgets' ),
						'box_content'          => esc_html__( 'Move this box anywhere in the canvas.', 'custom-elementor-widgets' ),
						'box_x'                => 10,
						'box_y'                => 12,
						'box_background_color' => '#2D6CDF',
					],
					[
						'box_title'            => esc_html__( 'Design', 'custom-elementor-widgets' ),
						'box_content'          => esc_html__( 'Drag with your mouse or finger.', 'custom-elementor-widgets' ),
						'box_x'                => 55,
						'box_y'                => 28,
						'box_background_color' => '#6B4CE6',
					],
					[
						'box_title'            => esc_html__( 'Launch', 'custom-elementor-widgets' ),
						'box_content'          => esc_html__( 'Keyboard arrows move a focused box.', 'custom-elementor-widgets' ),
						'box_x'                => 24,
						'box_y'                => 62,
						'box_background_color' => '#008C72',
					],
				],
				'title_field' => '{{{ box_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_interaction',
			[
				'label' => esc_html__( 'Interaction', 'custom-elementor-widgets' ),
			]
		);

		$this->add_responsive_control(
			'canvas_height',
			[
				'label'      => esc_html__( 'Canvas height', 'custom-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 1200,
					],
					'vh' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 480,
				],
				'selectors'  => [
					'{{WRAPPER}} .draggable-boxes__canvas' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'inertia',
			[
				'label'        => esc_html__( 'Inertia', 'custom-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'description'  => esc_html__( 'Continue a box briefly after a fast drag.', 'custom-elementor-widgets' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'keyboard_step',
			[
				'label'   => esc_html__( 'Keyboard move distance', 'custom-elementor-widgets' ) . ' (px)',
				'type'    => Controls_Manager::NUMBER,
				'default' => 10,
				'min'     => 1,
				'max'     => 100,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_boxes',
			[
				'label' => esc_html__( 'Boxes', 'custom-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'box_width',
			[
				'label'      => esc_html__( 'Width', 'custom-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 120,
						'max' => 500,
					],
					'%'  => [
						'min' => 20,
						'max' => 90,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 220,
				],
				'selectors'  => [
					'{{WRAPPER}} .draggable-boxes__item' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label'      => esc_html__( 'Padding', 'custom-elementor-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .draggable-boxes__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_border_radius',
			[
				'label'      => esc_html__( 'Border radius', 'custom-elementor-widgets' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .draggable-boxes__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function build_settings_payload( array $settings ): array {
		return [
			'inertia'      => 'yes' === ( $settings['inertia'] ?? '' ),
			'keyboardStep' => isset( $settings['keyboard_step'] ) ? max( 1, (int) $settings['keyboard_step'] ) : 10,
		];
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$boxes    = $settings['boxes'] ?? [];

		if ( empty( $boxes ) ) {
			return;
		}

		$this->add_render_attribute(
			'wrapper',
			[
				'class'         => 'draggable-boxes',
				'data-settings' => wp_json_encode( $this->build_settings_payload( $settings ) ),
			]
		);

		$instruction_id = 'draggable-boxes-instructions-' . $this->get_id();
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<p class="draggable-boxes__instructions" id="<?php echo esc_attr( $instruction_id ); ?>">
				<?php esc_html_e( 'Drag each box with a mouse or touch. Focus a box and use the arrow keys to move it.', 'custom-elementor-widgets' ); ?>
			</p>
			<div class="draggable-boxes__canvas">
				<?php foreach ( $boxes as $box ) :
					$x = min( 100, max( 0, (float) ( $box['box_x'] ?? 0 ) ) );
					$y = min( 100, max( 0, (float) ( $box['box_y'] ?? 0 ) ) );
					$background = sanitize_hex_color( $box['box_background_color'] ?? '' ) ?: '#2D6CDF';
					$text_color = sanitize_hex_color( $box['box_text_color'] ?? '' ) ?: '#FFFFFF';
					$title = $box['box_title'] ?? '';
					$content = $box['box_content'] ?? '';
					?>
					<div
						class="draggable-boxes__item"
						tabindex="0"
						role="group"
						aria-roledescription="<?php echo esc_attr__( 'draggable box', 'custom-elementor-widgets' ); ?>"
						aria-label="<?php echo esc_attr( $title ); ?>"
						aria-describedby="<?php echo esc_attr( $instruction_id ); ?>"
						data-x="0"
						data-y="0"
						style="left: <?php echo esc_attr( $x ); ?>%; top: <?php echo esc_attr( $y ); ?>%; --draggable-boxes-background: <?php echo esc_attr( $background ); ?>; --draggable-boxes-color: <?php echo esc_attr( $text_color ); ?>;"
					>
						<?php if ( '' !== $title ) : ?>
							<h3 class="draggable-boxes__title"><?php echo esc_html( $title ); ?></h3>
						<?php endif; ?>
						<?php if ( '' !== $content ) : ?>
							<p class="draggable-boxes__content"><?php echo esc_html( $content ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
			<p class="draggable-boxes__status" aria-live="polite"></p>
		</div>
		<?php
	}
}
