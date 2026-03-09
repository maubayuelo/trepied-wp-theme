// Main JavaScript for Trépied website

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

// Project modal: reads data from DOM attributes
function openProjectModal(projectCard) {
    const modal = document.getElementById('project-modal');
    const modalContent = document.getElementById('modal-content');

    if (!modal || !modalContent || !projectCard) {
        return;
    }

    const title = projectCard.dataset.projectTitle || '';
    const client = projectCard.dataset.projectClient || '';
    const videoId = projectCard.dataset.projectVideo || '';
    const longDescriptionTemplate = projectCard.querySelector('.project-long-description');
    const longDescription = longDescriptionTemplate ? longDescriptionTemplate.innerHTML : '';

    const videoUrl = videoId ? `https://www.youtube.com/embed/${videoId}` : '';

    modalContent.innerHTML = `
        <div class="p-6 md:p-6">
            ${videoUrl ? `
            <div class="relative w-full aspect-video rounded-2xl overflow-hidden">
                <iframe src="${videoUrl}" title="${title}" class="absolute inset-0 w-full h-full" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            ` : ''}
            <h3 class="text-[28px] md:text-[30px] leading-[1.2] font-bold mb-3 mt-4 text-black font-condensed">
                ${title}
            </h3>
            ${client ? `<p class="text-[17px] md:text-[17px] font-bold mb-3 text-[#1a1a1a]">${client}</p>` : ''}
            ${longDescription ? `<div class="text-[16px] md:text-[17px] leading-[1.7] text-[#4a4a4a]">${longDescription}</div>` : ''}
        </div>
    `;

    modal.classList.remove('hidden');
    modal.classList.add('show');
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';

    recreateIcons();
}

// Set up project modal triggers
document.querySelectorAll('.project-modal-trigger').forEach((trigger) => {
    trigger.addEventListener('click', (event) => {
        event.preventDefault();
        const projectCard = trigger.closest('.project-card');
        if (projectCard) {
            openProjectModal(projectCard);
        }
    });
});

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
        closeQuoteModal();
    }
});

// Quote Modal Functions
function openQuoteModal() {
    const modal = document.getElementById('quote-modal');
    if (!modal) {
        return;
    }

    modal.classList.remove('hidden');
    document.body.classList.add('modal-open');
    document.body.style.overflow = 'hidden';

    // Close mobile menu if open
    if (mobileMenu && mobileMenu.classList.contains('active')) {
        mobileMenu.classList.remove('active');
        if (mobileMenuToggle) {
            const icon = mobileMenuToggle.querySelector('[data-lucide]');
            if (icon) {
                icon.setAttribute('data-lucide', 'menu');
            }
        }
    }

    recreateIcons();
}

function closeQuoteModal() {
    const modal = document.getElementById('quote-modal');
    if (!modal) {
        return;
    }

    modal.classList.add('hidden');
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
}

// Quote modal triggers
document.querySelectorAll('.quote-modal-trigger').forEach((trigger) => {
    trigger.addEventListener('click', (event) => {
        event.preventDefault();
        openQuoteModal();
    });
});

// Quote modal close button
const quoteModalCloseBtn = document.getElementById('quote-modal-close');
if (quoteModalCloseBtn) {
    quoteModalCloseBtn.addEventListener('click', closeQuoteModal);
}

// Quote modal backdrop click
const quoteModal = document.getElementById('quote-modal');
if (quoteModal) {
    quoteModal.addEventListener('click', (event) => {
        if (event.target === quoteModal) {
            closeQuoteModal();
        }
    });
}

let currentTestimonialIndex = 0;
let touchStartX = 0;
let touchEndX = 0;

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
    const testimonials = document.querySelectorAll('.testimonial-item');
    if (testimonials.length === 0) return;
    const nextIndex = (currentTestimonialIndex + 1) % testimonials.length;
    showTestimonial(nextIndex);
}

function previousTestimonial() {
    const testimonials = document.querySelectorAll('.testimonial-item');
    if (testimonials.length === 0) return;
    const prevIndex = currentTestimonialIndex === 0 ? testimonials.length - 1 : currentTestimonialIndex - 1;
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
    // Set up testimonial dot click handlers
    document.querySelectorAll('.testimonial-dot').forEach((dot) => {
        dot.addEventListener('click', () => {
            const index = parseInt(dot.dataset.index, 10);
            if (!isNaN(index)) {
                goToTestimonial(index);
            }
        });
    });
    recreateIcons();
    
    // Initialize quote request form handler
    initQuoteForm();
    
    // Initialize Go to Top button
    initGoToTop();
});

// Quote Request Form Handler
function initQuoteForm() {
    const form = document.getElementById('quote-request-form');
    if (!form) {
        return;
    }

    const messagesContainer = document.getElementById('quote-form-messages');
    const messageContent = messagesContainer?.querySelector('[data-message-content]');
    const submitBtn = form.querySelector('[data-form-submit-btn]');
    const btnText = submitBtn?.querySelector('[data-btn-text]');
    const btnLoading = submitBtn?.querySelector('[data-btn-loading]');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        // Check if trepiedForms is available
        if (typeof window.trepiedForms === 'undefined') {
            console.error('Form configuration not loaded');
            return;
        }

        // Clear previous messages
        hideFormMessage();
        clearFieldErrors();

        // Disable submit button and show loading state
        setFormLoading(true);

        // Collect form data
        const formData = new FormData(form);
        formData.append('action', 'trepied_quote_request');
        formData.append('nonce', window.trepiedForms.nonce);
        formData.append('page_url', window.location.href);

        try {
            const response = await fetch(window.trepiedForms.ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
            });

            const result = await response.json();

            if (result.success) {
                showFormMessage(result.data.message, 'success');
                form.reset();
                
                // Track successful submission (for analytics)
                if (typeof window.dataLayer !== 'undefined') {
                    window.dataLayer.push({
                        event: 'quote_form_submit',
                        form_name: 'quote_request',
                    });
                }
            } else {
                const errorMessage = result.data?.message || window.trepiedForms.i18n.error;
                showFormMessage(errorMessage, 'error');
                
                // Show field-specific errors if available
                if (result.data?.errors) {
                    showFieldErrors(result.data.errors);
                }
            }
        } catch (error) {
            console.error('Form submission error:', error);
            showFormMessage(window.trepiedForms.i18n.error, 'error');
        } finally {
            setFormLoading(false);
        }
    });

    function setFormLoading(isLoading) {
        if (submitBtn) {
            submitBtn.disabled = isLoading;
        }
        if (btnText) {
            btnText.classList.toggle('hidden', isLoading);
        }
        if (btnLoading) {
            btnLoading.classList.toggle('hidden', !isLoading);
        }
    }

    function showFormMessage(message, type) {
        if (!messagesContainer || !messageContent) {
            return;
        }

        messageContent.textContent = message;
        messageContent.className = 'p-4 rounded-lg text-[15px]';
        
        if (type === 'success') {
            messageContent.classList.add('bg-green-100', 'text-green-800', 'border', 'border-green-200');
        } else {
            messageContent.classList.add('bg-red-100', 'text-red-800', 'border', 'border-red-200');
        }

        messagesContainer.classList.remove('hidden');
        
        // Scroll to message
        messagesContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideFormMessage() {
        if (messagesContainer) {
            messagesContainer.classList.add('hidden');
        }
    }

    function showFieldErrors(errors) {
        Object.entries(errors).forEach(([fieldName, errorMessage]) => {
            const field = form.querySelector(`[data-field="${fieldName}"]`);
            if (field) {
                field.classList.add('border-red-500');
                
                // Add error message below field
                const errorEl = document.createElement('p');
                errorEl.className = 'text-red-600 text-[13px] mt-1';
                errorEl.setAttribute('data-field-error', fieldName);
                errorEl.textContent = errorMessage;
                field.parentNode.appendChild(errorEl);
            }
        });
    }

    function clearFieldErrors() {
        // Remove error styling from fields
        form.querySelectorAll('.border-red-500').forEach((field) => {
            field.classList.remove('border-red-500');
        });
        
        // Remove error messages
        form.querySelectorAll('[data-field-error]').forEach((errorEl) => {
            errorEl.remove();
        });
    }
}

// Go to Top Button Handler
function initGoToTop() {
    const goToTopBtn = document.getElementById('go-to-top');
    if (!goToTopBtn) {
        return;
    }

    const scrollThreshold = 400;

    // Show/hide button based on scroll position
    function toggleGoToTopButton() {
        if (window.scrollY > scrollThreshold) {
            goToTopBtn.classList.remove('opacity-0', 'invisible', 'translate-y-4');
            goToTopBtn.classList.add('opacity-100', 'visible', 'translate-y-0');
        } else {
            goToTopBtn.classList.add('opacity-0', 'invisible', 'translate-y-4');
            goToTopBtn.classList.remove('opacity-100', 'visible', 'translate-y-0');
        }
    }

    // Scroll to top on click
    goToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Listen to scroll events with throttling
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                toggleGoToTopButton();
                ticking = false;
            });
            ticking = true;
        }
    });

    // Initial check
    toggleGoToTopButton();
}
