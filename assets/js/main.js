// Main JavaScript for Trépied website

const contentData = {
    projects: [
        {
            id: 1,
            title: 'Campagne de recrutement',
            client: 'PME locale — Montréal',
            shortDescription: "Attirer de nouveaux talents en montrant le quotidien réel de l'équipe. Production, réalisation et montage.",
            description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.',
            image: 'https://images.unsplash.com/photo-1744339699989-550c61f3ecb8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxNb250cmVhbCUyMHVyYmFuJTIwY3JlYXRpdmV8ZW58MXx8fHwxNzcwNTMzNzcxfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
            videoUrl: 'https://www.youtube.com/embed/kcfs1-ryKWE'
        },
        {
            id: 2,
            title: 'Série de clips musicaux',
            client: 'Artiste indépendant',
            shortDescription: "Créer un univers visuel cohérent et sensible, fidèle à l'identité artistique.",
            description: 'Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
            image: 'https://images.unsplash.com/photo-1582474277699-0deef526f592?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxNb250cmVhbCUyMGNpdHlzY2FwZSUyMGFyY2hpdGVjdHVyZXxlbnwxfHx8fDE3NzA1MzM3NzJ8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
            videoUrl: 'https://www.youtube.com/embed/kcfs1-ryKWE'
        },
        {
            id: 3,
            title: 'Documentaire court',
            client: 'Organisme culturel',
            shortDescription: 'Mettre en lumière un projet à impact social, avec un regard humain et accessible.',
            description: 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo.',
            image: `https://images.unsplash.com/photo-1654000680055-a6c0dbe25c7b?q=80&w=2069&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D`,
            videoUrl: 'https://www.youtube.com/embed/kcfs1-ryKWE'
        }
    ],
    testimonials: [
        {
            id: 1,
            quote: "Trépied a transformé notre message confus en une vidéo claire et percutante. L'équipe nous a écoutés vraiment.",
            name: 'Marie Gagnon',
            role: 'Directrice, PME Montréal'
        },
        {
            id: 2,
            quote: "Une collaboration fluide et humaine. Ils ont capté l'essence de notre projet culturel avec une sensibilité rare.",
            name: 'David Lemieux',
            role: 'Producteur, Festival de musique'
        },
        {
            id: 3,
            quote: "Professionnels, créatifs et à l'écoute. Le résultat a dépassé nos attentes et notre vidéo fait maintenant partie intégrante de notre stratégie.",
            name: 'Sophie Tremblay',
            role: 'Directrice marketing, Organisme culturel'
        },
        {
            id: 4,
            quote: 'Trépied comprend rapidement les enjeux et propose des solutions adaptées. Un vrai partenaire de confiance pour nos contenus vidéo.',
            name: 'Jean-François Roy',
            role: 'Fondateur, Startup tech Montréal'
        }
    ]
};

const recreateIcons = () => {
    if (window.lucide && typeof window.lucide.createIcons === 'function') {
        window.lucide.createIcons();
    }
};

const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
const mobileMenu = document.getElementById('mobile-menu');
const mobileMenuLinks = document.querySelectorAll('.mobile-menu-link');

if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        const icon = mobileMenuToggle.querySelector('[data-lucide]');
        if (icon) {
            icon.setAttribute('data-lucide', mobileMenu.classList.contains('active') ? 'x' : 'menu');
            recreateIcons();
        }
    });
}

mobileMenuLinks.forEach((link) => {
    link.addEventListener('click', () => {
        if (!mobileMenu) {
            return;
        }
        mobileMenu.classList.remove('active');
        if (mobileMenuToggle) {
            const icon = mobileMenuToggle.querySelector('[data-lucide]');
            if (icon) {
                icon.setAttribute('data-lucide', 'menu');
            }
        }
        recreateIcons();
    });
});

let currentProjects = contentData.projects;

function renderProjects() {
    const container = document.getElementById('projects-container');
    if (!container) {
        return;
    }

    container.innerHTML = currentProjects.map((project) => `
        <div>
            <img src="${project.image}" alt="" class="w-full h-[360px] md:h-[520px] object-cover rounded-2xl mb-8">
            <div class="max-w-[680px]">
                <h3 class="text-[24px] md:text-[28px] leading-[1.3] font-medium mb-3">
                    ${project.title}
                </h3>
                <p class="text-[16px] md:text-[17px] font-medium mb-2 text-[#1a1a1a]">
                    ${project.client}
                </p>
                <p class="text-[17px] md:text-[18px] leading-[1.6] text-[#4a4a4a] mb-6">
                    ${project.shortDescription}
                </p>
                <a href="#" onclick="openProjectModal(${project.id}); return false;" class="inline-block text-[15px] text-[#1a1a1a] hover:text-[#ff0000] transition-all underline underline-offset-4 font-bold">
                    Voir projet →
                </a>
            </div>
        </div>
    `).join('');
}

function openProjectModal(projectId) {
    const project = currentProjects.find((item) => item.id === projectId);
    if (!project) {
        return;
    }

    const modal = document.getElementById('project-modal');
    const modalContent = document.getElementById('modal-content');

    if (!modal || !modalContent) {
        return;
    }

    modalContent.innerHTML = `
        
        <div class="p-6 md:p-6">

        <div class="relative w-full aspect-video rounded-2xl overflow-hidden">
            <iframe src="${project.videoUrl}" title="${project.title}" class="absolute inset-0 w-full h-full" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>


            <h3 class="text-[28px] md:text-[30px] leading-[1.2] font-bold mb-3 mt-4 text-black font-condensed">
                ${project.title}
            </h3>
            <p class="text-[17px] md:text-[17px] font-bold mb-3 text-[#1a1a1a]">
                ${project.client}
            </p>
            <p class="text-[16px] md:text-[17px] leading-[1.7] text-[#4a4a4a]">
                ${project.description}
            </p>
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('show');
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';

    recreateIcons();
}

function closeProjectModal() {
    const modal = document.getElementById('project-modal');
    if (!modal) {
        return;
    }

    modal.classList.add('hidden');
    modal.classList.remove('show');
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
}

const modalCloseBtn = document.getElementById('modal-close');
if (modalCloseBtn) {
    modalCloseBtn.addEventListener('click', closeProjectModal);
}

const projectModal = document.getElementById('project-modal');
if (projectModal) {
    projectModal.addEventListener('click', (event) => {
        if (event.target === projectModal) {
            closeProjectModal();
        }
    });
}

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeProjectModal();
    }
});

let currentTestimonials = contentData.testimonials;
let currentTestimonialIndex = 0;
let touchStartX = 0;
let touchEndX = 0;

function renderTestimonials() {
    const slider = document.getElementById('testimonial-slider');
    if (!slider) {
        return;
    }

    slider.innerHTML = currentTestimonials.map((testimonial, index) => `
        <div class="testimonial-item ${index === 0 ? 'active' : ''}" data-index="${index}">
            <p class="text-[24px] md:text-[28px] lg:text-[32px] leading-[1.4] font-medium mb-12 text-[#1a1a1a]">
                ${testimonial.quote}
            </p>
            <div class="text-[16px] md:text-[17px]">
                <p class="font-medium text-[#1a1a1a]">${testimonial.name}</p>
                <p class="text-[#6a6a6a]">${testimonial.role}</p>
            </div>
        </div>
    `).join('');
}

function createTestimonialDots() {
    const dotsContainer = document.getElementById('testimonial-dots');
    if (!dotsContainer) {
        return;
    }

    dotsContainer.innerHTML = currentTestimonials.map((_, index) => `
        <button onclick="goToTestimonial(${index})" class="testimonial-dot w-3 h-3 sm:w-2 sm:h-2 rounded-full transition-all duration-300 ${index === 0 ? 'active bg-[#1a1a1a]' : 'bg-[#d0d0d0]'}" aria-label="Testimonial ${index + 1}"></button>
    `).join('');
}

function showTestimonial(index) {
    const testimonials = document.querySelectorAll('.testimonial-item');
    const dots = document.querySelectorAll('.testimonial-dot');

    testimonials.forEach((testimonial, itemIndex) => {
        if (itemIndex === index) {
            testimonial.classList.add('active');
        } else {
            testimonial.classList.remove('active');
        }
    });

    dots.forEach((dot, dotIndex) => {
        if (dotIndex === index) {
            dot.classList.add('active', 'bg-[#1a1a1a]');
            dot.classList.remove('bg-[#d0d0d0]');
        } else {
            dot.classList.remove('active', 'bg-[#1a1a1a]');
            dot.classList.add('bg-[#d0d0d0]');
        }
    });

    currentTestimonialIndex = index;
}

function goToTestimonial(index) {
    showTestimonial(index);
}

function nextTestimonial() {
    const nextIndex = (currentTestimonialIndex + 1) % currentTestimonials.length;
    showTestimonial(nextIndex);
}

function previousTestimonial() {
    const prevIndex = currentTestimonialIndex === 0 ? currentTestimonials.length - 1 : currentTestimonialIndex - 1;
    showTestimonial(prevIndex);
}

const testimonialPrevBtn = document.getElementById('testimonial-prev');
const testimonialNextBtn = document.getElementById('testimonial-next');

if (testimonialPrevBtn) {
    testimonialPrevBtn.addEventListener('click', previousTestimonial);
}

if (testimonialNextBtn) {
    testimonialNextBtn.addEventListener('click', nextTestimonial);
}

const testimonialSlider = document.getElementById('testimonial-slider');

if (testimonialSlider) {
    testimonialSlider.addEventListener('touchstart', (event) => {
        touchStartX = event.changedTouches[0].screenX;
    });

    testimonialSlider.addEventListener('touchend', (event) => {
        touchEndX = event.changedTouches[0].screenX;
        handleSwipe();
    });
}

function handleSwipe() {
    const minSwipeDistance = 50;
    const distance = touchStartX - touchEndX;

    if (distance > minSwipeDistance) {
        nextTestimonial();
    } else if (distance < -minSwipeDistance) {
        previousTestimonial();
    }
}

document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (event) {
        const href = this.getAttribute('href');
        if (href === '#' || href === '') {
            return;
        }

        event.preventDefault();
        const target = document.querySelector(href);
        if (target) {
            const offsetTop = target.offsetTop - 80;
            window.scrollTo({
                top: offsetTop,
                behavior: 'smooth'
            });
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    renderProjects();
    renderTestimonials();
    createTestimonialDots();
    recreateIcons();
});

window.openProjectModal = openProjectModal;
window.goToTestimonial = goToTestimonial;
