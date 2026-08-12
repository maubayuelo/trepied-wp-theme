<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Include theme options page (analytics IDs, legal contact info)
 */
require_once get_template_directory() . '/inc/options.php';

/**
 * Include Loi 25 consent banner
 */
require_once get_template_directory() . '/inc/consent/consent.php';

/**
 * Include GA4 (Consent Mode v2) + Meta Pixel gating
 */
require_once get_template_directory() . '/inc/consent/analytics.php';

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

	// Design tokens: sitewide (consent banner + legal pages both need them)
	wp_enqueue_style(
		'trepied-tokens',
		get_template_directory_uri() . '/assets/css/tokens.css',
		['trepied-theme-style'],
		$theme_version
	);

	// Legal page (privacy policy templates): page-specific styles only
	if (is_page_template('page-legal.php')) {
		$legal_css_path = get_template_directory() . '/assets/css/legal.css';
		wp_enqueue_style(
			'trepied-legal',
			get_template_directory_uri() . '/assets/css/legal.css',
			['trepied-tokens'],
			file_exists($legal_css_path) ? (string) filemtime($legal_css_path) : $theme_version
		);
	}

	// Tailwind CDN - load in footer. Not deferred: the inline config below
	// must run immediately after this script executes and defines `tailwind`.
	wp_register_script('trepied-tailwind', 'https://cdn.tailwindcss.com', [], null, true);
	wp_add_inline_script(
		'trepied-tailwind',
		"tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif'],condensed:['Barlow Condensed','sans-serif']},colors:{cream:'#f5f3ed','accent-red':'#ff0000'}}}};",
		'after'
	);
	wp_enqueue_script('trepied-tailwind');

	// Lucide icons - load in footer, defer
	wp_enqueue_script('trepied-lucide', 'https://unpkg.com/lucide@latest/dist/umd/lucide.min.js', [], null, true);

	// Reusable accessible dialog module (focus trap, Esc, backdrop, scroll lock)
	$modal_js_path = get_template_directory() . '/assets/js/modal.js';
	wp_enqueue_script(
		'trepied-modal',
		get_template_directory_uri() . '/assets/js/modal.js',
		[],
		file_exists($modal_js_path) ? (string) filemtime($modal_js_path) : $theme_version,
		true
	);

	// Main JS - load in footer with file-based versioning
	$main_js_path = get_template_directory() . '/assets/js/main.js';
	wp_enqueue_script(
		'trepied-main',
		get_template_directory_uri() . '/assets/js/main.js',
		['trepied-lucide', 'trepied-modal'],
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

	// DNS prefetch for YouTube
	echo '<link rel="dns-prefetch" href="https://www.youtube-nocookie.com">' . "\n";
	echo '<link rel="dns-prefetch" href="https://i.ytimg.com">' . "\n";

	// Robots meta: index all pages by default
	echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";
}
add_action('wp_head', 'trepied_add_resource_hints', 1);

function trepied_favicons(): void {
	$dir = get_template_directory_uri() . '/assets/images/favicons';
	echo '<link rel="icon" type="image/x-icon" href="' . esc_url($dir . '/favicon.ico') . '">' . "\n";
	echo '<link rel="icon" type="image/png" sizes="16x16" href="' . esc_url($dir . '/favicon-16x16.png') . '">' . "\n";
	echo '<link rel="icon" type="image/png" sizes="32x32" href="' . esc_url($dir . '/favicon-32x32.png') . '">' . "\n";
	echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url($dir . '/apple-touch-icon.png') . '">' . "\n";
	echo '<link rel="manifest" href="' . esc_url($dir . '/site.webmanifest') . '">' . "\n";
}
add_action('wp_head', 'trepied_favicons', 1);

/**
 * Front page SEO title, from the "FrontPage SEO" ACF group (per-language
 * via ACFML). Never hardcoded — falls back to the site title only if the
 * field is genuinely empty.
 */
function trepied_get_front_page_seo_title(): string {
	if (!function_exists('get_field')) {
		return get_bloginfo('name');
	}

	$front_page_id = trepied_get_front_page_id();
	$title = $front_page_id ? get_field('seo_title', $front_page_id) : '';

	return $title ? $title : get_bloginfo('name');
}

/**
 * Front page SEO description, same source/field group as the title.
 */
function trepied_get_front_page_seo_description(): string {
	if (!function_exists('get_field')) {
		return '';
	}

	$front_page_id = trepied_get_front_page_id();
	$description = $front_page_id ? get_field('seo_meta_description', $front_page_id) : '';

	return $description ? $description : '';
}

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
		$description = trepied_get_front_page_seo_description();
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
 * Override the <title> tag on the front page.
 * WP's title-tag support (add_theme_support('title-tag')) otherwise falls
 * back to just the site title ("trepied") with no SEO plugin active.
 */
function trepied_front_page_document_title(string $title): string {
	if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION')) {
		return $title;
	}

	if (!is_front_page()) {
		return $title;
	}

	return trepied_get_front_page_seo_title();
}
add_filter('pre_get_document_title', 'trepied_front_page_document_title', 20);

/**
 * Add defer attribute to non-critical scripts
 */
function trepied_defer_scripts(string $tag, string $handle, string $src): string {
	// Scripts that should be deferred
	$defer_scripts = ['trepied-lucide', 'trepied-modal', 'trepied-main', 'trepied-calendly'];
	
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

/**
 * Correct the <html lang="..."> attribute for the Quebec/Canada audience.
 *
 * WPML's configured locales here are en_US/fr_FR/es_ES (a WPML Languages
 * admin setting, not a theme concern) which language_attributes() turns
 * into lang="en-US"/"fr-FR"/"es-ES". This only overrides the rendered
 * attribute for SEO/accessibility — it does not touch WPML's own locale
 * config (date formats, etc.), which is a separate, riskier change.
 */
function trepied_fix_language_attributes(string $output): string {
	if (!function_exists('apply_filters')) {
		return $output;
	}

	$current_lang = apply_filters('wpml_current_language', null);
	$lang_map = [
		'en' => 'en-CA',
		'fr' => 'fr-CA',
		'es' => 'es',
	];

	if (!$current_lang || !isset($lang_map[$current_lang])) {
		return $output;
	}

	return preg_replace('/lang="[^"]*"/', 'lang="' . esc_attr($lang_map[$current_lang]) . '"', $output, 1);
}
add_filter('language_attributes', 'trepied_fix_language_attributes');

/**
 * Add canonical URL tag
 */
function trepied_add_canonical(): void {
	if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION')) {
		return;
	}

	$canonical = '';

	if (is_front_page()) {
		$canonical = home_url('/');
	} elseif (is_singular()) {
		$canonical = get_permalink();
	} elseif (is_category() || is_tag() || is_tax()) {
		$canonical = get_term_link(get_queried_object());
	}

	if (!empty($canonical) && !is_wp_error($canonical)) {
		echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
	}
}
add_action('wp_head', 'trepied_add_canonical', 1);

/**
 * Add Open Graph and Twitter Card meta tags
 */
function trepied_add_social_meta(): void {
	if (defined('WPSEO_VERSION') || defined('RANK_MATH_VERSION') || defined('AIOSEO_VERSION')) {
		return;
	}

	$title       = wp_get_document_title();
	$description = is_front_page() ? trepied_get_front_page_seo_description() : get_bloginfo('description');
	$url         = is_front_page() ? home_url('/') : (is_singular() ? get_permalink() : home_url('/'));
	// A static front page is also is_singular() — check is_front_page() first,
	// otherwise the homepage gets tagged as "article" instead of "website".
	$type        = is_front_page() ? 'website' : (is_singular() ? 'article' : 'website');
	$image        = '';
	$image_width  = 0;
	$image_height = 0;
	$site_name    = get_bloginfo('name');

	// Try to get a relevant image
	if (is_singular() && has_post_thumbnail()) {
		$thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
		if ($thumb) {
			$image        = $thumb[0];
			$image_width  = $thumb[1];
			$image_height = $thumb[2];
		}
	}

	// Fallback: ACF hero image. Not a real 1200x630 share asset — it is the
	// decorative brand symbol (596x792, portrait). Blocked on the client
	// providing a proper share image (see CLAUDE.md); in the meantime the
	// declared dimensions below are read from the actual file so they never
	// lie about what will be shown, even though the image itself is a poor
	// fit for a 1.91:1 social card.
	if (empty($image) && function_exists('get_field')) {
		$hero = get_field('hero', 'option');
		if (empty($hero)) {
			$hero = get_field('hero');
		}
		if (!empty($hero['image']['url'])) {
			$image        = $hero['image']['url'];
			$image_width  = (int) ($hero['image']['width'] ?? 0);
			$image_height = (int) ($hero['image']['height'] ?? 0);
		}
	}

	echo '<meta property="og:type" content="' . esc_attr($type) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr(substr(wp_strip_all_tags($description), 0, 160)) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
	echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";

	if (!empty($image)) {
		echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
		if ($image_width && $image_height) {
			echo '<meta property="og:image:width" content="' . esc_attr($image_width) . '">' . "\n";
			echo '<meta property="og:image:height" content="' . esc_attr($image_height) . '">' . "\n";
		}
	}

	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr(substr(wp_strip_all_tags($description), 0, 160)) . '">' . "\n";

	if (!empty($image)) {
		echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
	}
}
add_action('wp_head', 'trepied_add_social_meta', 5);

/**
 * Add JSON-LD structured data (Organization + LocalBusiness + VideoProductionService)
 */
function trepied_add_schema(): void {
	if (!is_front_page()) {
		return;
	}

	$site_name   = get_bloginfo('name');
	$site_url    = home_url('/');
	$logo_url    = get_template_directory_uri() . '/assets/images/symbol.png';
	$social_urls = function_exists('trepied_get_social_urls') ? trepied_get_social_urls() : [];

	$organization = [
		'@type'       => ['Organization', 'LocalBusiness', 'ProfessionalService'],
		'@id'         => $site_url . '#organization',
		'name'        => $site_name,
		'url'         => $site_url,
		'logo'        => [
			'@type' => 'ImageObject',
			'url'   => $logo_url,
		],
		'description' => trepied_get_front_page_seo_description(),
		'address'     => [
			'@type'            => 'PostalAddress',
			'addressLocality'  => 'Montreal',
			'addressRegion'    => 'QC',
			'addressCountry'   => 'CA',
		],
		'areaServed'  => ['Montreal', 'Quebec', 'Canada'],
		'serviceType' => ['Video Production', 'Corporate Video', 'Commercial Production', 'Documentary'],
		'knowsLanguage' => ['fr', 'en', 'es'],
	];

	// Omit sameAs entirely rather than emit an empty array when no real
	// social URL is configured — no placeholders in structured data.
	if (!empty($social_urls)) {
		$organization['sameAs'] = $social_urls;
	}

	$schema = [
		'@context' => 'https://schema.org',
		'@graph'   => [
			$organization,
			[
				'@type'           => 'WebSite',
				'@id'             => $site_url . '#website',
				'url'             => $site_url,
				'name'            => $site_name,
				'publisher'       => ['@id' => $site_url . '#organization'],
				'inLanguage'      => ['fr-CA', 'en-CA', 'es'],
			],
		],
	];

	echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
}
add_action('wp_head', 'trepied_add_schema', 5);

/**
 * Preload hero image for faster LCP
 * Outputs a <link rel="preload"> for the ACF hero image on front page
 */
function trepied_preload_hero_image(): void {
	if (!is_front_page()) {
		return;
	}

	if (!function_exists('get_field')) {
		return;
	}

	$hero = get_field('hero');

	// The <video> branch takes precedence in front-page.php's markup — if
	// a hero video is set, the ACF hero image never actually renders on
	// the page. Preloading it anyway wastes a high-priority download on
	// an asset the browser will never paint.
	if (!empty($hero['video'])) {
		return;
	}

	if (empty($hero['image']['id'])) {
		return;
	}

	$image_id  = $hero['image']['id'];
	$image_src = wp_get_attachment_image_src($image_id, 'trepied-hero');
	if (!$image_src) {
		return;
	}

	// Build srcset for responsive preload
	$srcset = wp_get_attachment_image_srcset($image_id, 'trepied-hero');

	echo '<link rel="preload" as="image" href="' . esc_url($image_src[0]) . '"';
	if ($srcset) {
		echo ' imagesrcset="' . esc_attr($srcset) . '"';
		echo ' imagesizes="(max-width: 768px) 100vw, (max-width: 1400px) 100vw, 1400px"';
	}
	echo '>' . "\n";
}
add_action('wp_head', 'trepied_preload_hero_image', 2);
