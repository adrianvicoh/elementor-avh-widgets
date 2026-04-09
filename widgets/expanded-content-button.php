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

    public function get_script_depends(): array
    {
        return ['expanded-content-button-script'];
    }

    public function get_style_depends(): array
    {
        return ['expanded-content-button-style'];
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

        /*Button title*/
        $this->add_control(
            'button_title',
            [
                'label' => esc_html__('Button Title', 'textdomain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Button title', 'textdomain'),
                'placeholder' => esc_html__('Type button title', 'textdomain'),
            ]
        );

        /* Content Control */
        $this->add_control(
            'item_content',
            [
                'label' => esc_html__('Description', 'textdomain'),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => esc_html__('Default content', 'textdomain'),
                'placeholder' => esc_html__('Type your content here', 'textdomain'),
            ]
        );

        /*General features*/
        $this->add_control(
            'general_features',
            [
                'label' => esc_html__('General Features', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        /*Button position*/
        $this->add_control(
            'button_box_position',
            [
                'label' => esc_html__('Button Box position', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row-reverse' => [
                        'title' => esc_html__('Left', 'textdomain'),
                        'icon' => 'eicon-arrow-left',
                    ],
                    'row' => [
                        'title' => esc_html__('Right', 'textdomain'),
                        'icon' => 'eicon-arrow-right',
                    ],
                    'column-reverse' => [
                        'title' => esc_html__('Up', 'textdomain'),
                        'icon' => 'eicon-arrow-up',
                    ],
                    'column' => [
                        'title' => esc_html__('Down', 'textdomain'),
                        'icon' => 'eicon-arrow-down',
                    ],
                ],
                'default' => 'down',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-widget-container' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        /*Content box features*/
        $this->add_control(
            'content_box_features',
            [
                'label' => esc_html__('Content Box Features', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        /*Content box width*/
        $this->add_control(
            'content_box_width',
            [
                'label' => esc_html__('Width', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    /*'size' => 50,*/
                ],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-main' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /*Content box height*/
        $this->add_control(
            'content_box_height',
            [
                'label' => esc_html__('Height', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    /*'size' => 50,*/
                ],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-main' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /*Button box features*/
        $this->add_control(
            'button_box_features',
            [
                'label' => esc_html__('Button Box Features', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );



        /*Button box width*/
        $this->add_control(
            'button_box_width',
            [
                'label' => esc_html__('Width', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    /*'size' => 50,*/
                ],
                'selectors' => [
                    '{{WRAPPER}} .button-box-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        /*Button box height*/
        $this->add_control(
            'button_box_height',
            [
                'label' => esc_html__('Height', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    /*'size' => 50,*/
                ],
                'selectors' => [
                    '{{WRAPPER}} .button-box-container' => 'height: {{SIZE}}{{UNIT}};',
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

        if (empty($settings['item_content'])) {
            return;
        }
?>
        <div class="expanded-content-widget-container">
            <div class="expanded-content-main">
                <div class="expanded-content-wrapper">
                    <?php echo $settings['item_content']; ?>
                </div>
            </div>
            <div class="button-box-container">
                <div class="elementor-button-wrapper">
                    <a class="elementor-button elementor-button-link switch-content-button">
                        <span class="elementor-button-content-wrapper">
                            <span class="elementor-button-text">
                                <?php echo $settings['button_title']; ?>
                            </span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    <?php

    }

    protected function content_template(): void
    {
    ?>
        <#
            if ( ''===settings.item_content ) {
            return;
            }
            #>
            <div class="expanded-content-widget-container">
                <div class="expanded-content-main">
                    <div class="expanded-content-hidden">
                        {{{ settings.item_content }}}
                    </div>
                </div>
                <div class="button-box-container">
                    <div class="elementor-button-wrapper">
                        <a class="elementor-button elementor-button-link switch-content-button">
                            <span class="elementor-button-content-wrapper">
                                <span class="elementor-button-text">
                                    {{{ settings.button_title }}}
                                </span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

    <?php
    }
}
