<?php
/**
 * Loi 25 consent banner: enqueue + shared PHP contract.
 *
 * The cookie contract (name, shape, versioning) is shared with a future
 * React headless port — do not rename `magneto_consent`, its JSON keys,
 * or the `magneto:consent` event without updating that port too.
 *
 * @package Trepied
 */

if (!defined('ABSPATH')) {
	exit;
}

define('TREPIED_CONSENT_COOKIE', 'magneto_consent');
define('TREPIED_CONSENT_VERSION', 1);

/**
 * Resolve the Privacy Policy URL for the current language.
 *
 * Minimal version for Fase 3 — looks up the English-default page by path.
 * Fase 5 replaces this with a WPML-aware resolver (fallback to default
 * language when the current-language translation doesn't exist).
 *
 * @return string
 */
function trepied_get_privacy_policy_url(): string
{
	$page = get_page_by_path('privacy-policy');

	if (!$page) {
		return '';
	}

	return (string) get_permalink($page);
}

/**
 * Enqueue the consent banner assets sitewide (not just the legal page).
 */
function trepied_enqueue_consent_assets(): void
{
	$theme_version = wp_get_theme()->get('Version');

	$consent_css_path = get_template_directory() . '/inc/consent/consent.css';
	wp_enqueue_style(
		'trepied-consent',
		get_template_directory_uri() . '/inc/consent/consent.css',
		['trepied-tokens'],
		file_exists($consent_css_path) ? (string) filemtime($consent_css_path) : $theme_version
	);

	$consent_js_path = get_template_directory() . '/inc/consent/consent.js';
	wp_enqueue_script(
		'trepied-consent',
		get_template_directory_uri() . '/inc/consent/consent.js',
		[],
		file_exists($consent_js_path) ? (string) filemtime($consent_js_path) : $theme_version,
		true
	);

	$privacy_url = trepied_get_privacy_policy_url();

	wp_localize_script('trepied-consent', 'magnetoConsentData', [
		'cookieName' => TREPIED_CONSENT_COOKIE,
		'version'    => TREPIED_CONSENT_VERSION,
		'privacyUrl' => $privacy_url,
		'strings'    => [
			'bannerMessageBefore' => __('We use cookies to improve your experience. You can accept, refuse, or customize your choices. Learn more in our ', 'trepied'),
			'bannerMessageLink'   => __('Privacy Policy', 'trepied'),
			'bannerMessageAfter'  => __('.', 'trepied'),
			'acceptAll'           => __('Accept All', 'trepied'),
			'rejectAll'           => __('Reject All', 'trepied'),
			'customize'           => __('Customize', 'trepied'),
			'panelTitle'          => __('Manage Cookie Preferences', 'trepied'),
			'panelIntro'          => __('Choose which categories of cookies you allow. You can change your mind at any time.', 'trepied'),
			'essentialLabel'      => __('Essential', 'trepied'),
			'essentialDesc'       => __('Always active — required for the site to function.', 'trepied'),
			'analyticsLabel'      => __('Analytics (Google Analytics 4)', 'trepied'),
			'analyticsDesc'       => __('Helps us understand how visitors use the site.', 'trepied'),
			'marketingLabel'      => __('Marketing (Meta Pixel)', 'trepied'),
			'marketingDesc'       => __('Used to measure the performance of our ads.', 'trepied'),
			'save'                => __('Save My Choices', 'trepied'),
			'close'               => __('Close', 'trepied'),
			'panelOpenAria'       => __('Manage cookie preferences', 'trepied'),
		],
	]);
}
add_action('wp_enqueue_scripts', 'trepied_enqueue_consent_assets');
