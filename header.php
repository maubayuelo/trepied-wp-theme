<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class('min-h-screen bg-cream font-sans antialiased text-[#1a1a1a]'); ?>>
<?php wp_body_open(); ?>

<nav class="fixed top-0 left-0 right-0 z-50 bg-cream/90 backdrop-blur-md">
	<div class="max-w-[1400px] mx-auto px-8 md:px-16 lg:px-24 h-20 flex items-center justify-between">
		<div class="text-[15px] font-black tracking-[0.02em] uppercase">
			Trépied
		</div>

		<div class="hidden md:flex items-center gap-12 text-[15px]">
			<a href="#services" class="hover:opacity-60 transition-opacity"><?php echo esc_html__('Services', 'trepied'); ?></a>
			<a href="#projects" class="hover:opacity-60 transition-opacity"><?php echo esc_html__('Work', 'trepied'); ?></a>
			<a href="#about" class="hover:opacity-60 transition-opacity"><?php echo esc_html__('About', 'trepied'); ?></a>
			<div class="flex items-center gap-8">
				<?php if (trepied_is_multilingual_active()) : ?>
				<div class="flex items-center gap-3">
					<?php echo trepied_language_switcher('/'); ?>
				</div>
				<?php endif; ?>
				<?php trepied_calendly_button(
					esc_html__('Schedule a Call', 'trepied'),
					'!px-6 !py-2.5 !text-[14px] font-medium'
				); ?>
				<button type="button" class="quote-modal-trigger px-6 py-2.5 border-2 border-black text-black text-[14px] font-medium hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full cursor-pointer">
					<?php echo esc_html__('Request a Quote', 'trepied'); ?>
				</button>
			</div>
		</div>

		<div class="md:hidden flex items-center gap-3">
			<?php trepied_calendly_button(
				esc_html__('Schedule a Call', 'trepied'),
				'!px-5 !py-2 !text-[13px] font-medium whitespace-nowrap'
			); ?>
			<button id="mobile-menu-toggle" class="p-2.5 bg-black text-white hover:bg-accent-red transition-all duration-300 rounded-full">
				<i data-lucide="menu" class="w-5 h-5"></i>
			</button>
		</div>
	</div>
</nav>

<div id="mobile-menu" class="fixed inset-0 z-40 transform translate-x-full transition-transform duration-300 ease-in-out md:hidden" style="background-color: #f5f3ed;">
	<div class="flex flex-col h-full pt-24 px-8 pb-12">
		<nav class="flex-1 flex flex-col gap-8 text-[18px]">
			<a href="#services" class="mobile-menu-link hover:opacity-60 transition-opacity py-2"><?php echo esc_html__('Services', 'trepied'); ?></a>
			<a href="#projects" class="mobile-menu-link hover:opacity-60 transition-opacity py-2"><?php echo esc_html__('Work', 'trepied'); ?></a>
			<a href="#about" class="mobile-menu-link hover:opacity-60 transition-opacity py-2"><?php echo esc_html__('About', 'trepied'); ?></a>
			<a href="#contact" class="mobile-menu-link hover:opacity-60 transition-opacity py-2"><?php echo esc_html__('Contact', 'trepied'); ?></a>
		</nav>

		<div class="space-y-6 border-t border-[#b0b0b0] pt-8">
			<?php if (trepied_is_multilingual_active()) : ?>
			<div class="flex items-center gap-4 text-[16px]">
				<?php echo trepied_language_switcher('/'); ?>
			</div>
			<?php endif; ?>
			<?php trepied_calendly_button(
				esc_html__('Schedule a Call', 'trepied'),
				'mobile-menu-link block w-full text-center'
			); ?>
			<button type="button" class="quote-modal-trigger mobile-menu-link block w-full text-center px-7 py-3 border-2 border-black text-black text-[15px] hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full cursor-pointer">
				<?php echo esc_html__('Request a Quote', 'trepied'); ?>
			</button>
		</div>
	</div>
</div>
