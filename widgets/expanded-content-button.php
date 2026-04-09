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

        /* Widget container style */
        $this->add_control(
            'widget_container_style_heading',
            [
                'label' => esc_html__('Widget Container Styles', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'widget_container_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .expanded-content-widget-container',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'widget_container_border',
                'selector' => '{{WRAPPER}} .expanded-content-widget-container',
            ]
        );

        $this->add_responsive_control(
            'widget_container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_container_padding',
            [
                'label' => esc_html__('Padding', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-widget-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_container_margin',
            [
                'label' => esc_html__('Margin', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-widget-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_container_gap',
            [
                'label' => esc_html__('Gap', 'textdomain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-widget-container' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'widget_container_box_shadow',
                'selector' => '{{WRAPPER}} .expanded-content-widget-container',
            ]
        );

        $this->add_responsive_control(
            'widget_container_horizontal_alignment',
            [
                'label' => esc_html__('Items Horizontal Alignment', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'textdomain'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'textdomain'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'textdomain'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'textdomain'),
                        'icon' => 'eicon-justify-space-between-h',
                    ],
                    'space-around' => [
                        'title' => esc_html__('Space Around', 'textdomain'),
                        'icon' => 'eicon-justify-space-around-h',
                    ],
                    'space-evenly' => [
                        'title' => esc_html__('Space Evenly', 'textdomain'),
                        'icon' => 'eicon-justify-space-evenly-h',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-widget-container' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'widget_container_vertical_alignment',
            [
                'label' => esc_html__('Items Vertical Alignment', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'textdomain'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'textdomain'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'textdomain'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'textdomain'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-widget-container' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        /* Main and button section styles */
        $this->add_control(
            'sections_style_heading',
            [
                'label' => esc_html__('Main and Button Box Styles', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'expanded_main_style_heading',
            [
                'label' => esc_html__('Expanded Main Styles', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'expanded_main_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .expanded-content-main',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'expanded_main_border',
                'selector' => '{{WRAPPER}} .expanded-content-main',
            ]
        );

        $this->add_responsive_control(
            'expanded_main_border_radius',
            [
                'label' => esc_html__('Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-main' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'expanded_main_padding',
            [
                'label' => esc_html__('Padding', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'expanded_main_margin',
            [
                'label' => esc_html__('Margin', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-main' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'expanded_main_box_shadow',
                'selector' => '{{WRAPPER}} .expanded-content-main',
            ]
        );

        $this->add_control(
            'button_box_style_heading',
            [
                'label' => esc_html__('Button Box Container Styles', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_box_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .button-box-container',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_box_border',
                'selector' => '{{WRAPPER}} .button-box-container',
            ]
        );

        $this->add_responsive_control(
            'button_box_border_radius',
            [
                'label' => esc_html__('Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .button-box-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_box_padding',
            [
                'label' => esc_html__('Padding', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .button-box-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_box_margin',
            [
                'label' => esc_html__('Margin', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .button-box-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_container_shadow',
                'selector' => '{{WRAPPER}} .button-box-container',
            ]
        );

        /* Button style */
        $this->add_control(
            'button_style_heading',
            [
                'label' => esc_html__('Button Styles', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .switch-content-button, {{WRAPPER}} .switch-content-button .elementor-button-text',
            ]
        );

        $this->start_controls_tabs('button_style_tabs');

        $this->start_controls_tab(
            'button_style_tab_normal',
            [
                'label' => esc_html__('Normal', 'textdomain'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .switch-content-button, {{WRAPPER}} .switch-content-button .elementor-button-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .switch-content-button',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .switch-content-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_style_tab_hover',
            [
                'label' => esc_html__('Hover', 'textdomain'),
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label' => esc_html__('Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .switch-content-button:hover, {{WRAPPER}} .switch-content-button:hover .elementor-button-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .switch-content-button:hover',
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label' => esc_html__('Border Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .switch-content-button:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'button_border_border!' => '',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_border_radius',
            [
                'label' => esc_html__('Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .switch-content-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => esc_html__('Padding', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .switch-content-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .switch-content-button',
            ]
        );

        $this->add_responsive_control(
            'button_content_horizontal_position',
            [
                'label' => esc_html__('Button Horizontal Position', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'textdomain'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'textdomain'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'textdomain'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .button-box-container .elementor-button-wrapper' => 'display:flex; justify-content: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'button_content_vertical_position',
            [
                'label' => esc_html__('Button Vertical Position', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'textdomain'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'textdomain'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'textdomain'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'textdomain'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .button-box-container .elementor-button-wrapper' => 'display:flex; align-items: {{VALUE}}; min-height: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_text_align',
            [
                'label' => esc_html__('Button Text Align', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
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
                    'justify' => [
                        'title' => esc_html__('Justified', 'textdomain'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .switch-content-button .elementor-button-content-wrapper' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        /* Content style */
        $this->add_control(
            'content_style_heading',
            [
                'label' => esc_html__('Content Styles', 'textdomain'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'selector' => '{{WRAPPER}} .expanded-content-wrapper, {{WRAPPER}} .expanded-content-wrapper p',
            ]
        );

        $this->add_control(
            'content_text_color',
            [
                'label' => esc_html__('Text Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-wrapper, {{WRAPPER}} .expanded-content-wrapper p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_link_color',
            [
                'label' => esc_html__('Link Color', 'textdomain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-wrapper a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'content_background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .expanded-content-wrapper',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'content_border',
                'selector' => '{{WRAPPER}} .expanded-content-wrapper',
            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label' => esc_html__('Padding', 'textdomain'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_box_shadow',
                'selector' => '{{WRAPPER}} .expanded-content-wrapper',
            ]
        );

        $this->add_responsive_control(
            'content_horizontal_position',
            [
                'label' => esc_html__('Content Horizontal Position', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'textdomain'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'textdomain'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'textdomain'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-main .expanded-content-wrapper' => 'display:flex; justify-content: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'content_vertical_position',
            [
                'label' => esc_html__('Content Vertical Position', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'textdomain'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'textdomain'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'textdomain'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'textdomain'),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-main .expanded-content-wrapper' => 'display:flex; align-items: {{VALUE}}; min-height: 100%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_text_align',
            [
                'label' => esc_html__('Content Text Align', 'textdomain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
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
                    'justify' => [
                        'title' => esc_html__('Justified', 'textdomain'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .expanded-content-wrapper' => 'text-align: {{VALUE}};',
                ],
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
                    <div class="expanded-content-wrapper">
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
