<?php
/**
 * Theme Options Page (Loi 25 / analytics config)
 *
 * Stores IDs and legal contact info that must never be hardcoded in
 * templates or JS. If a field is empty, the corresponding script must
 * never load — see trepied_get_option() callers.
 *
 * @package Trepied
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Register the "Trépied — Config" options page (ACF Pro).
 */
function trepied_register_options_page(): void
{
	if (!function_exists('acf_add_options_page')) {
		return;
	}

	acf_add_options_page([
		'page_title' => 'Trépied — Config',
		'menu_title' => 'Trépied — Config',
		'menu_slug'  => 'trepied-config',
		'capability' => 'manage_options',
		'icon_url'   => 'dashicons-admin-generic',
		'position'   => 80,
		'redirect'   => false,
	]);
}
add_action('acf/init', 'trepied_register_options_page');

/**
 * Register the option fields (local field group, DB-independent).
 */
function trepied_register_options_fields(): void
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key'      => 'group_trepied_options',
		'title'    => 'Analytics & Legal',
		'fields'   => [
			[
				'key'           => 'field_trepied_ga4_id',
				'label'         => 'GA4 Measurement ID',
				'name'          => 'ga4_measurement_id',
				'type'          => 'text',
				'instructions'  => 'Format: G-XXXXXXXXXX. Leave empty to disable GA4 entirely.',
				'default_value' => 'G-VBB1BK5XTR',
				'placeholder'   => 'G-XXXXXXXXXX',
			],
			[
				'key'          => 'field_trepied_meta_pixel_id',
				'label'        => 'Meta Pixel ID',
				'name'         => 'meta_pixel_id',
				'type'         => 'text',
				'instructions' => 'Numeric Pixel ID. Leave empty to disable Meta Pixel entirely (no ID yet — pending client ad spend decision).',
				'placeholder'  => '1234567890123456',
			],
			[
				'key'           => 'field_trepied_privacy_email',
				'label'         => 'Privacy Contact Email',
				'name'          => 'privacy_contact_email',
				'type'          => 'email',
				'instructions'  => 'Used for data-access/deletion requests (Loi 25).',
				'default_value' => 'info@trepied.ca',
			],
			[
				'key'           => 'field_trepied_legal_entity',
				'label'         => 'Legal Entity Name',
				'name'          => 'legal_entity_name',
				'type'          => 'text',
				'instructions'  => 'Responsible entity shown in the privacy policy / consent panel.',
				'default_value' => 'Trépied',
			],
			[
				'key'          => 'field_trepied_linkedin_url',
				'label'        => 'LinkedIn URL',
				'name'         => 'linkedin_url',
				'type'         => 'url',
				'instructions' => 'Single source of truth for the footer social icon and the JSON-LD sameAs — never hardcode this elsewhere.',
				'default_value' => 'https://www.linkedin.com/in/israel-valencia-833aa341/',
			],
			[
				'key'          => 'field_trepied_instagram_url',
				'label'        => 'Instagram URL',
				'name'         => 'instagram_url',
				'type'         => 'url',
				'instructions' => 'Leave empty to hide the icon entirely — no placeholder/generic URL. Same source used by the footer and JSON-LD sameAs.',
			],
			[
				'key'          => 'field_trepied_youtube_url',
				'label'        => 'YouTube URL',
				'name'         => 'youtube_url',
				'type'         => 'url',
				'instructions' => 'Leave empty to hide the icon entirely — no placeholder/generic URL. Same source used by the footer and JSON-LD sameAs.',
			],
		],
		'location' => [
			[
				[
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'trepied-config',
				],
			],
		],
	]);
}
add_action('acf/init', 'trepied_register_options_fields');

/**
 * Safe reader for theme option fields.
 *
 * Always returns a trimmed string. Never echoes raw HTML — callers must
 * esc_attr()/esc_html() at the point of output.
 *
 * @param string $key Field name registered above.
 * @return string Field value, or '' if ACF/option is unavailable.
 */
function trepied_get_option(string $key): string
{
	if (!function_exists('get_field')) {
		return '';
	}

	$value = get_field($key, 'option');

	if ($value === null || $value === '') {
		// Under WPML, ACFML can return null for an options-page field in a
		// non-default language when no per-language copy was ever saved —
		// even though these are organization-level values (LinkedIn URL,
		// GA4 ID, legal contact info...) meant to be identical in every
		// language. Fall back to the raw value ACF actually persists an
		// options-page field under, bypassing WPML's language filtering.
		$value = get_option('options_' . $key);
	}

	return is_string($value) ? trim($value) : '';
}

/**
 * The site's real social profile URLs — single source of truth for both
 * the footer icons and the JSON-LD sameAs array, so the two can never
 * drift out of sync. Only non-empty URLs are included; no placeholders.
 *
 * @return string[]
 */
function trepied_get_social_urls(): array
{
	$urls = array_map('trepied_get_option', ['linkedin_url', 'instagram_url', 'youtube_url']);

	return array_values(array_filter($urls));
}

/**
 * Is this request running on the production domain?
 *
 * The production DB gets imported to local for development, bringing the
 * live GA4/Pixel IDs with it — so "an ID is set" can never be the signal
 * that analytics should fire. This is the actual gate. Host is derived
 * from home_url(), never $_SERVER['HTTP_HOST'] (attacker-controllable,
 * and unset entirely under WP-CLI).
 *
 * TREPIED_DISABLE_ANALYTICS wins over TREPIED_FORCE_ANALYTICS if both are
 * somehow defined true — disabling should never be silently overridden.
 *
 * @return bool
 */
function trepied_is_production(): bool
{
	if (defined('TREPIED_DISABLE_ANALYTICS') && TREPIED_DISABLE_ANALYTICS) {
		return false;
	}

	if (defined('TREPIED_FORCE_ANALYTICS') && TREPIED_FORCE_ANALYTICS) {
		return true;
	}

	$host = wp_parse_url(home_url(), PHP_URL_HOST);

	if (!$host) {
		return false;
	}

	$host = strtolower((string) $host);
	$host = preg_replace('/^www\./', '', $host);

	return $host === 'trepied.ca';
}

/**
 * Surface the analytics guard's state on the "Trépied — Config" screen —
 * emptying the GA4 field by hand after every DB import has already been
 * missed once; this makes the (safe) suppressed state visible instead of
 * silent. Informational only — fields stay editable, values stay saved.
 */
function trepied_analytics_guard_notice(): void
{
	if (trepied_is_production()) {
		return;
	}

	$screen = get_current_screen();
	if (!$screen || strpos($screen->id, 'trepied-config') === false) {
		return;
	}

	$host = (string) wp_parse_url(home_url(), PHP_URL_HOST);

	printf(
		'<div class="notice notice-info"><p>%s</p></div>',
		esc_html(
			sprintf(
				/* translators: %s: current site host */
				__('Analytics are disabled on this environment (%s). IDs are saved but not emitted.', 'trepied'),
				$host
			)
		)
	);
}
add_action('admin_notices', 'trepied_analytics_guard_notice');
