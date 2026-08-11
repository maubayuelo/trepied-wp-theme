<?php
/**
 * GA4 (Consent Mode v2) + Meta Pixel — gated by Loi 25 consent.
 *
 * No ID configured in the "Trépied — Config" options page → that script
 * is never enqueued, with or without consent (see trepied_get_option()).
 *
 * @package Trepied
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * GA4: gtag.js loads on every page view, but with all consent signals
 * defaulted to "denied" until the user grants consent (Consent Mode v2).
 * The default itself is what keeps _ga/_gid/_ga_* from being set —
 * without it Google's library would set them before consent exists.
 */
function trepied_enqueue_ga4(string $ga4_id): void
{
	wp_register_script(
		'trepied-gtag',
		'https://www.googletagmanager.com/gtag/js?id=' . rawurlencode($ga4_id),
		[],
		null,
		true
	);
	wp_script_add_data('trepied-gtag', 'async', true);

	$bootstrap = sprintf(
		"window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}"
		. "gtag('consent','default',{'analytics_storage':'denied','ad_storage':'denied','ad_user_data':'denied','ad_personalization':'denied','wait_for_update':500});"
		. "gtag('js',new Date());gtag('config','%s');",
		esc_js($ga4_id)
	);

	wp_add_inline_script('trepied-gtag', $bootstrap, 'before');
	wp_enqueue_script('trepied-gtag');
}

/**
 * Gating listener (analytics.js): updates GA4 consent + injects Meta
 * Pixel in response to the `magneto:consent` event — never reads the
 * cookie directly (single source of truth: consent.js).
 */
function trepied_enqueue_consent_gating(): void
{
	$ga4_id   = trepied_get_option('ga4_measurement_id');
	$pixel_id = trepied_get_option('meta_pixel_id');

	if ($ga4_id !== '') {
		trepied_enqueue_ga4($ga4_id);
	}

	if ($ga4_id === '' && $pixel_id === '') {
		return;
	}

	$theme_version = wp_get_theme()->get('Version');
	$js_path       = get_template_directory() . '/inc/consent/analytics.js';

	wp_enqueue_script(
		'trepied-consent-gating',
		get_template_directory_uri() . '/inc/consent/analytics.js',
		['trepied-consent'],
		file_exists($js_path) ? (string) filemtime($js_path) : $theme_version,
		true
	);

	wp_localize_script('trepied-consent-gating', 'magnetoGatingData', [
		'pixelId' => $pixel_id,
	]);
}
add_action('wp_enqueue_scripts', 'trepied_enqueue_consent_gating');
