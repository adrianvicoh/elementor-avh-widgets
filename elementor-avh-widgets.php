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
    require_once __DIR__ . "/widgets/widget-1.php";

    $widgets_manager->register(new \Elementor_Widget_1());
}
add_action("elementor/widgets/register", "register_new_widgets");

/**
 * Register categories for Elementor test widgets.
 */
function add_elementor_widget_categories($elements_manager)
{
    $elements_manager->add_category("AVH Widgets", [
        "title" => esc_html__("elementor-avh-widgets", "textdomain"),
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
    /* Scripts */
    wp_register_script(
        "widget-script-1",
        plugins_url("assets/js/widget-script-1.js", __FILE__),
    );

    /* Styles */
    wp_register_style(
        "widget-style-1",
        plugins_url("assets/css/widget-style-1.css", __FILE__),
    );
}
add_action("wp_enqueue_scripts", "elementor_test_widgets_dependencies");
