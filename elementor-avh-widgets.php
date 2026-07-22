<?php
/*
Plugin Name:    Elementor Custom Widgets
Description:    WordPress plugin with extended widgets for Elementor
Author:         Adrián Vico Hernández <adrian.vico.95@gmail.com>
License:        GPL v2 or later
Text Domain:    custom-elementor-widgets
Requires:       Elementor, Elementor Pro
*/

// Swiper 11 CDN constants used by the Coverflow Slider.
if ( ! defined( 'SWIPER_CDN_VERSION' ) ) {
	define( 'SWIPER_CDN_VERSION', '11.0.5' );
}
if ( ! defined( 'SWIPER_CDN_CSS_URL' ) ) {
	define( 'SWIPER_CDN_CSS_URL', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.css' );
}
if ( ! defined( 'SWIPER_CDN_JS_URL' ) ) {
	define( 'SWIPER_CDN_JS_URL', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js' );
}
if ( ! defined( 'SWIPER_CDN_CSS_SRI' ) ) {
	define( 'SWIPER_CDN_CSS_SRI', 'sha512-pmAAV1X4Nh5jA9m+jcvwJXFQvCBi3T17aZ1KWkqXr7g/O2YMvO8rfaa5ETWDuBvRq6fbDjlw4jHL44jNTScaKg==' );
}
if ( ! defined( 'SWIPER_CDN_JS_SRI' ) ) {
	define( 'SWIPER_CDN_JS_SRI', 'sha512-Ysw1DcK1P+uYLqprEAzNQJP+J4hTx4t/3X2nbVwszao8wD+9afLjBQYjz7Uk4ADP+Er++mJoScI42ueGtQOzEA==' );
}

/**
 * Register Elementor widgets.
 */
function register_new_widgets($widgets_manager)
{
	require_once __DIR__ . '/widgets/coverflow-slider.php';
	require_once __DIR__ . '/widgets/toggle-content.php';

	foreach ([ 'Coverflow_Slider', 'Toggle_Content' ] as $widget_class) {
		if (class_exists($widget_class)) {
			$widgets_manager->register(new $widget_class());
		}
	}
}
add_action('elementor/widgets/register', 'register_new_widgets');

/**
 * Register categories for Elementor widgets.
 */
function add_elementor_widget_categories($elements_manager)
{
	$elements_manager->add_category('custom-elementor-widgets', [
		'title' => esc_html__('Custom Elementor Widgets', 'custom-elementor-widgets'),
		'icon' => 'fa fa-plug',
	]);
}
add_action(
	'elementor/elements/categories_registered',
	'add_elementor_widget_categories'
);

/**
 * Register scripts and styles for Elementor widgets.
 */
function elementor_widgets_dependencies()
{
	$base = __DIR__;

	/* ── Coverflow Slider: Swiper 11 CDN + local assets ── */

	wp_register_style(
		'coverflow-slider-swiper-style',
		SWIPER_CDN_CSS_URL,
		[],
		SWIPER_CDN_VERSION
	);

	wp_register_script(
		'coverflow-slider-swiper-script',
		SWIPER_CDN_JS_URL,
		[],
		SWIPER_CDN_VERSION,
		true
	);

	$cf_script = $base . '/assets/js/coverflow-slider.js';
	$cf_style = $base . '/assets/css/coverflow-slider.css';
	$toggle_script = $base . '/assets/js/toggle-content.js';
	$toggle_style = $base . '/assets/css/toggle-content.css';

	wp_register_style(
		'coverflow-slider-widget-style',
		plugins_url('/assets/css/coverflow-slider.css', __FILE__),
		['coverflow-slider-swiper-style'],
		file_exists($cf_style) ? filemtime($cf_style) : false
	);

	wp_register_script(
		'coverflow-slider-widget-script',
		plugins_url('/assets/js/coverflow-slider.js', __FILE__),
		['coverflow-slider-swiper-script'],
		file_exists($cf_script) ? filemtime($cf_script) : false,
		true
	);

	wp_register_style(
		'toggle-content-widget-style',
		plugins_url('/assets/css/toggle-content.css', __FILE__),
		[],
		file_exists($toggle_style) ? filemtime($toggle_style) : false
	);

	wp_register_script(
		'toggle-content-widget-script',
		plugins_url('/assets/js/toggle-content.js', __FILE__),
		[],
		file_exists($toggle_script) ? filemtime($toggle_script) : false,
		true
	);
}
add_action('wp_enqueue_scripts', 'elementor_widgets_dependencies');
add_action('elementor/frontend/after_register_scripts', 'elementor_widgets_dependencies', 20);
add_action('elementor/frontend/after_register_styles', 'elementor_widgets_dependencies', 20);

/**
 * Enqueue widget CSS in the editor preview iframe.
 */
function enqueue_editor_widget_styles()
{
	if (wp_style_is('coverflow-slider-widget-style', 'registered')) {
		wp_enqueue_style('coverflow-slider-widget-style');
	}
	if (wp_style_is('toggle-content-widget-style', 'registered')) {
		wp_enqueue_style('toggle-content-widget-style');
	}
}
add_action('elementor/editor/after_enqueue_styles', 'enqueue_editor_widget_styles');
add_action('elementor/preview/enqueue_styles', 'enqueue_editor_widget_styles');

/**
 * Inject Subresource Integrity (SRI) and crossorigin attributes onto the
 * Swiper 11 CDN <script> tag emitted by wp_register_script.
 */
function swiper_cdn_script_attributes($tag, $handle)
{
	if ('coverflow-slider-swiper-script' !== $handle) {
		return $tag;
	}

	$extra = sprintf(
		' integrity="%s" crossorigin="anonymous" referrerpolicy="no-referrer"',
		esc_attr(SWIPER_CDN_JS_SRI)
	);

	return str_replace(' src=', $extra . ' src=', $tag);
}
add_filter('script_loader_tag', 'swiper_cdn_script_attributes', 10, 2);

/**
 * Inject SRI and crossorigin attributes onto the Swiper 11 CDN <link> tag.
 */
function swiper_cdn_style_attributes($tag, $handle)
{
	if ('coverflow-slider-swiper-style' !== $handle) {
		return $tag;
	}

	$extra = sprintf(
		' integrity="%s" crossorigin="anonymous" referrerpolicy="no-referrer"',
		esc_attr(SWIPER_CDN_CSS_SRI)
	);

	return str_replace(' href=', $extra . ' href=', $tag);
}
add_filter('style_loader_tag', 'swiper_cdn_style_attributes', 10, 2);
