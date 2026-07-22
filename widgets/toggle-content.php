<?php
/**
 * Toggle Content — button-controlled content reveal widget for Elementor.
 */

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Toggle_Content extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'toggle-content';
	}

	public function get_title(): string {
		return esc_html__( 'Toggle Content', 'custom-elementor-widgets' );
	}

	public function get_icon(): string {
		return 'eicon-toggle';
	}

	public function get_categories(): array {
		return [ 'custom-elementor-widgets' ];
	}

	public function get_keywords(): array {
		return [ 'toggle', 'accordion', 'reveal', 'content', 'button' ];
	}

	public function get_style_depends(): array {
		return [ 'toggle-content-widget-style' ];
	}

	public function get_script_depends(): array {
		return [ 'toggle-content-widget-script' ];
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_toggle',
			[
				'label' => esc_html__( 'Toggle', 'custom-elementor-widgets' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Button text', 'custom-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Show content', 'custom-elementor-widgets' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'close_text',
			[
				'label' => esc_html__( 'Text when open', 'custom-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Hide content', 'custom-elementor-widgets' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'custom-elementor-widgets' ),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Add your content here. It will be shown or hidden with the button.', 'custom-elementor-widgets' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'open_by_default',
			[
				'label' => esc_html__( 'Open by default', 'custom-elementor-widgets' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => esc_html__( 'Button', 'custom-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_alignment',
			[
				'label' => esc_html__( 'Alignment', 'custom-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'custom-elementor-widgets' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'custom-elementor-widgets' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'End', 'custom-elementor-widgets' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'flex-start',
				'selectors' => [
					'{{WRAPPER}} .toggle-content__button-wrap' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_alignment',
			[
				'label' => esc_html__( 'Content alignment', 'custom-elementor-widgets' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'custom-elementor-widgets' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'custom-elementor-widgets' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'custom-elementor-widgets' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .toggle-content__body' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$content = $settings['content'] ?? '';

		if ( '' === trim( (string) $content ) ) {
			return;
		}

		$button_text = $settings['button_text'] ?? esc_html__( 'Show content', 'custom-elementor-widgets' );
		$close_text = $settings['close_text'] ?? esc_html__( 'Hide content', 'custom-elementor-widgets' );
		$open_by_default = 'yes' === ( $settings['open_by_default'] ?? '' );
		$content_id = 'toggle-content-' . $this->get_id();

		$this->add_render_attribute(
			'wrapper',
			[
				'class' => [
					'toggle-content',
					$open_by_default ? 'is-open' : 'is-closed',
				],
			]
		);

		$this->add_render_attribute(
			'button',
			[
				'class' => 'toggle-content__button',
				'type' => 'button',
				'aria-controls' => $content_id,
				'aria-expanded' => $open_by_default ? 'true' : 'false',
				'data-open-text' => $button_text,
				'data-close-text' => $close_text,
			]
		);

		$this->add_render_attribute(
			'body',
			[
				'class' => 'toggle-content__body',
				'id' => $content_id,
			]
		);

		if ( ! $open_by_default ) {
			$this->add_render_attribute( 'body', 'hidden', 'hidden' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="toggle-content__button-wrap">
				<button <?php $this->print_render_attribute_string( 'button' ); ?>>
					<span class="toggle-content__button-text"><?php echo esc_html( $button_text ); ?></span>
				</button>
			</div>

			<div <?php $this->print_render_attribute_string( 'body' ); ?>>
				<?php echo wp_kses_post( $content ); ?>
			</div>
		</div>
		<?php
	}
}
