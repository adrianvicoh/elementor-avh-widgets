<?php
class Expanded_Content_Button extends \Elementor\Widget_Base
{

    public function get_name(): string
    {
        return 'expanded-content-button';
    }

    public function get_title(): string
    {
        return esc_html__('Expanded Content Button', 'textdomain');
    }

    public function get_icon(): string
    {
        return 'eicon-global-settings';
    }

    public function get_categories(): array
    {
        return ['elementor-avh-widgets'];
    }

    public function get_keywords(): array
    {
        return ['keyword', 'keyword'];
    }

    public function get_custom_help_url(): string
    {
        return '';
    }

    protected function get_upsale_data(): array
    {
        return [];
    }

    public function get_script_depends(): array {
		return [ 'expanded-content-button-script' ];
	}

	public function get_style_depends(): array {
		return [ 'expanded-content-button-style' ];
	}

    protected function register_controls(): void
    {

        /* Content Section */
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        /* Title Control */
        $this->add_control(
            'item_content',
			[
				'label' => esc_html__( 'Description', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Default content', 'textdomain' ),
				'placeholder' => esc_html__( 'Type your content here', 'textdomain' ),
			]
        );

        /*Button position*/
        $this->add_control(
            'button_position',
            [
                'label' => esc_html__( 'Button position', 'textdomain' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'row-reverse' => [
						'title' => esc_html__( 'Left', 'textdomain' ),
						'icon' => 'eicon-arrow-left',
					],
					'row' => [
						'title' => esc_html__( 'Right', 'textdomain' ),
						'icon' => 'eicon-arrow-right',
					],
					'column-reverse' => [
						'title' => esc_html__( 'Up', 'textdomain' ),
						'icon' => 'eicon-arrow-up',
					],
                    'column' => [
						'title' => esc_html__( 'Down', 'textdomain' ),
						'icon' => 'eicon-arrow-down',
					],
				],
				'default' => 'down',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .expanded-content-widget' => 'flex-direction: {{VALUE}};',
				],
            ]
        );
        
        $this->end_controls_section();

        /* Style Section */
        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

		if ( empty( $settings['item_content'] ) ) {
			return;
		}
		?>
        <div class="expanded-content-widget">   
            <div class="expanded-content-text">
                <?php echo $settings['item_content']; ?>
            </div>
            <button class="switch-content-button">
                Switch Content
            </button>
        </div>
		<?php

    }

    protected function content_template(): void
    {
        ?>
		<#
		if ( '' === settings.item_content ) {
			return;
		}
		#>
        <div class="expanded-content-widget">
            <div class="expanded-content-text">
                {{{ settings.item_content }}}
            </div>
            <button class="switch-content-button">
                Switch Content
            </button>
        </div>
		
		<?php
    }
}
