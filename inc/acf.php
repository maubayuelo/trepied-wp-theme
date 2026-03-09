<?php
/**
 * ACF Helper Layer for Trepied Theme
 *
 * Provides safe access to ACF fields from the Front Page.
 * All functions fail gracefully if ACF is not active.
 *
 * @package Trepied
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Check if ACF is active and available
 *
 * @return bool
 */
function trepied_is_acf_active(): bool
{
	return function_exists('get_field');
}

/**
 * Get the Front Page ID
 *
 * Returns the ID of the page set as "Front Page" in WordPress Settings > Reading.
 * Handles WPML and Polylang translation automatically.
 * Falls back to 0 if no front page is set.
 *
 * @return int The front page ID or 0
 */
function trepied_get_front_page_id(): int
{
	$front_page_id = (int) get_option('page_on_front', 0);

	// Handle WPML translation
	if ($front_page_id > 0 && function_exists('icl_object_id')) {
		$translated_id = icl_object_id($front_page_id, 'page', true);
		if ($translated_id) {
			$front_page_id = (int) $translated_id;
		}
	}
	// Handle Polylang translation
	elseif ($front_page_id > 0 && function_exists('pll_get_post')) {
		$translated_id = pll_get_post($front_page_id);
		if ($translated_id) {
			$front_page_id = (int) $translated_id;
		}
	}

	return $front_page_id;
}

/**
 * Get an ACF field value from the Front Page
 *
 * Safely retrieves a field value, returning the default if:
 * - ACF is not active
 * - The field doesn't exist
 * - The field value is empty
 *
 * @param string $field_name The ACF field name
 * @param mixed  $default    Default value if field is empty or unavailable
 * @return mixed The field value or default
 */
function trepied_get_field(string $field_name, $default = null)
{
	if (!trepied_is_acf_active()) {
		return $default;
	}

	$front_page_id = trepied_get_front_page_id();

	if ($front_page_id === 0) {
		return $default;
	}

	$value = get_field($field_name, $front_page_id);

	// Return default for empty values (null, empty string, empty array)
	if ($value === null || $value === '' || $value === []) {
		return $default;
	}

	return $value;
}

/**
 * Get an ACF group field from the Front Page
 *
 * Retrieves a group field and ensures it returns an array.
 * Useful for ACF Group field types.
 *
 * @param string $group_name The ACF group field name
 * @return array The group data or empty array
 */
function trepied_get_group(string $group_name): array
{
	$group = trepied_get_field($group_name, []);

	if (!is_array($group)) {
		return [];
	}

	return $group;
}

/**
 * Debug function: Dump available ACF field keys to error_log
 *
 * Only runs when WP_DEBUG is enabled.
 * Logs all ACF field groups and their field keys for the Front Page.
 *
 * @return void
 */
function trepied_dump_acf_keys(): void
{
	// Only run in debug mode
	if (!defined('WP_DEBUG') || !WP_DEBUG) {
		return;
	}

	if (!trepied_is_acf_active()) {
		error_log('[Trepied ACF Debug] ACF is not active.');
		return;
	}

	$front_page_id = trepied_get_front_page_id();

	if ($front_page_id === 0) {
		error_log('[Trepied ACF Debug] No front page set in WordPress Settings > Reading.');
		return;
	}

	error_log('[Trepied ACF Debug] Front Page ID: ' . $front_page_id);

	// Get all ACF field groups
	if (!function_exists('acf_get_field_groups')) {
		error_log('[Trepied ACF Debug] acf_get_field_groups() not available.');
		return;
	}

	$field_groups = acf_get_field_groups(['post_id' => $front_page_id]);

	if (empty($field_groups)) {
		error_log('[Trepied ACF Debug] No ACF field groups found for Front Page.');
		return;
	}

	error_log('[Trepied ACF Debug] Found ' . count($field_groups) . ' field group(s):');

	foreach ($field_groups as $group) {
		error_log('[Trepied ACF Debug] --- Group: ' . $group['title'] . ' (key: ' . $group['key'] . ')');

		if (function_exists('acf_get_fields')) {
			$fields = acf_get_fields($group['key']);

			if (!empty($fields)) {
				foreach ($fields as $field) {
					$value = get_field($field['name'], $front_page_id);
					$value_type = gettype($value);
					$value_preview = is_array($value) ? 'Array(' . count($value) . ')' : (is_string($value) ? substr($value, 0, 50) : $value);

					error_log(sprintf(
						'[Trepied ACF Debug]     - %s (key: %s, type: %s) = [%s] %s',
						$field['name'],
						$field['key'],
						$field['type'],
						$value_type,
						$value_preview
					));
				}
			}
		}
	}

	error_log('[Trepied ACF Debug] --- End of ACF dump ---');
}
