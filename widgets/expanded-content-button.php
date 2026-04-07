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
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'type' => \Elementor\Controls_Manager::TEXT,
                'label' => esc_html__('Title', 'textdomain'),
                'placeholder' => esc_html__('Enter your title', 'textdomain'),
            ]
        );

        $this->add_control(
            'size',
            [
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label' => esc_html__('Size', 'textdomain'),
                'placeholder' => '0',
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 50,
            ]
        );

        $this->add_control(
            'open_lightbox',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => esc_html__('Lightbox', 'textdomain'),
                'options' => [
                    'default' => esc_html__('Default', 'textdomain'),
                    'yes' => esc_html__('Yes', 'textdomain'),
                    'no' => esc_html__('No', 'textdomain'),
                ],
                'default' => 'no',
            ]
        );

        $this->add_control(
            'alignment',
            [
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'label' => esc_html__('Alignment', 'textdomain'),
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'textdomain'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'textdomain'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'textdomain'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => esc_html__('Choose Image', 'textdomain'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Style', 'textdomain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__('Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f00',
                'selectors' => [
                    '{{WRAPPER}} h3' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        if (empty($settings['title'])) {
            return;
        }
?>
        <h3>
            <?php echo $settings['title']; ?>
        </h3>
    <?php

        echo \Elementor\Group_Control_Image_Size::get_attachment_image_html($settings);
    }

    protected function content_template(): void
    {
    ?>
        <#
            if ( ''===settings.title ) {
            return;
            }
            #>
            <h3>
                {{{ settings.title }}}
            </h3>
            <#
                const image={
                id: settings.image.id,
                url: settings.image.url,
                size: settings.image_size,
                dimension: settings.image_custom_dimension,
                model: view.getEditModel()
                };

                const image_url=elementor.imagesManager.getImageUrl( image );

                if ( ''===image_url ) {
                return;
                }
                #>
                <img src="{{{ image_url }}}">
        <?php
    }
}
