<?php
/**
 * Quote Request Form Handler for Trepied Theme
 *
 * @package Trepied
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Configuration constants - define in wp-config.php to override
 */
if (!defined('TREPIED_QUOTE_ADMIN_EMAIL')) {
	define('TREPIED_QUOTE_ADMIN_EMAIL', get_option('admin_email'));
}

if (!defined('TREPIED_QUOTE_SEND_USER_CONFIRMATION')) {
	define('TREPIED_QUOTE_SEND_USER_CONFIRMATION', true);
}

if (!defined('TREPIED_QUOTE_EMAIL_FROM_NAME')) {
	define('TREPIED_QUOTE_EMAIL_FROM_NAME', get_bloginfo('name'));
}

/**
 * SMTP Configuration - Required for sending real emails from LocalWP
 * Define these constants in wp-config.php to enable SMTP
 * 
 * Example for Gmail SMTP:
 * define('TREPIED_SMTP_HOST', 'smtp.gmail.com');
 * define('TREPIED_SMTP_PORT', 587);
 * define('TREPIED_SMTP_USER', 'your-email@gmail.com');
 * define('TREPIED_SMTP_PASS', 'your-app-password');
 * define('TREPIED_SMTP_FROM', 'your-email@gmail.com');
 * define('TREPIED_SMTP_FROM_NAME', 'Trépied');
 */

/**
 * Configure WordPress to use SMTP if constants are defined
 */
function trepied_configure_smtp($phpmailer): void {
	if (!defined('TREPIED_SMTP_HOST') || !defined('TREPIED_SMTP_USER') || !defined('TREPIED_SMTP_PASS')) {
		return;
	}

	$phpmailer->isSMTP();
	$phpmailer->Host       = TREPIED_SMTP_HOST;
	$phpmailer->SMTPAuth   = true;
	$phpmailer->Port       = defined('TREPIED_SMTP_PORT') ? TREPIED_SMTP_PORT : 587;
	$phpmailer->Username   = TREPIED_SMTP_USER;
	$phpmailer->Password   = TREPIED_SMTP_PASS;
	$phpmailer->SMTPSecure = defined('TREPIED_SMTP_SECURE') ? TREPIED_SMTP_SECURE : 'tls';
	
	if (defined('TREPIED_SMTP_FROM')) {
		$phpmailer->From = TREPIED_SMTP_FROM;
	}
	if (defined('TREPIED_SMTP_FROM_NAME')) {
		$phpmailer->FromName = TREPIED_SMTP_FROM_NAME;
	}
}
add_action('phpmailer_init', 'trepied_configure_smtp');

/**
 * Log email errors for debugging
 */
function trepied_log_mail_errors($wp_error): void {
	if (defined('WP_DEBUG') && WP_DEBUG) {
		error_log('WordPress Mail Error: ' . $wp_error->get_error_message());
	}
}
add_action('wp_mail_failed', 'trepied_log_mail_errors');

/**
 * Register Quote Request custom post type
 */
function trepied_register_quote_request_cpt(): void {
	$args = [
		'labels'              => [
			'name'               => __('Quote Requests', 'trepied'),
			'singular_name'      => __('Quote Request', 'trepied'),
			'menu_name'          => __('Quote Requests', 'trepied'),
			'all_items'          => __('All Requests', 'trepied'),
			'view_item'          => __('View Request', 'trepied'),
			'search_items'       => __('Search Requests', 'trepied'),
			'not_found'          => __('No requests found', 'trepied'),
			'not_found_in_trash' => __('No requests found in trash', 'trepied'),
		],
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-email-alt',
		'capability_type'     => 'post',
		'hierarchical'        => false,
		'supports'            => ['title', 'custom-fields'],
		'has_archive'         => false,
		'rewrite'             => false,
		'query_var'           => false,
		'show_in_rest'        => false,
	];

	register_post_type('quote_request', $args);
}
add_action('init', 'trepied_register_quote_request_cpt');

/**
 * Add custom columns to Quote Requests admin list
 */
function trepied_quote_request_columns(array $columns): array {
	$new_columns = [];
	
	foreach ($columns as $key => $value) {
		if ($key === 'title') {
			$new_columns[$key] = __('Name', 'trepied');
			$new_columns['email'] = __('Email', 'trepied');
			$new_columns['company'] = __('Company', 'trepied');
			$new_columns['budget'] = __('Budget', 'trepied');
			$new_columns['timeline'] = __('Timeline', 'trepied');
		} else {
			$new_columns[$key] = $value;
		}
	}
	
	return $new_columns;
}
add_filter('manage_quote_request_posts_columns', 'trepied_quote_request_columns');

/**
 * Populate custom columns in Quote Requests admin list
 */
function trepied_quote_request_column_content(string $column, int $post_id): void {
	switch ($column) {
		case 'email':
			echo esc_html(get_post_meta($post_id, '_quote_email', true));
			break;
		case 'company':
			$company = get_post_meta($post_id, '_quote_company', true);
			echo $company ? esc_html($company) : '—';
			break;
		case 'budget':
			echo esc_html(trepied_get_budget_label(get_post_meta($post_id, '_quote_budget_range', true)));
			break;
		case 'timeline':
			echo esc_html(trepied_get_timeline_label(get_post_meta($post_id, '_quote_timeline', true)));
			break;
	}
}
add_action('manage_quote_request_posts_custom_column', 'trepied_quote_request_column_content', 10, 2);

/**
 * Get human-readable budget label
 */
function trepied_get_budget_label(string $value): string {
	$labels = [
		'5k-10k'   => '$5,000 - $10,000',
		'10k-25k'  => '$10,000 - $25,000',
		'25k-50k'  => '$25,000 - $50,000',
		'50k+'     => '$50,000+',
	];
	
	return $labels[$value] ?? $value;
}

/**
 * Get human-readable timeline label
 */
function trepied_get_timeline_label(string $value): string {
	$labels = [
		'asap'        => __('ASAP', 'trepied'),
		'1-2-months'  => __('1-2 months', 'trepied'),
		'3-6-months'  => __('3-6 months', 'trepied'),
		'flexible'    => __('Flexible', 'trepied'),
	];
	
	return $labels[$value] ?? $value;
}

/**
 * Enqueue form scripts and localize AJAX data
 */
function trepied_enqueue_form_scripts(): void {
	if (is_admin()) {
		return;
	}

	wp_localize_script('trepied-main', 'trepiedForms', [
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'nonce'   => wp_create_nonce('trepied_quote_request'),
		'i18n'    => [
			'sending'         => __('Sending...', 'trepied'),
			'success'         => __('Thank you! Your request has been submitted. We\'ll be in touch soon.', 'trepied'),
			'error'           => __('Something went wrong. Please try again or contact us directly.', 'trepied'),
			'validationError' => __('Please fill in all required fields correctly.', 'trepied'),
		],
	]);
}
add_action('wp_enqueue_scripts', 'trepied_enqueue_form_scripts', 20);

/**
 * Handle AJAX quote request submission
 */
function trepied_handle_quote_request(): void {
	// Verify nonce
	if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'trepied_quote_request')) {
		wp_send_json_error([
			'message' => __('Security check failed. Please refresh the page and try again.', 'trepied'),
		], 403);
	}

	// Check honeypot
	if (!empty($_POST['website_url'])) {
		// Silently fail for bots - return fake success
		wp_send_json_success([
			'message' => __('Thank you! Your request has been submitted.', 'trepied'),
		]);
	}

	// Validate and sanitize inputs
	$validation = trepied_validate_quote_form($_POST);
	
	if (!$validation['valid']) {
		wp_send_json_error([
			'message' => $validation['message'],
			'errors'  => $validation['errors'],
		], 400);
	}

	$data = $validation['data'];

	// Create the quote request post
	$post_id = wp_insert_post([
		'post_type'   => 'quote_request',
		'post_title'  => $data['name'],
		'post_status' => 'publish',
		'meta_input'  => [
			'_quote_email'               => $data['email'],
			'_quote_company'             => $data['company'],
			'_quote_budget_range'        => $data['budget_range'],
			'_quote_timeline'            => $data['timeline'],
			'_quote_project_description' => $data['project_description'],
			'_quote_gdpr_consent'        => $data['gdpr_consent'],
			'_quote_ip_address'          => trepied_get_client_ip(),
			'_quote_user_agent'          => sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? ''),
			'_quote_page_url'            => esc_url_raw($_POST['page_url'] ?? ''),
		],
	]);

	if (is_wp_error($post_id)) {
		wp_send_json_error([
			'message' => __('Failed to save your request. Please try again.', 'trepied'),
		], 500);
	}

	// Send admin notification email
	$admin_email_sent = trepied_send_admin_notification($data, $post_id);

	// Send user confirmation email if enabled
	$user_email_sent = false;
	if (TREPIED_QUOTE_SEND_USER_CONFIRMATION) {
		$user_email_sent = trepied_send_user_confirmation($data);
	}

	// Log email status
	update_post_meta($post_id, '_quote_admin_email_sent', $admin_email_sent ? 'yes' : 'no');
	update_post_meta($post_id, '_quote_user_email_sent', $user_email_sent ? 'yes' : 'no');

	wp_send_json_success([
		'message' => __('Thank you! Your request has been submitted. We\'ll be in touch within 24-48 hours.', 'trepied'),
	]);
}
add_action('wp_ajax_trepied_quote_request', 'trepied_handle_quote_request');
add_action('wp_ajax_nopriv_trepied_quote_request', 'trepied_handle_quote_request');

/**
 * Validate quote form data
 */
function trepied_validate_quote_form(array $input): array {
	$errors = [];
	$data = [];

	// Name - required
	$name = isset($input['name']) ? sanitize_text_field(trim($input['name'])) : '';
	if (empty($name)) {
		$errors['name'] = __('Name is required.', 'trepied');
	} elseif (strlen($name) < 2) {
		$errors['name'] = __('Please enter a valid name.', 'trepied');
	}
	$data['name'] = $name;

	// Email - required and valid
	$email = isset($input['email']) ? sanitize_email(trim($input['email'])) : '';
	if (empty($email)) {
		$errors['email'] = __('Email is required.', 'trepied');
	} elseif (!is_email($email)) {
		$errors['email'] = __('Please enter a valid email address.', 'trepied');
	}
	$data['email'] = $email;

	// Company - optional
	$data['company'] = isset($input['company']) ? sanitize_text_field(trim($input['company'])) : '';

	// Budget range - required, must be valid option
	$valid_budgets = ['5k-10k', '10k-25k', '25k-50k', '50k+'];
	$budget = isset($input['budget_range']) ? sanitize_text_field($input['budget_range']) : '';
	if (empty($budget)) {
		$errors['budget_range'] = __('Please select a budget range.', 'trepied');
	} elseif (!in_array($budget, $valid_budgets, true)) {
		$errors['budget_range'] = __('Invalid budget selection.', 'trepied');
	}
	$data['budget_range'] = $budget;

	// Timeline - required, must be valid option
	$valid_timelines = ['asap', '1-2-months', '3-6-months', 'flexible'];
	$timeline = isset($input['timeline']) ? sanitize_text_field($input['timeline']) : '';
	if (empty($timeline)) {
		$errors['timeline'] = __('Please select a timeline.', 'trepied');
	} elseif (!in_array($timeline, $valid_timelines, true)) {
		$errors['timeline'] = __('Invalid timeline selection.', 'trepied');
	}
	$data['timeline'] = $timeline;

	// Project description - required
	$description = isset($input['project_description']) ? sanitize_textarea_field(trim($input['project_description'])) : '';
	if (empty($description)) {
		$errors['project_description'] = __('Please describe your project.', 'trepied');
	} elseif (strlen($description) < 20) {
		$errors['project_description'] = __('Please provide more details about your project (at least 20 characters).', 'trepied');
	}
	$data['project_description'] = $description;

	// GDPR consent - required
	$gdpr = isset($input['gdpr_consent']) && ($input['gdpr_consent'] === 'on' || $input['gdpr_consent'] === '1' || $input['gdpr_consent'] === 'true');
	if (!$gdpr) {
		$errors['gdpr_consent'] = __('You must agree to the privacy policy.', 'trepied');
	}
	$data['gdpr_consent'] = $gdpr ? 'yes' : 'no';

	if (!empty($errors)) {
		return [
			'valid'   => false,
			'message' => __('Please correct the errors below.', 'trepied'),
			'errors'  => $errors,
			'data'    => $data,
		];
	}

	return [
		'valid' => true,
		'data'  => $data,
	];
}

/**
 * Send notification email to admin
 */
function trepied_send_admin_notification(array $data, int $post_id): bool {
	$to = TREPIED_QUOTE_ADMIN_EMAIL;
	$subject = sprintf(
		/* translators: %s: client name */
		__('[New Quote Request] %s', 'trepied'),
		$data['name']
	);

	$edit_link = admin_url('post.php?post=' . $post_id . '&action=edit');

	$message = sprintf(
		/* translators: Quote request notification email body */
		__('New quote request received:

Name: %1$s
Email: %2$s
Company: %3$s
Budget Range: %4$s
Timeline: %5$s

Project Description:
%6$s

---
View in WordPress: %7$s', 'trepied'),
		$data['name'],
		$data['email'],
		$data['company'] ?: '—',
		trepied_get_budget_label($data['budget_range']),
		trepied_get_timeline_label($data['timeline']),
		$data['project_description'],
		$edit_link
	);

	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		'From: ' . TREPIED_QUOTE_EMAIL_FROM_NAME . ' <' . get_option('admin_email') . '>',
		'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>',
	];

	return wp_mail($to, $subject, $message, $headers);
}

/**
 * Send confirmation email to user
 */
function trepied_send_user_confirmation(array $data): bool {
	$to = $data['email'];
	$subject = sprintf(
		/* translators: %s: site name */
		__('We received your quote request - %s', 'trepied'),
		get_bloginfo('name')
	);

	$message = sprintf(
		/* translators: User confirmation email body */
		__('Hi %1$s,

Thank you for your interest! We\'ve received your quote request and will review it shortly.

Here\'s a summary of what you submitted:

Budget Range: %2$s
Timeline: %3$s

Project Description:
%4$s

We\'ll get back to you within 24-48 business hours.

Best regards,
%5$s', 'trepied'),
		$data['name'],
		trepied_get_budget_label($data['budget_range']),
		trepied_get_timeline_label($data['timeline']),
		$data['project_description'],
		get_bloginfo('name')
	);

	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		'From: ' . TREPIED_QUOTE_EMAIL_FROM_NAME . ' <' . get_option('admin_email') . '>',
	];

	return wp_mail($to, $subject, $message, $headers);
}

/**
 * Get client IP address safely
 */
function trepied_get_client_ip(): string {
	$ip_keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
	
	foreach ($ip_keys as $key) {
		if (!empty($_SERVER[$key])) {
			$ip = $_SERVER[$key];
			// Handle comma-separated IPs (X-Forwarded-For)
			if (strpos($ip, ',') !== false) {
				$ip = trim(explode(',', $ip)[0]);
			}
			if (filter_var($ip, FILTER_VALIDATE_IP)) {
				return sanitize_text_field($ip);
			}
		}
	}
	
	return '';
}

/**
 * Add meta box to display quote details in admin
 */
function trepied_quote_request_meta_box(): void {
	add_meta_box(
		'quote_request_details',
		__('Quote Request Details', 'trepied'),
		'trepied_render_quote_meta_box',
		'quote_request',
		'normal',
		'high'
	);
}
add_action('add_meta_boxes', 'trepied_quote_request_meta_box');

/**
 * Render quote request meta box
 */
function trepied_render_quote_meta_box(WP_Post $post): void {
	$meta = [
		'email'               => get_post_meta($post->ID, '_quote_email', true),
		'company'             => get_post_meta($post->ID, '_quote_company', true),
		'budget_range'        => get_post_meta($post->ID, '_quote_budget_range', true),
		'timeline'            => get_post_meta($post->ID, '_quote_timeline', true),
		'project_description' => get_post_meta($post->ID, '_quote_project_description', true),
		'gdpr_consent'        => get_post_meta($post->ID, '_quote_gdpr_consent', true),
		'ip_address'          => get_post_meta($post->ID, '_quote_ip_address', true),
		'page_url'            => get_post_meta($post->ID, '_quote_page_url', true),
		'admin_email_sent'    => get_post_meta($post->ID, '_quote_admin_email_sent', true),
		'user_email_sent'     => get_post_meta($post->ID, '_quote_user_email_sent', true),
	];
	?>
	<table class="form-table">
		<tr>
			<th><?php esc_html_e('Email', 'trepied'); ?></th>
			<td><a href="mailto:<?php echo esc_attr($meta['email']); ?>"><?php echo esc_html($meta['email']); ?></a></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Company', 'trepied'); ?></th>
			<td><?php echo $meta['company'] ? esc_html($meta['company']) : '—'; ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Budget Range', 'trepied'); ?></th>
			<td><?php echo esc_html(trepied_get_budget_label($meta['budget_range'])); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Timeline', 'trepied'); ?></th>
			<td><?php echo esc_html(trepied_get_timeline_label($meta['timeline'])); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Project Description', 'trepied'); ?></th>
			<td><div style="white-space: pre-wrap; background: #f6f7f7; padding: 12px; border-radius: 4px;"><?php echo esc_html($meta['project_description']); ?></div></td>
		</tr>
		<tr>
			<th><?php esc_html_e('GDPR Consent', 'trepied'); ?></th>
			<td><?php echo $meta['gdpr_consent'] === 'yes' ? '✓ ' . esc_html__('Agreed', 'trepied') : '✗ ' . esc_html__('Not agreed', 'trepied'); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e('IP Address', 'trepied'); ?></th>
			<td><code><?php echo esc_html($meta['ip_address'] ?: '—'); ?></code></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Submitted From', 'trepied'); ?></th>
			<td><?php echo $meta['page_url'] ? '<a href="' . esc_url($meta['page_url']) . '" target="_blank">' . esc_html($meta['page_url']) . '</a>' : '—'; ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e('Email Status', 'trepied'); ?></th>
			<td>
				<?php 
				echo esc_html__('Admin notification:', 'trepied') . ' ';
				echo $meta['admin_email_sent'] === 'yes' ? '✓' : '✗';
				echo ' | ';
				echo esc_html__('User confirmation:', 'trepied') . ' ';
				echo $meta['user_email_sent'] === 'yes' ? '✓' : '✗';
				?>
			</td>
		</tr>
	</table>
	<?php
}
