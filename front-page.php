<?php
if (!defined('ABSPATH')) {
	exit;
}

get_header();

// Get ACF hero data
$hero = trepied_get_group('hero');
$hero_copy = $hero['copy'] ?? '';
$hero_subtitle = $hero['subtitle'] ?? '';
$hero_video        = $hero['video'] ?? '';
$hero_video_mobile = $hero['video_mobile'] ?? '';
$hero_image        = $hero['image'] ?? null;
?>

<section class="pt-32 pb-12 md:pt-44 md:pb-16 px-8 md:px-16 lg:px-24">
	<div class="max-w-[1400px] mx-auto">
		<div class="flex flex-col lg:flex-row gap-9 items-start">
			<div class="flex-1 max-w-[920px] order-1">
				<?php if ($hero_copy) : ?>
				<h1 class="text-[36px] md:text-[42px] lg:text-[60px] leading-[1.0] font-bold tracking-[-0.02em] mb-9 text-black uppercase font-condensed drop-shadow-md">
					<?php echo esc_html($hero_copy); ?>
				</h1>
				<?php endif; ?>
				<?php if ($hero_subtitle) : ?>
				<p class="text-[19px] md:text-[21px] leading-[1.5] text-[#4a4a4a] max-w-[680px] mb-9">
					<?php echo esc_html($hero_subtitle); ?>
				</p>
				<?php endif; ?>
				<?php trepied_calendly_button(
					esc_html__("Let's talk about your project", 'trepied'),
					'font-bold uppercase drop-shadow-md'
				); ?>
			</div>
			<div class="hidden lg:block lg:w-[298px] shrink-0 order-2">
				<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/symbol.png'); ?>" alt="" class="w-full h-auto max-h-[280px] object-contain rounded-2xl opacity-30 mix-blend-difference">
			</div>
		</div>
	</div>
</section>

<section class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48 border-b border-[#b0b0b0] pb-32 md:pb-48">
	<div class="max-w-[1400px] mx-auto">
		<?php if ($hero_video || ($hero_image && !empty($hero_image['id']))) : ?>
		<div class="relative w-full h-[400px] md:h-[580px] rounded-2xl overflow-hidden">
			<?php if ($hero_video) : ?>
				<video
					class="absolute top-1/2 left-0 -translate-y-1/2 w-full h-[105%] object-cover pointer-events-none"
					autoplay muted loop playsinline>
					<?php if ($hero_video_mobile) : ?>
					<source media="(max-width: 1023px)" src="<?php echo esc_url($hero_video_mobile); ?>" type="video/mp4">
					<?php endif; ?>
					<source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
				</video>
			<?php elseif ($hero_image && !empty($hero_image['id'])) : ?>
				<?php echo wp_get_attachment_image($hero_image['id'], 'trepied-hero', false, [
					'class' => 'absolute inset-0 w-full h-full object-cover',
					'fetchpriority' => 'high',
					'decoding' => 'async',
				]); ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<div class="lg:hidden mt-12 flex justify-center">
			<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/symbol.png'); ?>" alt="" class="w-1/4 scale-[0.8625] h-auto rounded-2xl opacity-30 mix-blend-difference">
		</div>
	</div>
</section>

<?php
// Get ACF services data
$services = trepied_get_group('services');
$services_title = $services['title'] ?? '';
$services_description = $services['description'] ?? '';
$services_items = $services['service'] ?? [];
?>

<section id="services" class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48 border-b border-[#b0b0b0] pb-32 md:pb-48">
	<div class="max-w-[1400px] mx-auto">
		<?php if ($services_title || $services_description) : ?>
		<div class="mb-20 md:mb-28">
			<?php if ($services_title) : ?>
			<h2 class="text-[32px] md:text-[44px] lg:text-[54px] leading-[1.15] font-bold tracking-[-0.01em] mb-6 text-black font-condensed">
				<?php echo esc_html($services_title); ?>
			</h2>
			<?php endif; ?>
			<?php if ($services_description) : ?>
			<p class="text-[19px] md:text-[21px] leading-[1.5] text-[#4a4a4a] max-w-[720px]">
				<?php echo esc_html($services_description); ?>
			</p>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if (!empty($services_items)) : ?>
		<div class="space-y-32 md:space-y-32">
			<?php foreach ($services_items as $index => $service) :
				$service_title = $service['service_title'] ?? '';
				$service_description = $service['service_description'] ?? '';
				$service_image = $service['service_image'] ?? null;
				$service_link = $service['service_link_text'] ?? '';
				$is_even = ($index % 2 === 1);
			?>
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
				<?php if ($is_even) : ?>
				<!-- Even: Text first (mobile), Image first (desktop) -->
				<div class="order-2 lg:order-1 lg:col-span-5 lg:pt-8">
					<?php if ($service_title) : ?>
					<h3 class="text-[26px] md:text-[32px] leading-[1.25] font-bold mb-6">
						<?php echo esc_html($service_title); ?>
					</h3>
					<?php endif; ?>
					<?php if ($service_description || $service_link) : ?>
					<div class="space-y-5 text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a]">
						<?php if ($service_description) : ?>
						<div class="service-description">
							<?php echo wp_kses_post($service_description); ?>
						</div>
						<?php endif; ?>
						<?php if ($service_link) : ?>
						<p class="mt-6">
							<button type="button" class="calendly-popup-trigger font-bold text-[#1a1a1a] hover:text-accent-red transition-all underline underline-offset-4" data-calendly-url="<?php echo esc_attr(trepied_get_calendly_url()); ?>">
								<?php echo esc_html($service_link); ?> →
							</button>
						</p>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
				<?php if ($service_image && !empty($service_image['id'])) : ?>
				<div class="order-1 lg:order-2 lg:col-span-7">
					<?php echo wp_get_attachment_image($service_image['id'], 'trepied-service', false, [
						'class' => 'w-full h-[420px] md:h-[520px] object-cover rounded-2xl',
						'loading' => 'lazy',
					]); ?>
				</div>
				<?php endif; ?>
				<?php else : ?>
				<!-- Odd: Image first -->
				<?php if ($service_image && !empty($service_image['id'])) : ?>
				<div class="lg:col-span-7">
					<?php echo wp_get_attachment_image($service_image['id'], 'trepied-service', false, [
						'class' => 'w-full h-[420px] md:h-[520px] object-cover rounded-2xl',
						'loading' => 'lazy',
					]); ?>
				</div>
				<?php endif; ?>
				<div class="lg:col-span-5 lg:pt-8">
					<?php if ($service_title) : ?>
					<h3 class="text-[26px] md:text-[32px] leading-[1.25] font-bold mb-6">
						<?php echo esc_html($service_title); ?>
					</h3>
					<?php endif; ?>
					<?php if ($service_description || $service_link) : ?>
					<div class="space-y-5 text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a]">
						<?php if ($service_description) : ?>
						<div class="service-description">
							<?php echo wp_kses_post($service_description); ?>
						</div>
						<?php endif; ?>
						<?php if ($service_link) : ?>
						<p class="mt-6">
							<button type="button" class="calendly-popup-trigger font-semibold text-[#1a1a1a] hover:text-accent-red transition-all underline underline-offset-4" data-calendly-url="<?php echo esc_attr(trepied_get_calendly_url()); ?>">
								<?php echo esc_html($service_link); ?> →
							</button>
						</p>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<div class="text-center mt-24">
			<?php trepied_calendly_button(
				esc_html__('Schedule a Call', 'trepied'),
				'border-2 border-black !bg-transparent !text-black hover:!bg-accent-red hover:!text-white hover:!border-accent-red'
			); ?>
		</div>
	</div>
</section>

<?php
// Get ACF projects data
$projects = trepied_get_group('projects');
$projects_title = $projects['title'] ?? '';
$projects_description = $projects['description'] ?? '';
$projects_items = $projects['project'] ?? [];
?>

<section id="projects" class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48 border-b border-[#b0b0b0] pb-32 md:pb-48">
	<div class="max-w-[1400px] mx-auto">
		<?php if ($projects_title || $projects_description) : ?>
		<div class="mb-20 md:mb-28">
			<?php if ($projects_title) : ?>
			<h2 class="text-[32px] md:text-[44px] lg:text-[54px] leading-[1.15] font-bold tracking-[-0.01em] mb-6 text-black font-condensed">
				<?php echo esc_html($projects_title); ?>
			</h2>
			<?php endif; ?>
			<?php if ($projects_description) : ?>
			<p class="text-[19px] md:text-[21px] leading-[1.5] text-[#4a4a4a] max-w-[720px]">
				<?php echo esc_html($projects_description); ?>
			</p>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if (!empty($projects_items)) : ?>
		<div class="space-y-20 md:space-y-24">
			<?php foreach ($projects_items as $index => $project) :
				$project_title = $project['project_title'] ?? '';
				$project_image = $project['project_image'] ?? null;
				$project_client = $project['client'] ?? '';
				$project_description = $project['short_description'] ?? '';
				$project_long_description = $project['long_description'] ?? '';
				$project_video_id = $project['yt_video_id'] ?? '';
			?>
			<div class="project-card"
				data-project-id="<?php echo esc_attr($index); ?>"
				data-project-title="<?php echo esc_attr($project_title); ?>"
				data-project-client="<?php echo esc_attr($project_client); ?>"
				data-project-video="<?php echo esc_attr($project_video_id); ?>">
				
				<?php if ($project_image && !empty($project_image['id'])) : ?>
				<?php echo wp_get_attachment_image($project_image['id'], 'trepied-hero', false, [
					'class' => 'w-full h-[360px] md:h-[520px] object-cover rounded-2xl mb-8',
					'loading' => 'lazy',
				]); ?>
				<?php endif; ?>
				
				<div class="max-w-[680px]">
					<?php if ($project_title) : ?>
					<h3 class="text-[24px] md:text-[28px] leading-[1.3] font-medium mb-3">
						<?php echo esc_html($project_title); ?>
					</h3>
					<?php endif; ?>
					<?php if ($project_client) : ?>
					<p class="text-[16px] md:text-[17px] font-medium mb-2 text-[#1a1a1a]">
						<?php echo esc_html($project_client); ?>
					</p>
					<?php endif; ?>
					<?php if ($project_description) : ?>
					<p class="text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a] mb-6">
						<?php echo esc_html($project_description); ?>
					</p>
					<?php endif; ?>
					<button type="button" class="project-modal-trigger inline-block text-[15px] text-[#1a1a1a] hover:text-[#ff0000] transition-all underline underline-offset-4 font-bold">
						<?php echo esc_html__('View project', 'trepied'); ?> →
					</button>
				</div>
				
				<!-- Hidden long description for modal -->
				<template class="project-long-description">
					<?php echo wp_kses_post($project_long_description); ?>
				</template>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<div class="text-center mt-20">
			<button type="button" class="quote-modal-trigger inline-block px-7 py-3 border-2 border-black text-black text-[15px] hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full cursor-pointer">
				<?php echo esc_html__('Request a Quote', 'trepied'); ?>
			</button>
		</div>
	</div>
</section>

<?php
// Get ACF about data
$about = trepied_get_group('about');
$about_title = $about['title'] ?? '';
$about_subtitle = $about['subtitle'] ?? '';
$about_paragraph = $about['paragraph'] ?? '';
$about_image = $about['image'] ?? null;
$about_image_id = is_array($about_image) ? ($about_image['id'] ?? null) : $about_image;
?>

<section id="about" class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48 border-b border-[#b0b0b0] pb-32 md:pb-48">
	<div class="max-w-[1400px] mx-auto">
		<?php if ($about_title || $about_subtitle || $about_paragraph) : ?>
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 mb-16 md:mb-20">
			<?php if ($about_title) : ?>
			<div class="lg:col-span-7">
				<h2 class="text-[32px] md:text-[44px] lg:text-[52px] leading-[1.15] font-bold tracking-[-0.01em] mb-8 text-black font-condensed">
					<?php echo esc_html($about_title); ?>
				</h2>
			</div>
			<?php endif; ?>
			<?php if ($about_subtitle || $about_paragraph) : ?>
			<div class="lg:col-span-5 space-y-6 text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a] lg:pt-4">
				<?php if ($about_subtitle) : ?>
				<p class="text-[19px] md:text-[20px] text-[#1a1a1a] font-bold">
					<?php echo esc_html($about_subtitle); ?>
				</p>
				<?php endif; ?>
				<?php if ($about_paragraph) : ?>
				<div class="about-content">
					<?php echo wp_kses_post($about_paragraph); ?>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>

		<?php if ($about_image_id) : ?>
		<div>
			<?php echo wp_get_attachment_image($about_image_id, 'trepied-hero', false, [
				'class' => 'w-full h-[360px] md:h-[480px] object-cover rounded-2xl',
				'loading' => 'lazy',
			]); ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php
// Get ACF testimonials data
$testimonials = trepied_get_group('testimonials');
$testimonials_title = $testimonials['title'] ?? '';
$testimonials_items = $testimonials['testimonial'] ?? [];
?>

<section class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48 border-b border-[#b0b0b0] pb-32 md:pb-48">
	<div class="max-w-[1400px] mx-auto">
		<?php if ($testimonials_title) : ?>
		<div class="mb-16 md:mb-20 text-center">
			<h2 class="text-[32px] md:text-[44px] lg:text-[54px] leading-[1.15] font-bold tracking-[-0.01em] text-black font-condensed">
				<?php echo esc_html($testimonials_title); ?>
			</h2>
		</div>
		<?php endif; ?>

		<?php if (!empty($testimonials_items)) : ?>
		<div class="max-w-[920px] mx-auto relative">
			<button id="testimonial-prev" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 md:-translate-x-16 lg:-translate-x-20 p-3 hover:opacity-60 transition-opacity hidden sm:block" aria-label="Previous testimonial">
				<i data-lucide="chevron-left" class="w-6 h-6"></i>
			</button>

			<button id="testimonial-next" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 md:translate-x-16 lg:translate-x-20 p-3 hover:opacity-60 transition-opacity hidden sm:block" aria-label="Next testimonial">
				<i data-lucide="chevron-right" class="w-6 h-6"></i>
			</button>

			<div id="testimonial-slider" class="py-12">
				<?php foreach ($testimonials_items as $index => $item) :
					$client = $item['client'] ?? '';
					$role = $item['role'] ?? '';
					$quote = $item['testimonial'] ?? '';
				?>
				<div class="testimonial-item <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo esc_attr($index); ?>">
					<?php if ($quote) : ?>
					<p class="text-[24px] md:text-[28px] lg:text-[32px] leading-[1.4] font-medium mb-12 text-[#1a1a1a]">
						<?php echo esc_html($quote); ?>
					</p>
					<?php endif; ?>
					<?php if ($client || $role) : ?>
					<div class="text-[16px] md:text-[17px]">
						<?php if ($client) : ?>
						<p class="font-medium text-[#1a1a1a]"><?php echo esc_html($client); ?></p>
						<?php endif; ?>
						<?php if ($role) : ?>
						<p class="text-[#6a6a6a]"><?php echo esc_html($role); ?></p>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>

			<div id="testimonial-dots" class="flex justify-center gap-5 sm:gap-2 mt-8">
				<?php foreach ($testimonials_items as $index => $item) : ?>
				<button class="testimonial-dot w-3 h-3 sm:w-2 sm:h-2 rounded-full transition-all duration-300 <?php echo $index === 0 ? 'active bg-[#1a1a1a]' : 'bg-[#d0d0d0]'; ?>" data-index="<?php echo esc_attr($index); ?>" aria-label="Testimonial <?php echo esc_attr($index + 1); ?>"></button>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php
// Get ACF CTA section data
$cta = trepied_get_group('cta_section');
$cta_title = $cta['title'] ?? '';
$cta_paragraph = $cta['paragraph'] ?? '';
?>

<section id="contact" class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48">
	<div class="max-w-[1400px] mx-auto">
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-20">
			<?php if ($cta_title) : ?>
			<div class="lg:col-span-7">
				<h2 class="text-[32px] md:text-[44px] lg:text-[54px] leading-[1.15] font-bold tracking-[-0.01em] text-black font-condensed">
					<?php echo esc_html($cta_title); ?>
				</h2>
			</div>
			<?php endif; ?>

			<div class="lg:col-span-5 lg:pt-4">
				<?php if ($cta_paragraph) : ?>
				<div class="mb-10 space-y-3 text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a]">
					<?php echo wp_kses_post($cta_paragraph); ?>
				</div>
				<?php endif; ?>

				<div class="space-y-4 text-[16px]">
					<div>
						<?php trepied_calendly_button(esc_html__('Schedule a Free Call', 'trepied')); ?>
					</div>
					<div>
						<button type="button" class="quote-modal-trigger inline-block px-7 py-3 border-2 border-black text-black text-[15px] hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full cursor-pointer">
							<?php echo esc_html__('Request a Quote', 'trepied'); ?>
						</button>
					</div>
					<div>
						<a href="#" class="inline-block px-7 py-3 border-2 border-black text-black text-[15px] hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full">
							<?php echo esc_html__('WhatsApp', 'trepied'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
