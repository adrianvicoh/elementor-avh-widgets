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

        /* SwitchContent Button */
        $this->add_control(
			'switch_content_button',
			[
				'label' => esc_html__( 'Switch Content', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::BUTTON,
				'separator' => 'before',
				'button_type' => 'success',
				'text' => esc_html__( 'Switch Content', 'textdomain' ),
				'event' => 'namespace:editor:switch',
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
		<div class="expanded-content-text">
			<?php echo $settings['item_content']; ?>
		</div>
        <button class="switch-content-button">
            Switch Content
        </button>
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
		<div class="expanded-content-text">
			{{{ settings.item_content }}}
		</div>
        <button class="switch-content-button">
            Switch Content
        </button>
		<?php
    }
}
