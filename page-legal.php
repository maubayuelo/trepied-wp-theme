<?php
/**
 * Template Name: Legal Page
 *
 * Used for the trilingual privacy policy pages. No sidebar, 720px reading
 * width, styles come exclusively from assets/css/tokens.css custom
 * properties (see CLAUDE.md).
 *
 * @package Trepied
 */

if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>

<main class="legal">
	<?php while (have_posts()) : the_post(); ?>
		<h1 class="legal__title"><?php the_title(); ?></h1>
		<p class="legal__updated">
			<?php
			printf(
				/* translators: %s: last modified date */
				esc_html__('Last updated: %s', 'trepied'),
				esc_html(get_the_modified_date())
			);
			?>
		</p>

		<div class="legal__content">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>
</main>

<?php
get_footer();
