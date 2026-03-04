<?php
if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>

<section class="pt-32 pb-12 md:pt-44 md:pb-16 px-8 md:px-16 lg:px-24">
	<div class="max-w-[1400px] mx-auto">
		<div class="flex flex-col lg:flex-row gap-9 items-start">
			<div class="flex-1 max-w-[920px] order-1">
				<h1 class="text-[36px] md:text-[42px] lg:text-[60px] leading-[1.0] font-bold tracking-[-0.02em] mb-9 text-black uppercase font-condensed drop-shadow-md">
					Production vidéo créative à Montréal pour raconter ce qui compte vraiment
				</h1>
				<p class="text-[19px] md:text-[21px] leading-[1.5] text-[#4a4a4a] max-w-[680px] mb-9">
					Des vidéos claires et humaines, pensées pour créer du lien — pas pour faire du bruit.
				</p>
				<a href="#contact" class="inline-block px-7 py-3 bg-black text-white text-[15px] font-bold hover:bg-accent-red transition-all duration-300 rounded-full uppercase drop-shadow-md">
					Parlons de votre projet
				</a>
			</div>
			<div class="hidden lg:block lg:w-[298px] shrink-0 order-2">
				<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/symbol.png'); ?>" alt="" class="w-full h-auto max-h-[280px] object-contain rounded-2xl opacity-30 mix-blend-difference">
			</div>
		</div>
	</div>
</section>

<section class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48 border-b border-[#b0b0b0] pb-32 md:pb-48">
	<div class="max-w-[1400px] mx-auto">
		<div class="relative w-full h-[400px] md:h-[580px] rounded-2xl overflow-hidden">
			<iframe src="https://www.youtube.com/embed/kcfs1-ryKWE?autoplay=1&amp;mute=1&amp;loop=1&amp;playlist=kcfs1-ryKWE&amp;controls=0&amp;modestbranding=1&amp;rel=0&amp;showinfo=0&amp;iv_load_policy=3&amp;disablekb=1&amp;fs=0" title="" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[177.77%] h-[177.77%] pointer-events-none" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
		</div>

		<div class="lg:hidden mt-12 flex justify-center">
			<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/symbol.png'); ?>" alt="" class="w-1/4 scale-[0.8625] h-auto rounded-2xl opacity-30 mix-blend-difference">
		</div>
	</div>
</section>

<section id="services" class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48 border-b border-[#b0b0b0] pb-32 md:pb-48">
	<div class="max-w-[1400px] mx-auto">
		<div class="mb-20 md:mb-28">
			<h2 class="text-[32px] md:text-[44px] lg:text-[54px] leading-[1.15] font-bold tracking-[-0.01em] mb-6 text-black font-condensed">
				Ce Que Nous Faisons
			</h2>
			<p class="text-[19px] md:text-[21px] leading-[1.5] text-[#4a4a4a] max-w-[720px]">
				On ne fait pas juste des vidéos. On vous accompagne pour transformer vos idées en images claires, utiles et justes.
			</p>
		</div>

		<div class="space-y-32 md:space-y-32">
			<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
				<div class="lg:col-span-7">
					<img src="https://images.unsplash.com/photo-1620440589020-9cf02752bdcc?crop=entropy&amp;cs=tinysrgb&amp;fit=max&amp;fm=jpg&amp;ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx2aWRlbyUyMGNhbWVyYSUyMGNpbmVtYXRvZ3JhcGh5fGVufDF8fHx8MTc3MDUzMzc3MXww&amp;ixlib=rb-4.1.0&amp;q=80&amp;w=1080&amp;utm_source=figma&amp;utm_medium=referral" alt="" class="w-full h-[420px] md:h-[520px] object-cover rounded-2xl">
				</div>
				<div class="lg:col-span-5 lg:pt-8">
					<h3 class="text-[26px] md:text-[32px] leading-[1.25] font-bold mb-6">
						Production vidéo corporative
					</h3>
					<div class="space-y-5 text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a]">
						<p class="font-medium text-[#1a1a1a]">
							Présenter une organisation, c'est raconter ce qu'elle fait — et pourquoi elle le fait.
						</p>
						<p>
							Vidéos institutionnelles ou internes, pensées pour durer et être comprises par celles et ceux à qui elles s'adressent.
						</p>
						<p class="font-medium text-[#1a1a1a]">
							Une image professionnelle, humaine et crédible.
						</p>
						<p class="mt-6">
							<a href="#contact" class="font-semibold text-[#1a1a1a] hover:text-accent-red transition-all underline underline-offset-4">
								Voir si ce format est adapté à votre projet →
							</a>
						</p>
					</div>
				</div>
			</div>

			<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
				<div class="order-2 lg:order-1 lg:col-span-5 lg:pt-8">
					<h3 class="text-[26px] md:text-[32px] leading-[1.25] font-bold mb-6">
						Contenu pour réseaux sociaux
					</h3>
					<div class="space-y-5 text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a]">
						<p class="font-medium text-[#1a1a1a]">
							Sur les réseaux, chaque seconde compte.
						</p>
						<p>
							On conçoit des vidéos courtes, pensées pour le mobile, avec un message clair dès les premières images.
						</p>
						<p class="font-medium text-[#1a1a1a]">
							Pas de contenu vide. Pas de recettes recyclées.
						</p>
						<p class="mt-6">
							<a href="#contact" class="font-bold text-[#1a1a1a] hover:text-accent-red transition-all underline underline-offset-4">
								Discuter d'un format social →
							</a>
						</p>
					</div>
				</div>
				<div class="order-1 lg:order-2 lg:col-span-7">
					<img src="https://images.unsplash.com/photo-1753164725054-763fe44e3be8?q=80&amp;w=1600&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.1.0&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" class="w-full h-[420px] md:h-[520px] object-cover rounded-2xl">
				</div>
			</div>

			<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
				<div class="lg:col-span-7">
					<img src="https://images.unsplash.com/photo-1591129963919-b7bebbe969e9?q=80&amp;w=1600&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.1.0&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="" class="w-full h-[420px] md:h-[520px] object-cover rounded-2xl">
				</div>
				<div class="lg:col-span-5 lg:pt-8">
					<h3 class="text-[26px] md:text-[32px] leading-[1.25] font-bold mb-6">
						Événements &amp; culture
					</h3>
					<div class="space-y-5 text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a]">
						<p class="font-medium text-[#1a1a1a]">
							Certains projets méritent d'être documentés avec sensibilité.
						</p>
						<p>
							On capte l'essentiel : les gens, l'énergie, le sens derrière ce qui se passe réellement.
						</p>
						<p class="mt-6">
							<a href="#contact" class="font-bold text-[#1a1a1a] hover:text-accent-red transition-all underline underline-offset-4">
								Parler d'un projet culturel →
							</a>
						</p>
					</div>
				</div>
			</div>
		</div>

		<div class="text-center mt-24">
			<a href="#contact" class="inline-block px-7 py-3 border-2 border-black text-black text-[15px] hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full">
				Planifier un appel
			</a>
		</div>
	</div>
</section>

<section id="realisations" class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48 border-b border-[#b0b0b0] pb-32 md:pb-48">
	<div class="max-w-[1400px] mx-auto">
		<div class="mb-20 md:mb-28">
			<h2 class="text-[32px] md:text-[44px] lg:text-[54px] leading-[1.15] font-bold tracking-[-0.01em] mb-6 text-black font-condensed">
				Réalisations Récentes
			</h2>
			<p class="text-[19px] md:text-[21px] leading-[1.5] text-[#4a4a4a] max-w-[720px]">
				Une sélection de projets récents, pensés pour transmettre un message clair et créer un lien avec leur public.
			</p>
		</div>

		<div id="projects-container" class="space-y-20 md:space-y-24">
		</div>

		<div class="text-center mt-20">
			<a href="#contact" class="inline-block px-7 py-3 border-2 border-black text-black text-[15px] hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full">
				Demander une soumission
			</a>
		</div>
	</div>
</section>

<section id="apropos" class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48 border-b border-[#b0b0b0] pb-32 md:pb-48">
	<div class="max-w-[1400px] mx-auto">
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 mb-16 md:mb-20">
			<div class="lg:col-span-7">
				<h2 class="text-[32px] md:text-[44px] lg:text-[52px] leading-[1.15] font-bold tracking-[-0.01em] mb-8 text-black font-condensed">
					Vous avez un message à transmettre. Nous sommes là pour le rendre possible, avec vous.
				</h2>
			</div>
			<div class="lg:col-span-5 space-y-6 text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a] lg:pt-4">
				<p class="text-[19px] md:text-[20px] text-[#1a1a1a] font-bold">
					On travaille comme un partenaire, pas comme un simple prestataire : en écoutant, en questionnant et en simplifiant quand c'est nécessaire.
				</p>
				<p>
					Nous collaborons principalement avec le secteur des PME, les organismes à but non lucratif, ainsi que les porteurs de projets culturels, environnementaux et urbains.
				</p>
				<p class="font-medium text-[#1a1a1a]">
					Créatifs et accessibles. Sérieux sur le fond, souples dans la manière de faire.
				</p>
			</div>
		</div>

		<div>
			<img src="https://images.unsplash.com/photo-1588459894867-cddd23e05fdf?q=80&amp;w=1600&amp;auto=format&amp;fit=crop&amp;ixlib=rb-4.1.0&amp;ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Équipe Trépied" class="w-full h-[360px] md:h-[480px] object-cover rounded-2xl">
		</div>
	</div>
</section>

<section class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48 border-b border-[#b0b0b0] pb-32 md:pb-48">
	<div class="max-w-[1400px] mx-auto">
		<div class="mb-16 md:mb-20 text-center">
			<h2 class="text-[32px] md:text-[44px] lg:text-[54px] leading-[1.15] font-bold tracking-[-0.01em] text-black font-condensed">
				Témoignages
			</h2>
		</div>

		<div class="max-w-[920px] mx-auto relative">
			<button id="testimonial-prev" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 md:-translate-x-16 lg:-translate-x-20 p-3 hover:opacity-60 transition-opacity hidden sm:block" aria-label="Previous testimonial">
				<i data-lucide="chevron-left" class="w-6 h-6"></i>
			</button>

			<button id="testimonial-next" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 md:translate-x-16 lg:translate-x-20 p-3 hover:opacity-60 transition-opacity hidden sm:block" aria-label="Next testimonial">
				<i data-lucide="chevron-right" class="w-6 h-6"></i>
			</button>

			<div id="testimonial-slider" class="py-12">
			</div>

			<div id="testimonial-dots" class="flex justify-center gap-5 sm:gap-2 mt-8">
			</div>
		</div>
	</div>
</section>

<section id="contact" class="px-8 md:px-16 lg:px-24 mb-32 md:mb-48">
	<div class="max-w-[1400px] mx-auto">
		<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
			<div class="lg:col-span-7">
				<h2 class="text-[32px] md:text-[44px] lg:text-[54px] leading-[1.15] font-bold tracking-[-0.01em] text-black font-condensed">
					Parlons De Votre Projet
				</h2>
			</div>

			<div class="lg:col-span-5 lg:pt-4">
				<div class="mb-10 space-y-3 text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a]">
					<p>Un concept, une question, un projet en tête ?</p>
					<p>Passons de l'idée à l'action : réalisons ensemble votre prochaine vidéo.</p>
				</div>

				<div class="space-y-4 text-[16px]">
					<div>
						<a href="#" class="inline-block px-7 py-3 bg-black text-white text-[15px] hover:bg-accent-red transition-all duration-300 rounded-full">
							Planifier un appel gratuit
						</a>
					</div>
					<div>
						<a href="#" class="inline-block px-7 py-3 border-2 border-black text-black text-[15px] hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full">
							Demander une soumission
						</a>
					</div>
					<div>
						<a href="#" class="inline-block px-7 py-3 border-2 border-black text-black text-[15px] hover:bg-accent-red hover:text-white hover:border-accent-red transition-all duration-300 rounded-full">
							WhatsApp
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
