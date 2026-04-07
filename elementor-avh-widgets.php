<?php
/*
Plugin Name:    Elementor AVH Widgets
Description:    WordPress plugin with extended widgets for Elementor
Version:        0.0.1
Author:         Adrián Vico Hernández <adrian.vico.95@gmail.com>
License:        GPL v2 or later
Text Domain:    elementor-avh-widgets
*/

/**
 * Register Elementor test widgets.
 */
function register_new_widgets($widgets_manager)
{
    require_once __DIR__ . "/widgets/expanded-content-button.php";

    $widgets_manager->register(new \Expanded_Content_Button());
}
add_action("elementor/widgets/register", "register_new_widgets");

/**
 * Register categories for Elementor test widgets.
 */
function add_elementor_widget_categories($elements_manager)
{
    $elements_manager->add_category("elementor-avh-widgets", [
        "title" => esc_html__("AVH Widgets", "textdomain"),
        "icon" => "fa fa-plug",
    ]);
}
add_action(
    "elementor/elements/categories_registered",
    "add_elementor_widget_categories",
);

/**
 * Register scripts and styles for Elementor test widgets.
 */
function elementor_test_widgets_dependencies()
{
    wp_register_script(
        "expanded-content-button",
        plugins_url("assets/js/expanded-content-button.js", __FILE__),
    );

    wp_register_style(
        "expanded-content-button",
        plugins_url("assets/css/expanded-content-button.css", __FILE__),
    );
}
add_action("wp_enqueue_scripts", "elementor_test_widgets_dependencies");
