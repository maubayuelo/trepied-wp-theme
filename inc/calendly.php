<?php
/**
 * Calendly Integration for Trepied Theme
 * 
 * Performance optimized: Calendly assets load on-demand when user clicks trigger
 *
 * @package Trepied
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Default Calendly URL - can be overridden in wp-config.php
 * Define TREPIED_CALENDLY_URL in wp-config.php to customize
 */
if (!defined('TREPIED_CALENDLY_URL')) {
	define('TREPIED_CALENDLY_URL', 'https://calendly.com/maubayuelo/30min');
}

/**
 * DO NOT enqueue Calendly assets on page load
 * Assets are loaded on-demand when user clicks a trigger button
 * This improves initial page load performance significantly
 */

/**
 * Build Calendly URL with UTM parameters from current page
 *
 * @param string $base_url Optional custom Calendly URL, defaults to TREPIED_CALENDLY_URL
 * @return string Calendly URL with UTM parameters appended
 */
function trepied_get_calendly_url(string $base_url = ''): string {
	if (empty($base_url)) {
		$base_url = TREPIED_CALENDLY_URL;
	}

	// Collect UTM params from current URL
	$utm_params = [];
	$utm_keys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];

	foreach ($utm_keys as $key) {
		if (isset($_GET[$key]) && !empty($_GET[$key])) {
			$utm_params[$key] = sanitize_text_field($_GET[$key]);
		}
	}

	// If no UTM params in URL, don't modify
	if (empty($utm_params)) {
		return esc_url($base_url);
	}

	// Parse existing URL and merge params
	$parsed = wp_parse_url($base_url);
	$existing_params = [];
	
	if (!empty($parsed['query'])) {
		parse_str($parsed['query'], $existing_params);
	}

	$merged_params = array_merge($existing_params, $utm_params);
	
	$new_url = $parsed['scheme'] . '://' . $parsed['host'];
	if (!empty($parsed['path'])) {
		$new_url .= $parsed['path'];
	}
	$new_url .= '?' . http_build_query($merged_params);

	return esc_url($new_url);
}

/**
 * Output Calendly popup button
 *
 * @param string $text Button text
 * @param string $class Additional CSS classes
 * @param string $url Optional custom Calendly URL
 */
function trepied_calendly_button(string $text, string $class = '', string $url = ''): void {
	$calendly_url = trepied_get_calendly_url($url);
	$default_class = 'calendly-popup-trigger inline-block px-7 py-3 bg-black text-white text-[15px] hover:bg-accent-red transition-all duration-300 rounded-full cursor-pointer';
	$final_class = $class ? $default_class . ' ' . esc_attr($class) : $default_class;
	
	printf(
		'<button type="button" class="%s" data-calendly-url="%s">%s</button>',
		esc_attr($final_class),
		esc_attr($calendly_url),
		esc_html($text)
	);
}

/**
 * Enqueue the Calendly click-to-load script.
 * Assets (assets.calendly.com CSS/JS) only load when a user clicks a
 * .calendly-popup-trigger button — never preloaded. A preload (even an
 * idle-time one) would set Calendly cookies before consent, which is the
 * same Loi 25 gap already closed for GA4 and Meta Pixel.
 */
function trepied_enqueue_calendly_assets(): void {
	if (is_admin()) {
		return;
	}

	$theme_version = wp_get_theme()->get('Version');
	$js_path = get_template_directory() . '/inc/calendly.js';

	wp_enqueue_script(
		'trepied-calendly',
		get_template_directory_uri() . '/inc/calendly.js',
		[],
		file_exists($js_path) ? (string) filemtime($js_path) : $theme_version,
		true
	);

	wp_localize_script('trepied-calendly', 'trepiedCalendlyData', [
		'url' => trepied_get_calendly_url(),
	]);
}
add_action('wp_enqueue_scripts', 'trepied_enqueue_calendly_assets');
