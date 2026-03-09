<?php
/**
 * ACF Local Field Group Registration for Trepied Theme
 *
 * DISABLED: Field groups are now managed via ACF admin UI in the database.
 * Keeping this file for reference only. The database field groups are:
 * - FrontPage Hero Section (group_69a5f37e52db6)
 * - FrontPage Services Section (group_69a77c72a2028)
 * - FrontPage Projects Section (group_69a77f37aa666)
 * - FrontPage About Section (group_69a781358063e)
 * - FrontPage Testimonials Section (group_69a783c4508d5)
 * - FrontPage CTA Section (group_69a791b7076fe)
 *
 * @package Trepied
 */

if (!defined('ABSPATH')) {
	exit;
}

// DISABLED - Using database field groups instead
// add_action('acf/init', 'trepied_register_acf_fields');

/**
 * Register ACF field groups
 */
function trepied_register_acf_fields(): void
{
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group([
		'key' => 'group_69a06dda27f0d',
		'title' => 'Trepied Content',
		'fields' => [
			// =========================================
			// HERO SECTION
			// =========================================
			[
				'key' => 'field_trepied_hero',
				'label' => 'Hero',
				'name' => 'hero',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'field_trepied_hero_copy',
						'label' => 'Copy',
						'name' => 'copy',
						'type' => 'text',
					],
					[
						'key' => 'field_trepied_hero_subtitle',
						'label' => 'Subtitle',
						'name' => 'subtitle',
						'type' => 'text',
					],
					[
						'key' => 'field_trepied_hero_video',
						'label' => 'Video',
						'name' => 'video',
						'type' => 'text',
						'instructions' => 'YT Video ID',
					],
					[
						'key' => 'field_trepied_hero_image',
						'label' => 'Image',
						'name' => 'image',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
					],
				],
			],

			// =========================================
			// SERVICES SECTION
			// =========================================
			[
				'key' => 'field_trepied_services',
				'label' => 'Services',
				'name' => 'services',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'field_trepied_services_title',
						'label' => 'Section Title',
						'name' => 'section_title',
						'type' => 'text',
					],
					[
						'key' => 'field_trepied_services_description',
						'label' => 'Section Description',
						'name' => 'section_description',
						'type' => 'textarea',
					],
					[
						'key' => 'field_trepied_services_repeater',
						'label' => 'Services',
						'name' => 'service',
						'type' => 'repeater',
						'layout' => 'block',
						'button_label' => 'Add Service',
						'sub_fields' => [
							[
								'key' => 'field_trepied_service_title',
								'label' => 'Service Title',
								'name' => 'service_title',
								'type' => 'text',
							],
							[
								'key' => 'field_trepied_service_description',
								'label' => 'Service Description',
								'name' => 'service_description',
								'type' => 'wysiwyg',
								'tabs' => 'all',
								'toolbar' => 'full',
								'media_upload' => 0,
							],
							[
								'key' => 'field_trepied_service_image',
								'label' => 'Service Image',
								'name' => 'service_image',
								'type' => 'image',
								'return_format' => 'array',
								'preview_size' => 'medium',
							],
							[
								'key' => 'field_trepied_service_link',
								'label' => 'Service Link',
								'name' => 'service_link',
								'type' => 'text',
							],
						],
					],
				],
			],

			// =========================================
			// PROJECTS SECTION
			// =========================================
			[
				'key' => 'field_trepied_projects',
				'label' => 'Projects',
				'name' => 'projects',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'field_trepied_projects_title',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
					],
					[
						'key' => 'field_trepied_projects_description',
						'label' => 'Description',
						'name' => 'description',
						'type' => 'textarea',
					],
					[
						'key' => 'field_trepied_projects_repeater',
						'label' => 'Projects',
						'name' => 'project',
						'type' => 'repeater',
						'layout' => 'block',
						'button_label' => 'Add Project',
						'sub_fields' => [
							[
								'key' => 'field_trepied_project_title',
								'label' => 'Title',
								'name' => 'title',
								'type' => 'text',
							],
							[
								'key' => 'field_trepied_project_image',
								'label' => 'Image',
								'name' => 'image',
								'type' => 'image',
								'return_format' => 'array',
								'preview_size' => 'medium',
							],
							[
								'key' => 'field_trepied_project_client',
								'label' => 'Client',
								'name' => 'client',
								'type' => 'text',
							],
							[
								'key' => 'field_trepied_project_description',
								'label' => 'Description',
								'name' => 'description',
								'type' => 'textarea',
							],
							[
								'key' => 'field_trepied_project_long_description',
								'label' => 'Long Description',
								'name' => 'long_description',
								'type' => 'wysiwyg',
								'tabs' => 'all',
								'toolbar' => 'full',
								'media_upload' => 1,
							],
						],
					],
				],
			],

			// =========================================
			// TESTIMONIALS SECTION
			// =========================================
			[
				'key' => 'field_trepied_testimonials',
				'label' => 'Testimonials',
				'name' => 'testimonials',
				'type' => 'repeater',
				'layout' => 'block',
				'button_label' => 'Add Testimonial',
				'sub_fields' => [
					[
						'key' => 'field_trepied_testimonial_group',
						'label' => 'Testimonial',
						'name' => 'testimonial',
						'type' => 'group',
						'layout' => 'block',
						'sub_fields' => [
							[
								'key' => 'field_trepied_testimonial_name',
								'label' => 'Name',
								'name' => 'name',
								'type' => 'text',
							],
							[
								'key' => 'field_trepied_testimonial_role',
								'label' => 'Role',
								'name' => 'role',
								'type' => 'text',
							],
							[
								'key' => 'field_trepied_testimonial_quote',
								'label' => 'Quote',
								'name' => 'quote',
								'type' => 'textarea',
							],
						],
					],
				],
			],

			// =========================================
			// ABOUT SECTION
			// =========================================
			[
				'key' => 'field_trepied_about',
				'label' => 'About',
				'name' => 'about',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'field_trepied_about_title',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
					],
					[
						'key' => 'field_trepied_about_subtitle',
						'label' => 'Subtitle',
						'name' => 'subtitle',
						'type' => 'text',
					],
					[
						'key' => 'field_trepied_about_paragraph',
						'label' => 'Paragraph',
						'name' => 'paragraph',
						'type' => 'wysiwyg',
						'tabs' => 'all',
						'toolbar' => 'full',
						'media_upload' => 0,
					],
					[
						'key' => 'field_trepied_about_image',
						'label' => 'Image',
						'name' => 'image',
						'type' => 'image',
						'return_format' => 'array',
						'preview_size' => 'medium',
					],
				],
			],

			// =========================================
			// CTA SECTION
			// =========================================
			[
				'key' => 'field_trepied_cta_section',
				'label' => 'CTA Section',
				'name' => 'cta_section',
				'type' => 'group',
				'layout' => 'block',
				'sub_fields' => [
					[
						'key' => 'field_trepied_cta_title',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
					],
					[
						'key' => 'field_trepied_cta_paragraph',
						'label' => 'Paragraph',
						'name' => 'paragraph',
						'type' => 'textarea',
					],
				],
			],
		],
		'location' => [
			[
				[
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'front-page.php',
				],
			],
			[
				[
					'param' => 'page_type',
					'operator' => '==',
					'value' => 'front_page',
				],
			],
		],
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'show_in_rest' => false,
	]);
}
