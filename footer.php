<?php
if (!defined('ABSPATH')) {
	exit;
}
?>

<footer class="px-8 md:px-16 lg:px-24 pb-12 pt-16 border-t border-[#b0b0b0]">
	<div class="max-w-[1400px] mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
		<div class="text-[15px] font-black tracking-[0.02em] uppercase">
			Trépied
		</div>
		<div class="text-[14px] text-[#6a6a6a] flex flex-wrap items-center gap-x-2 gap-y-1">
			<span>© <?php echo date('Y'); ?> Trépied. <?php echo esc_html__('Video production — Montréal', 'trepied'); ?></span>
			<?php $privacy_policy_url = trepied_get_privacy_policy_url(); ?>
			<?php if ($privacy_policy_url) : ?>
			<span aria-hidden="true">·</span>
			<a href="<?php echo esc_url($privacy_policy_url); ?>" class="hover:opacity-60 transition-opacity">
				<?php echo esc_html__('Privacy Policy', 'trepied'); ?>
			</a>
			<?php endif; ?>
			<span aria-hidden="true">·</span>
			<button type="button" id="magneto-consent-manage" class="magneto-consent-manage-link hover:opacity-60 transition-opacity">
				<?php echo esc_html__('Manage Cookies', 'trepied'); ?>
			</button>
		</div>
		<div class="flex items-center gap-4">
			<a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="hover:opacity-60 transition-opacity" aria-label="<?php echo esc_attr__('Instagram', 'trepied'); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-[#1a1a1a]">
					<rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
					<path d="M16 11.37a4 4 0 1 1-7.914 1.174A4 4 0 0 1 16 11.37z"></path>
					<line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
				</svg>
			</a>
			<a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="hover:opacity-60 transition-opacity" aria-label="<?php echo esc_attr__('YouTube', 'trepied'); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-[#1a1a1a]">
					<path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"></path>
					<path d="m10 15 5-3-5-3z"></path>
				</svg>
			</a>
		</div>
	</div>
</footer>

<div id="project-modal" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 md:p-8 hidden">
	<div class="project-modal__dialog bg-cream rounded-2xl w-full max-w-[1000px] max-h-[90vh] overflow-hidden" style="background-color: #f5f3ed;" role="dialog" aria-modal="true" aria-labelledby="project-modal-title">
		<div class="relative h-full">
			<button id="modal-close" class="absolute top-4 right-4 z-20 p-2 bg-black text-white rounded-full hover:bg-accent-red transition-all duration-300" aria-label="<?php echo esc_attr__('Close modal', 'trepied'); ?>">
				<i data-lucide="x" class="w-5 h-5"></i>
			</button>

			<div class="max-h-[90vh] overflow-y-auto">
				<div id="modal-content" class=""></div>
			</div>
		</div>
	</div>
</div>

<!-- Quote Request Modal -->
<div id="quote-modal" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 md:p-8 hidden" data-quote-modal>
	<div class="bg-cream rounded-2xl w-full max-w-[640px] max-h-[90vh] overflow-hidden" style="background-color: #f5f3ed;" onclick="event.stopPropagation()">
		<div class="relative h-full">
			<button id="quote-modal-close" class="absolute top-4 right-4 z-20 p-2 bg-black text-white rounded-full hover:bg-accent-red transition-all duration-300" aria-label="<?php echo esc_attr__('Close modal', 'trepied'); ?>">
				<i data-lucide="x" class="w-5 h-5"></i>
			</button>

			<div class="max-h-[90vh] overflow-y-auto p-6 md:p-8">
				<h3 class="text-[26px] md:text-[32px] font-bold mb-2 text-black font-condensed pr-10">
					<?php echo esc_html__('Request a Quote', 'trepied'); ?>
				</h3>
				<p class="text-[16px] text-[#4a4a4a] mb-6">
					<?php echo esc_html__('Tell us about your project and we\'ll get back to you within 24-48 hours.', 'trepied'); ?>
				</p>

				<!-- Success/Error Messages -->
				<div id="quote-form-messages" class="hidden mb-6" data-form-messages>
					<div class="p-4 rounded-lg text-[15px]" data-message-content></div>
				</div>

				<form id="quote-request-form" method="post" data-form-submit="quote" class="space-y-5">
					<?php wp_nonce_field('trepied_quote_request', 'quote_nonce'); ?>

					<!-- Honeypot field -->
					<div class="absolute -left-[9999px]" aria-hidden="true">
						<label for="website_url"><?php echo esc_html__('Leave this empty', 'trepied'); ?></label>
						<input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
						<!-- Name -->
						<div>
							<label for="quote_name" class="block text-[14px] font-medium mb-2 text-[#1a1a1a]">
								<?php echo esc_html__('Name', 'trepied'); ?> <span class="text-accent-red">*</span>
							</label>
							<input
								type="text"
								id="quote_name"
								name="name"
								required
								class="w-full px-4 py-3 border border-[#c0c0c0] rounded-lg text-[15px] bg-white focus:border-black focus:outline-none transition-colors"
								data-field="name"
							>
						</div>

						<!-- Email -->
						<div>
							<label for="quote_email" class="block text-[14px] font-medium mb-2 text-[#1a1a1a]">
								<?php echo esc_html__('Email', 'trepied'); ?> <span class="text-accent-red">*</span>
							</label>
							<input
								type="email"
								id="quote_email"
								name="email"
								required
								class="w-full px-4 py-3 border border-[#c0c0c0] rounded-lg text-[15px] bg-white focus:border-black focus:outline-none transition-colors"
								data-field="email"
							>
						</div>
					</div>

					<!-- Company -->
					<div>
						<label for="quote_company" class="block text-[14px] font-medium mb-2 text-[#1a1a1a]">
							<?php echo esc_html__('Company', 'trepied'); ?>
						</label>
						<input
							type="text"
							id="quote_company"
							name="company"
							class="w-full px-4 py-3 border border-[#c0c0c0] rounded-lg text-[15px] bg-white focus:border-black focus:outline-none transition-colors"
							data-field="company"
						>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
						<!-- Budget Range -->
						<div>
							<label for="quote_budget" class="block text-[14px] font-medium mb-2 text-[#1a1a1a]">
								<?php echo esc_html__('Budget Range', 'trepied'); ?> <span class="text-accent-red">*</span>
							</label>
							<select
								id="quote_budget"
								name="budget_range"
								required
								class="w-full px-4 py-3 border border-[#c0c0c0] rounded-lg text-[15px] bg-white focus:border-black focus:outline-none transition-colors appearance-none cursor-pointer"
								data-field="budget_range"
							>
								<option value=""><?php echo esc_html__('Select budget', 'trepied'); ?></option>
								<option value="5k-10k">$5,000 - $10,000</option>
								<option value="10k-25k">$10,000 - $25,000</option>
								<option value="25k-50k">$25,000 - $50,000</option>
								<option value="50k+">$50,000+</option>
							</select>
						</div>

						<!-- Timeline -->
						<div>
							<label for="quote_timeline" class="block text-[14px] font-medium mb-2 text-[#1a1a1a]">
								<?php echo esc_html__('Timeline', 'trepied'); ?> <span class="text-accent-red">*</span>
							</label>
							<select
								id="quote_timeline"
								name="timeline"
								required
								class="w-full px-4 py-3 border border-[#c0c0c0] rounded-lg text-[15px] bg-white focus:border-black focus:outline-none transition-colors appearance-none cursor-pointer"
								data-field="timeline"
							>
								<option value=""><?php echo esc_html__('Select timeline', 'trepied'); ?></option>
								<option value="asap"><?php echo esc_html__('ASAP', 'trepied'); ?></option>
								<option value="1-2-months"><?php echo esc_html__('1-2 months', 'trepied'); ?></option>
								<option value="3-6-months"><?php echo esc_html__('3-6 months', 'trepied'); ?></option>
								<option value="flexible"><?php echo esc_html__('Flexible', 'trepied'); ?></option>
							</select>
						</div>
					</div>

					<!-- Project Description -->
					<div>
						<label for="quote_description" class="block text-[14px] font-medium mb-2 text-[#1a1a1a]">
							<?php echo esc_html__('Project Description', 'trepied'); ?> <span class="text-accent-red">*</span>
						</label>
						<textarea
							id="quote_description"
							name="project_description"
							required
							rows="4"
							class="w-full px-4 py-3 border border-[#c0c0c0] rounded-lg text-[15px] bg-white focus:border-black focus:outline-none transition-colors resize-none"
							data-field="project_description"
							placeholder="<?php echo esc_attr__('Tell us about your project...', 'trepied'); ?>"
						></textarea>
					</div>

					<!-- GDPR Consent -->
					<div class="flex items-start gap-3">
						<input
							type="checkbox"
							id="quote_gdpr"
							name="gdpr_consent"
							required
							class="mt-1 w-4 h-4 border border-[#c0c0c0] rounded cursor-pointer accent-black"
							data-field="gdpr_consent"
						>
						<label for="quote_gdpr" class="text-[14px] text-[#4a4a4a] cursor-pointer">
							<?php echo esc_html__('I agree to the processing of my personal data in accordance with the Privacy Policy.', 'trepied'); ?> <span class="text-accent-red">*</span>
						</label>
					</div>

					<!-- Submit Button -->
					<div class="pt-2">
						<button
							type="submit"
							class="inline-block px-7 py-3 bg-black text-white text-[15px] hover:bg-accent-red transition-all duration-300 rounded-full cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
							data-form-submit-btn
						>
							<span data-btn-text><?php echo esc_html__('Send Request', 'trepied'); ?></span>
							<span data-btn-loading class="hidden"><?php echo esc_html__('Sending...', 'trepied'); ?></span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Go to Top Button -->
<button id="go-to-top" class="fixed bottom-6 right-6 z-50 p-3 bg-black text-white rounded-full shadow-lg hover:bg-accent-red transition-all duration-300 opacity-0 invisible translate-y-4" aria-label="<?php echo esc_attr__('Go to top', 'trepied'); ?>">
	<i data-lucide="chevron-up" class="w-5 h-5"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>
