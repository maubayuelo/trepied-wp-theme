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
			<a href="#services" class="hover:opacity-60 transition-opacity">Services</a>
			<a href="#realisations" class="hover:opacity-60 transition-opacity">Réalisations</a>
			<a href="#apropos" class="hover:opacity-60 transition-opacity">À propos</a>
			<div class="flex items-center gap-8">
				<div class="flex items-center gap-3">
					<?php echo trepied_language_switcher('/'); ?>
				</div>
				<a href="#contact" class="px-6 py-2.5 bg-black text-white text-[14px] font-medium hover:bg-accent-red transition-all duration-300 rounded-full">
					Planifier un appel
				</a>
				<a href="#contact" class="px-6 py-2.5 border-2 border-black text-black text-[14px] font-medium hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full">
					Demander une soumission
				</a>
			</div>
		</div>

		<div class="md:hidden flex items-center gap-3">
			<a href="#contact" class="px-5 py-2 bg-black text-white text-[13px] font-medium hover:bg-accent-red transition-all duration-300 rounded-full whitespace-nowrap">
				Planifier un appel
			</a>
			<button id="mobile-menu-toggle" class="p-2.5 bg-black text-white hover:bg-accent-red transition-all duration-300 rounded-full">
				<i data-lucide="menu" class="w-5 h-5"></i>
			</button>
		</div>
	</div>
</nav>

<div id="mobile-menu" class="fixed inset-0 z-40 transform translate-x-full transition-transform duration-300 ease-in-out md:hidden" style="background-color: #f5f3ed;">
	<div class="flex flex-col h-full pt-24 px-8 pb-12">
		<nav class="flex-1 flex flex-col gap-8 text-[18px]">
			<a href="#services" class="mobile-menu-link hover:opacity-60 transition-opacity py-2">Services</a>
			<a href="#realisations" class="mobile-menu-link hover:opacity-60 transition-opacity py-2">Réalisations</a>
			<a href="#apropos" class="mobile-menu-link hover:opacity-60 transition-opacity py-2">À propos</a>
			<a href="#contact" class="mobile-menu-link hover:opacity-60 transition-opacity py-2">Contact</a>
		</nav>

		<div class="space-y-6 border-t border-[#b0b0b0] pt-8">
			<div class="flex items-center gap-4 text-[16px]">
				<?php echo trepied_language_switcher('/'); ?>
			</div>
			<a href="#contact" class="mobile-menu-link block w-full text-center px-7 py-3 bg-black text-white text-[15px] hover:bg-accent-red transition-all duration-300 rounded-full">
				Planifier un appel
			</a>
			<a href="#contact" class="mobile-menu-link block w-full text-center px-7 py-3 border-2 border-black text-black text-[15px] hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full">
				Demander une soumission
			</a>
		</div>
	</div>
</div>
