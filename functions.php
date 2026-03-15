<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Include ACF helper functions
 */
require_once get_template_directory() . '/inc/acf.php';

/**
 * Include ACF field group registration
 * (File is protected internally - only registers if ACF is active)
 */
require_once get_template_directory() . '/inc/acf-fields.php';

/**
 * Include Calendly integration
 */
require_once get_template_directory() . '/inc/calendly.php';

/**
 * Include Quote Request form handler
 */
require_once get_template_directory() . '/inc/forms.php';

function trepied_theme_setup(): void
{
	// Load text domain for translations
	load_theme_textdomain('trepied', get_template_directory() . '/languages');
	
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
	
	// Custom image sizes for optimized loading
	add_image_size('trepied-service', 1080, 720, true);  // Service section images
	add_image_size('trepied-hero', 1400, 800, true);     // Hero section images
}
add_action('after_setup_theme', 'trepied_theme_setup');

function trepied_enqueue_assets(): void
{
	$theme_version = wp_get_theme()->get('Version');

	// Google Fonts - with display=swap for better performance
	wp_enqueue_style(
		'trepied-google-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;900&family=Inter:wght@400;500;600;700;900&display=swap',
		[],
		null
	);

	// Main theme stylesheet
	wp_enqueue_style(
		'trepied-theme-style',
		get_stylesheet_uri(),
		[],
		$theme_version
	);

	// Custom styles with file-based versioning for cache busting
	$custom_css_path = get_template_directory() . '/assets/css/styles.css';
	wp_enqueue_style(
		'trepied-styles',
		get_template_directory_uri() . '/assets/css/styles.css',
		['trepied-theme-style'],
		file_exists($custom_css_path) ? (string) filemtime($custom_css_path) : $theme_version
	);

	// Tailwind CDN - load in head (required for styling)
	wp_register_script('trepied-tailwind', 'https://cdn.tailwindcss.com', [], null, false);
	wp_add_inline_script(
		'trepied-tailwind',
		"tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif'],condensed:['Barlow Condensed','sans-serif']},colors:{cream:'#f5f3ed','accent-red':'#ff0000'}}}};",
		'before'
	);
	wp_enqueue_script('trepied-tailwind');

	// Lucide icons - load in footer, defer
	wp_enqueue_script('trepied-lucide', 'https://unpkg.com/lucide@latest/dist/umd/lucide.min.js', [], null, true);

	// Main JS - load in footer with file-based versioning
	$main_js_path = get_template_directory() . '/assets/js/main.js';
	wp_enqueue_script(
		'trepied-main',
		get_template_directory_uri() . '/assets/js/main.js',
		['trepied-lucide'],
		file_exists($main_js_path) ? (string) filemtime($main_js_path) : $theme_version,
		true
	);

	wp_localize_script('trepied-main', 'trepiedData', [
		'templateUri' => get_template_directory_uri(),
	]);
}
add_action('wp_enqueue_scripts', 'trepied_enqueue_assets');

/**
 * Add preconnect hints for external resources
 * Improves load time by establishing early connections
 */
function trepied_add_resource_hints(): void {
	// Preconnect to Google Fonts (critical)
	echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	
	// Preconnect to CDNs used by the theme
	echo '<link rel="preconnect" href="https://unpkg.com" crossorigin>' . "\n";
	echo '<link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>' . "\n";
	
	// DNS prefetch for Calendly (loaded on interaction)
	echo '<link rel="dns-prefetch" href="https://assets.calendly.com">' . "\n";
	
	// DNS prefetch for YouTube (if videos are used)
	echo '<link rel="dns-prefetch" href="https://www.youtube-nocookie.com">' . "\n";
	echo '<link rel="dns-prefetch" href="https://i.ytimg.com">' . "\n";
}
add_action('wp_head', 'trepied_add_resource_hints', 1);

/**
 * Add meta description for SEO
 * Only outputs if no SEO plugin (Yoast, RankMath, etc.) is active
 */
function trepied_add_meta_description(): void {
	// Skip if SEO plugin is handling meta descriptions
	if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION')) {
		return;
	}

	$description = '';

	if (is_front_page() || is_home()) {
		$description = get_bloginfo('description');
		if (empty($description)) {
			$description = __('Trépied - Creative studio specializing in web development, branding, and digital experiences.', 'trepied');
		}
	} elseif (is_singular()) {
		$post = get_queried_object();
		if ($post && !empty($post->post_excerpt)) {
			$description = $post->post_excerpt;
		} elseif ($post && !empty($post->post_content)) {
			$description = wp_trim_words(strip_shortcodes($post->post_content), 25, '...');
		}
	} elseif (is_category() || is_tag() || is_tax()) {
		$description = term_description();
	}

	if (!empty($description)) {
		$description = wp_strip_all_tags($description);
		$description = esc_attr(substr($description, 0, 160));
		echo '<meta name="description" content="' . $description . '">' . "\n";
	}
}
add_action('wp_head', 'trepied_add_meta_description', 1);

/**
 * Add defer attribute to non-critical scripts
 */
function trepied_defer_scripts(string $tag, string $handle, string $src): string {
	// Scripts that should be deferred
	$defer_scripts = ['trepied-lucide', 'trepied-main'];
	
	if (in_array($handle, $defer_scripts, true)) {
		return str_replace(' src', ' defer src', $tag);
	}
	
	return $tag;
}
add_filter('script_loader_tag', 'trepied_defer_scripts', 10, 3);

/**
 * Add fetchpriority hint for critical CSS
 */
function trepied_optimize_stylesheets(string $tag, string $handle, string $href, string $media): string {
	// Google Fonts should load with high priority
	if ($handle === 'trepied-google-fonts') {
		return str_replace("rel='stylesheet'", "rel='stylesheet' fetchpriority='high'", $tag);
	}
	
	return $tag;
}
add_filter('style_loader_tag', 'trepied_optimize_stylesheets', 10, 4);

/**
 * Disable Polylang's default language switcher in footer
 */
function trepied_disable_polylang_footer_switcher(): void
{
	if (function_exists('pll_the_languages')) {
		remove_action('wp_footer', 'pll_language_switcher');
	}

	add_filter('pll_the_languages_raw', function ($output, $args) {
		if (doing_action('wp_footer')) {
			return [];
		}
		return $output;
	}, 10, 2);
}
add_action('init', 'trepied_disable_polylang_footer_switcher');

add_action('wp_footer', function () {
	echo '<style>.pll-switcher-footer, #pll_switcher, .widget_polylang { display: none !important; }</style>';
}, 1);

/**
 * Disable WPML's default language switcher
 * The theme's nav already has custom language switchers
 */
function trepied_disable_wpml_language_switcher(): void
{
	// Disable WPML's floating language switcher
	add_filter('wpml_ls_enable', '__return_false');
	
	// Remove WPML language switcher from wp_nav_menu
	add_filter('wp_nav_menu_items', function($items, $args) {
		// Remove any WPML-inserted language switcher from menus
		$items = preg_replace('/<li[^>]*class="[^"]*wpml-ls-item[^"]*"[^>]*>.*?<\/li>/is', '', $items);
		return $items;
	}, 100, 2);
}
add_action('init', 'trepied_disable_wpml_language_switcher');

// Hide any remaining WPML language switcher elements via CSS
add_action('wp_footer', function () {
	echo '<style>.wpml-ls-statics-footer, .wpml-ls-statics-post_translations, .wpml-ls-legacy-dropdown, .wpml-ls-legacy-list-horizontal, .wpml-ls-legacy-list-vertical, .wpml-ls-sidebars-wpml-ls-widget, #lang_sel, #lang_sel_click, #lang_sel_list, .icl_lang_sel_widget, .widget_icl_lang_sel_widget { display: none !important; }</style>';
}, 1);

/**
 * Get language switcher HTML for use in templates
 * Supports WPML, Polylang, or hides gracefully if neither is active
 *
 * @param string $separator The separator between language links
 * @return string HTML output (empty if no multilingual plugin active)
 */
function trepied_language_switcher(string $separator = ' / '): string
{
	$output = '';
	$links = [];
	$separator_html = '<span class="text-[#d0d0d0]">' . esc_html($separator) . '</span>';

	// Try WPML first
	if (function_exists('icl_get_languages')) {
		$languages = icl_get_languages('skip_missing=0&orderby=code');
		
		if (!empty($languages)) {
			foreach ($languages as $lang) {
				if ($lang['active']) {
					// Active language: render as span (not clickable)
					$links[] = sprintf(
						'<span class="font-medium underline underline-offset-4" lang="%s">%s</span>',
						esc_attr($lang['language_code']),
						esc_html(strtoupper($lang['language_code']))
					);
				} else {
					// Other languages: render as link
					$links[] = sprintf(
						'<a href="%s" class="hover:opacity-60 transition-opacity" lang="%s" hreflang="%s">%s</a>',
						esc_url($lang['url']),
						esc_attr($lang['language_code']),
						esc_attr($lang['language_code']),
						esc_html(strtoupper($lang['language_code']))
					);
				}
			}
			$output = implode($separator_html, $links);
		}
	}
	// Fallback to Polylang
	elseif (function_exists('pll_the_languages')) {
		$languages = pll_the_languages([
			'raw'              => 1,
			'hide_if_empty'    => 0,
			'show_flags'       => 0,
			'show_names'       => 1,
			'display_names_as' => 'slug',
		]);

		if (!empty($languages)) {
			foreach ($languages as $lang) {
				if ($lang['current_lang']) {
					// Active language: render as span (not clickable)
					$links[] = sprintf(
						'<span class="font-medium underline underline-offset-4" lang="%s">%s</span>',
						esc_attr($lang['slug']),
						esc_html(strtoupper($lang['slug']))
					);
				} else {
					// Other languages: render as link
					$links[] = sprintf(
						'<a href="%s" class="hover:opacity-60 transition-opacity" lang="%s" hreflang="%s">%s</a>',
						esc_url($lang['url']),
						esc_attr($lang['slug']),
						esc_attr($lang['slug']),
						esc_html(strtoupper($lang['slug']))
					);
				}
			}
			$output = implode($separator_html, $links);
		}
	}

	// No fallback - hide gracefully if no multilingual plugin is active
	return $output;
}

/**
 * Check if any multilingual plugin is active
 */
function trepied_is_multilingual_active(): bool {
	return function_exists('icl_get_languages') || function_exists('pll_the_languages');
}
