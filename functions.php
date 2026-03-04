<?php
if (!defined('ABSPATH')) {
	exit;
}

function trepied_theme_setup(): void
{
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
}
add_action('after_setup_theme', 'trepied_theme_setup');

function trepied_enqueue_assets(): void
{
	$theme_version = wp_get_theme()->get('Version');

	wp_enqueue_style(
		'trepied-google-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;900&family=Inter:wght@400;500;600;700;900&display=swap',
		[],
		null
	);

	wp_enqueue_style(
		'trepied-theme-style',
		get_stylesheet_uri(),
		[],
		$theme_version
	);

	$custom_css_path = get_template_directory() . '/assets/css/styles.css';
	wp_enqueue_style(
		'trepied-styles',
		get_template_directory_uri() . '/assets/css/styles.css',
		['trepied-theme-style'],
		file_exists($custom_css_path) ? (string) filemtime($custom_css_path) : $theme_version
	);

	wp_register_script('trepied-tailwind', 'https://cdn.tailwindcss.com', [], null, false);
	wp_add_inline_script(
		'trepied-tailwind',
		"tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif'],condensed:['Barlow Condensed','sans-serif']},colors:{cream:'#f5f3ed','accent-red':'#ff0000'}}}};",
		'before'
	);
	wp_enqueue_script('trepied-tailwind');

	wp_enqueue_script('trepied-lucide', 'https://unpkg.com/lucide@latest', [], null, true);

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
 * Get language switcher HTML for use in templates
 * Uses Polylang if available, falls back to static links
 *
 * @param string $separator The separator between language links
 * @return string HTML output
 */
function trepied_language_switcher(string $separator = ' / '): string
{
	$output = '';

	if (function_exists('pll_the_languages')) {
		$languages = pll_the_languages([
			'raw'              => 1,
			'hide_if_empty'    => 0,
			'show_flags'       => 0,
			'show_names'       => 1,
			'display_names_as' => 'slug',
		]);

		if (!empty($languages)) {
			$links = [];
			foreach ($languages as $lang) {
				$class = $lang['current_lang'] ? 'font-medium' : '';
				$links[] = sprintf(
					'<a href="%s" class="%s hover:opacity-60 transition-opacity" lang="%s" hreflang="%s">%s</a>',
					esc_url($lang['url']),
					esc_attr($class),
					esc_attr($lang['slug']),
					esc_attr($lang['slug']),
					esc_html(strtoupper($lang['slug']))
				);
			}
			$separator_html = '<span class="text-[#d0d0d0]">' . esc_html($separator) . '</span>';
			$output = implode($separator_html, $links);
		}
	}

	// Fallback to static links if Polylang not active or no languages
	if (empty($output)) {
		$output = '<a href="#" class="font-medium hover:opacity-60 transition-opacity">FR</a>';
		$output .= '<span class="text-[#d0d0d0]">/</span>';
		$output .= '<a href="#" class="hover:opacity-60 transition-opacity">EN</a>';
		$output .= '<span class="text-[#d0d0d0]">/</span>';
		$output .= '<a href="#" class="hover:opacity-60 transition-opacity">ES</a>';
	}

	return $output;
}
