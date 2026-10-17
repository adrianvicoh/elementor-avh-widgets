<?php
/**
 * Numeric Chart — configurable Chart.js graph for Elementor.
 */

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Numeric_Chart extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'numeric-chart';
	}

	public function get_title(): string {
		return esc_html__( 'Numeric Chart', 'custom-elementor-widgets' );
	}

	public function get_icon(): string {
		return 'eicon-skill-bar';
	}

	public function get_categories(): array {
		return [ 'custom-elementor-widgets' ];
	}

	public function get_keywords(): array {
		return [ 'chart', 'graph', 'numbers', 'data', 'statistics' ];
	}

	public function get_style_depends(): array {
		return [ 'numeric-chart-widget-style' ];
	}

	public function get_script_depends(): array {
		return [ 'numeric-chart-widget-script' ];
	}

	protected function register_controls(): void {
		$this->start_controls_section(
			'section_chart',
			[
				'label' => esc_html__( 'Chart', 'custom-elementor-widgets' ),
			]
		);

		$this->add_control(
			'chart_title',
			[
				'label'       => esc_html__( 'Title', 'custom-elementor-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Performance by month', 'custom-elementor-widgets' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'chart_type',
			[
				'label'   => esc_html__( 'Type', 'custom-elementor-widgets' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'bar',
				'options' => [
					'bar'     => esc_html__( 'Bar', 'custom-elementor-widgets' ),
					'line'    => esc_html__( 'Line', 'custom-elementor-widgets' ),
					'doughnut' => esc_html__( 'Doughnut', 'custom-elementor-widgets' ),
				],
			]
		);

		$this->add_control(
			'show_legend',
			[
				'label'        => esc_html__( 'Show legend', 'custom-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'show_grid',
			[
				'label'        => esc_html__( 'Show grid', 'custom-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'condition'    => [
					'chart_type!' => 'doughnut',
				],
			]
		);

		$this->add_control(
			'fill_line',
			[
				'label'        => esc_html__( 'Fill line area', 'custom-elementor-widgets' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => [
					'chart_type' => 'line',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_data',
			[
				'label' => esc_html__( 'Data', 'custom-elementor-widgets' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'data_label',
			[
				'label'       => esc_html__( 'Label', 'custom-elementor-widgets' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Item', 'custom-elementor-widgets' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'data_value',
			[
				'label'   => esc_html__( 'Value', 'custom-elementor-widgets' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 10,
			]
		);

		$repeater->add_control(
			'data_color',
			[
				'label'   => esc_html__( 'Color', 'custom-elementor-widgets' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#2D6CDF',
			]
		);

		$this->add_control(
			'data_points',
			[
				'label'       => esc_html__( 'Data points', 'custom-elementor-widgets' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'data_label' => esc_html__( 'January', 'custom-elementor-widgets' ),
						'data_value' => 42,
						'data_color' => '#2D6CDF',
					],
					[
						'data_label' => esc_html__( 'February', 'custom-elementor-widgets' ),
						'data_value' => 65,
						'data_color' => '#6B4CE6',
					],
					[
						'data_label' => esc_html__( 'March', 'custom-elementor-widgets' ),
						'data_value' => 51,
						'data_color' => '#008C72',
					],
				],
				'title_field' => '{{{ data_label }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_chart',
			[
				'label' => esc_html__( 'Chart', 'custom-elementor-widgets' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'chart_height',
			[
				'label'      => esc_html__( 'Height', 'custom-elementor-widgets' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range'      => [
					'px' => [
						'min' => 160,
						'max' => 1000,
					],
					'vh' => [
						'min' => 15,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 360,
				],
				'selectors'  => [
					'{{WRAPPER}} .numeric-chart__canvas-wrap' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	private function build_settings_payload( array $settings ): array {
		$palette = [ '#2D6CDF', '#6B4CE6', '#008C72', '#D66222', '#BE3F7D', '#4C647F' ];
		$labels = [];
		$values = [];
		$colors = [];

		foreach ( $settings['data_points'] ?? [] as $index => $point ) {
			$labels[] = sanitize_text_field( $point['data_label'] ?? '' );
			$values[] = is_numeric( $point['data_value'] ?? null ) ? (float) $point['data_value'] : 0;
			$colors[] = sanitize_hex_color( $point['data_color'] ?? '' ) ?: $palette[ $index % count( $palette ) ];
		}

		$type = in_array( $settings['chart_type'] ?? '', [ 'bar', 'line', 'doughnut' ], true )
			? $settings['chart_type']
			: 'bar';

		return [
			'title'      => sanitize_text_field( $settings['chart_title'] ?? '' ),
			'type'       => $type,
			'labels'     => $labels,
			'values'     => $values,
			'colors'     => $colors,
			'showLegend' => 'yes' === ( $settings['show_legend'] ?? '' ),
			'showGrid'   => 'yes' === ( $settings['show_grid'] ?? '' ),
			'fillLine'   => 'yes' === ( $settings['fill_line'] ?? '' ),
		];
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		$payload = $this->build_settings_payload( $settings );

		if ( empty( $payload['labels'] ) ) {
			return;
		}

		$this->add_render_attribute(
			'wrapper',
			[
				'class'         => 'numeric-chart',
				'data-settings' => wp_json_encode( $payload ),
			]
		);
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="numeric-chart__canvas-wrap">
				<canvas
					class="numeric-chart__canvas"
					role="img"
					aria-label="<?php echo esc_attr( $payload['title'] ?: __( 'Numeric chart', 'custom-elementor-widgets' ) ); ?>"
				></canvas>
			</div>
		</div>
		<?php
	}
}
