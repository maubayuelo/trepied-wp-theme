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
		<div class="text-[14px] text-[#6a6a6a]">
			© 2026 Trépied. Production vidéo — Montréal
		</div>
		<div class="flex items-center gap-4">
			<a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="hover:opacity-60 transition-opacity" aria-label="Instagram">
				<i data-lucide="instagram" class="w-5 h-5 text-[#1a1a1a]"></i>
			</a>
			<a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="hover:opacity-60 transition-opacity" aria-label="YouTube">
				<i data-lucide="youtube" class="w-5 h-5 text-[#1a1a1a]"></i>
			</a>
		</div>
	</div>
</footer>

<div id="project-modal" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-sm flex items-center justify-center p-3 md:p-8 hidden">
	<div class="bg-cream rounded-2xl w-full max-w-[1000px] max-h-[90vh] overflow-hidden" style="background-color: #f5f3ed;" onclick="event.stopPropagation()">
		<div class="relative h-full">
			<button id="modal-close" class="absolute top-4 right-4 z-20 p-2 bg-black text-white rounded-full hover:bg-accent-red transition-all duration-300" aria-label="Close modal">
				<i data-lucide="x" class="w-5 h-5"></i>
			</button>

			<div class="max-h-[90vh] overflow-y-auto">
				<div id="modal-content" class=""></div>
			</div>
		</div>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
