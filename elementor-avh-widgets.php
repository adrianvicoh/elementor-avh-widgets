<?php
/*
Plugin Name:    Elementor AVH Widgets
Description:    WordPress plugin with extended widgets for Elementor
Version:        0.0.4
Author:         Adrián Vico Hernández <adrian.vico.95@gmail.com>
License:        GPL v2 or later
Text Domain:    elementor-avh-widgets
*/

/**
 * Register Elementor widgets.
 */
function register_new_widgets($widgets_manager)
{
    require_once __DIR__ . '/widgets/expanded-content-button.php';
    $widgets_manager->register(new \Expanded_Content_Button());

    require_once __DIR__ . '/widgets/animated-carousel.php';
    $widgets_manager->register(new \AVH_Animated_Carousel());
}
add_action('elementor/widgets/register', 'register_new_widgets');

/**
 * Register categories for Elementor widgets.
 */
function add_elementor_widget_categories($elements_manager)
{
    $elements_manager->add_category('elementor-avh-widgets', [
        'title' => esc_html__('AVH Widgets', 'elementor-avh-widgets'),
        'icon' => 'fa fa-plug',
    ]);
}
add_action(
    'elementor/elements/categories_registered',
    'add_elementor_widget_categories',
);

/**
 * Register scripts and styles for Elementor widgets.
 */
function elementor_avh_widgets_dependencies()
{
    $base = __DIR__;

    $expanded_script = $base . '/assets/js/expanded-content-button.js';
    $expanded_style = $base . '/assets/css/expanded-content-button.css';

    wp_register_script(
        'expanded-content-button-script',
        plugins_url('/assets/js/expanded-content-button.js', __FILE__),
        [],
        file_exists($expanded_script) ? filemtime($expanded_script) : false,
        true,
    );

    wp_register_style(
        'expanded-content-button-style',
        plugins_url('/assets/css/expanded-content-button.css', __FILE__),
        [],
        file_exists($expanded_style) ? filemtime($expanded_style) : false,
    );

    $ac_script = $base . '/assets/js/animated-carousel.js';
    $ac_style = $base . '/assets/css/animated-carousel.css';

    wp_register_script(
        'avh-animated-carousel-script',
        plugins_url('/assets/js/animated-carousel.js', __FILE__),
        ['jquery', 'elementor-frontend', 'swiper'],
        file_exists($ac_script) ? filemtime($ac_script) : false,
        true,
    );

    wp_register_style(
        'avh-animated-carousel-style',
        plugins_url('/assets/css/animated-carousel.css', __FILE__),
        [],
        file_exists($ac_style) ? filemtime($ac_style) : false,
    );
}
add_action('wp_enqueue_scripts', 'elementor_avh_widgets_dependencies');
add_action('elementor/frontend/after_register_scripts', 'elementor_avh_widgets_dependencies', 20);
add_action('elementor/frontend/after_register_styles', 'elementor_avh_widgets_dependencies', 20);

/**
 * Enqueue carousel CSS in the editor preview iframe.
 */
function avh_enqueue_animated_carousel_editor_styles()
{
    if (wp_style_is('avh-animated-carousel-style', 'registered')) {
        wp_enqueue_style('avh-animated-carousel-style');
    }
}
add_action('elementor/editor/after_enqueue_styles', 'avh_enqueue_animated_carousel_editor_styles');
add_action('elementor/preview/enqueue_styles', 'avh_enqueue_animated_carousel_editor_styles');
