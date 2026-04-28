<?php
/*
Plugin Name:    Elementor AVH Widgets
Description:    WordPress plugin with extended widgets for Elementor
Author:         Adrián Vico Hernández <adrian.vico.95@gmail.com>
License:        GPL v2 or later
Text Domain:    elementor-avh-widgets
Requires:       Elementor, Elementor Pro
*/

/**
 * Swiper 11 CDN constants (used by AVH Coverflow Slider).
 *
 * Sourced from cdnjs to match the developer reference snippet that the
 * widget replicates. SRI hashes are validated when the asset is emitted via
 * the {script,style}_loader_tag filters below.
 */
define( 'AVH_SWIPER_CDN_VERSION', '11.0.5' );
define( 'AVH_SWIPER_CDN_CSS_URL', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.css' );
define( 'AVH_SWIPER_CDN_JS_URL', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js' );
define( 'AVH_SWIPER_CDN_CSS_SRI', 'sha512-pmAAV1X4Nh5jA9m+jcvwJXFQvCBi3T17aZ1KWkqXr7g/O2YMvO8rfaa5ETWDuBvRq6fbDjlw4jHL44jNTScaKg==' );
define( 'AVH_SWIPER_CDN_JS_SRI', 'sha512-Ysw1DcK1P+uYLqprEAzNQJP+J4hTx4t/3X2nbVwszao8wD+9afLjBQYjz7Uk4ADP+Er++mJoScI42ueGtQOzEA==' );

/**
 * Register Elementor widgets.
 */
function register_new_widgets($widgets_manager)
{
    require_once __DIR__ . '/widgets/expanded-content-button.php';
    $widgets_manager->register(new \Expanded_Content_Button());

    require_once __DIR__ . '/widgets/animated-carousel.php';
    $widgets_manager->register(new \AVH_Animated_Carousel());

    require_once __DIR__ . '/widgets/coverflow-slider.php';
    $widgets_manager->register(new \AVH_Coverflow_Slider());
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

    /* ── AVH Coverflow Slider: Swiper 11 CDN + local assets ── */

    wp_register_style(
        'avh-swiper-cdn-style',
        AVH_SWIPER_CDN_CSS_URL,
        [],
        AVH_SWIPER_CDN_VERSION,
    );

    wp_register_script(
        'avh-swiper-cdn-script',
        AVH_SWIPER_CDN_JS_URL,
        [],
        AVH_SWIPER_CDN_VERSION,
        true,
    );

    $cf_script = $base . '/assets/js/coverflow-slider.js';
    $cf_style = $base . '/assets/css/coverflow-slider.css';

    wp_register_style(
        'avh-coverflow-slider-style',
        plugins_url('/assets/css/coverflow-slider.css', __FILE__),
        ['avh-swiper-cdn-style'],
        file_exists($cf_style) ? filemtime($cf_style) : false,
    );

    wp_register_script(
        'avh-coverflow-slider-script',
        plugins_url('/assets/js/coverflow-slider.js', __FILE__),
        ['avh-swiper-cdn-script'],
        file_exists($cf_script) ? filemtime($cf_script) : false,
        true,
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
    if (wp_style_is('avh-coverflow-slider-style', 'registered')) {
        wp_enqueue_style('avh-coverflow-slider-style');
    }
}
add_action('elementor/editor/after_enqueue_styles', 'avh_enqueue_animated_carousel_editor_styles');
add_action('elementor/preview/enqueue_styles', 'avh_enqueue_animated_carousel_editor_styles');

/**
 * Inject Subresource Integrity (SRI) and crossorigin attributes onto the
 * Swiper 11 CDN <script> tag emitted by wp_register_script.
 */
function avh_swiper_cdn_script_attributes($tag, $handle)
{
    if ('avh-swiper-cdn-script' !== $handle) {
        return $tag;
    }

    $extra = sprintf(
        ' integrity="%s" crossorigin="anonymous" referrerpolicy="no-referrer"',
        esc_attr(AVH_SWIPER_CDN_JS_SRI),
    );

    return str_replace(' src=', $extra . ' src=', $tag);
}
add_filter('script_loader_tag', 'avh_swiper_cdn_script_attributes', 10, 2);

/**
 * Inject SRI and crossorigin attributes onto the Swiper 11 CDN <link> tag.
 */
function avh_swiper_cdn_style_attributes($tag, $handle)
{
    if ('avh-swiper-cdn-style' !== $handle) {
        return $tag;
    }

    $extra = sprintf(
        ' integrity="%s" crossorigin="anonymous" referrerpolicy="no-referrer"',
        esc_attr(AVH_SWIPER_CDN_CSS_SRI),
    );

    return str_replace(' href=', $extra . ' href=', $tag);
}
add_filter('style_loader_tag', 'avh_swiper_cdn_style_attributes', 10, 2);
