/**
 * Custom Streetwear - Main JavaScript
 * Vanilla JS - No frameworks
 */

document.addEventListener('DOMContentLoaded', function() {
    initMobileMenu();
    initHeroSlider();
    initScrollReveal();
    initBackToTop();
    initStickyHeader();
    initSmoothScroll();
    initProductGallery();
    initProductTabs();
    initCounters();
});

/* ========================================
   MOBILE MENU
   ======================================== */
function initMobileMenu() {
    // Menu toggle handled by inline onclick for simplicity
}

function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('mobileOverlay');
    const toggle = document.getElementById('mobileToggle');
    
    menu.classList.toggle('active');
    overlay.classList.toggle('active');
    toggle.classList.toggle('active');
    document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
}

function toggleMobileSubmenu(link) {
    const item = link.closest('.mobile-nav-item');
    const hasChildren = item.classList.contains('has-children');
    
    if (hasChildren) {
        item.classList.toggle('active');
    }
}

/* ========================================
   HERO SLIDER
   ======================================== */
let currentSlide = 0;
let slideInterval;

function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    if (slides.length === 0) return;
    
    showSlide(0);
    startAutoSlide();
}

function showSlide(index) {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    
    if (slides.length === 0) return;
    
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
    
    if (dots.length > 0) {
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    }
    
    currentSlide = index;
}

function nextSlide() {
    const slides = document.querySelectorAll('.hero-slide');
    const next = (currentSlide + 1) % slides.length;
    showSlide(next);
}

function prevSlide() {
    const slides = document.querySelectorAll('.hero-slide');
    const prev = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(prev);
}

function goToSlide(index) {
    showSlide(index);
    resetAutoSlide();
}

function startAutoSlide() {
    slideInterval = setInterval(nextSlide, 5000);
}

function resetAutoSlide() {
    clearInterval(slideInterval);
    startAutoSlide();
}

/* ========================================
   QUOTE MODAL
   ======================================== */
function openQuoteModal() {
    const modal = document.getElementById('quoteModal');
    const overlay = document.getElementById('quoteModalOverlay');
    
    modal.classList.add('active');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeQuoteModal() {
    const modal = document.getElementById('quoteModal');
    const overlay = document.getElementById('quoteModalOverlay');
    
    modal.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
    
    // Reset form
    const form = document.getElementById('quoteForm');
    const success = document.getElementById('quoteSuccess');
    if (form) form.style.display = 'block';
    if (success) success.style.display = 'none';
    if (form) form.reset();
}

function submitQuoteForm(event) {
    event.preventDefault();
    
    const form = event.target;
    const btn = document.getElementById('quoteSubmitBtn');
    const btnText = btn.querySelector('.btn-text');
    const btnLoader = btn.querySelector('.btn-loader');
    
    // Show loader
    btn.disabled = true;
    if (btnText) btnText.style.display = 'none';
    if (btnLoader) btnLoader.style.display = 'inline-flex';
    
    const formData = new FormData(form);
    
    fetch('/api/quote.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            form.style.display = 'none';
            document.getElementById('quoteSuccess').style.display = 'block';
        } else {
            alert(data.message || 'Something went wrong. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to submit. Please try again or contact us directly.');
    })
    .finally(() => {
        btn.disabled = false;
        if (btnText) btnText.style.display = '';
        if (btnLoader) btnLoader.style.display = 'none';
    });
    
    return false;
}

/* ========================================
   SCROLL REVEAL
   ======================================== */
function initScrollReveal() {
    const reveals = document.querySelectorAll('.reveal');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    reveals.forEach(el => observer.observe(el));
}

/* ========================================
   BACK TO TOP
   ======================================== */
function initBackToTop() {
    const btn = document.getElementById('backToTop');
    if (!btn) return;
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 500) {
            btn.classList.add('visible');
        } else {
            btn.classList.remove('visible');
        }
    }, { passive: true });
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ========================================
   STICKY HEADER
   ======================================== */
function initStickyHeader() {
    const header = document.getElementById('mainHeader');
    const topBar = document.getElementById('topBar');
    if (!header) return;
    
    let lastScroll = 0;
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.scrollY;
        
        if (currentScroll > 100) {
            header.style.boxShadow = '0 4px 30px rgba(0,0,0,0.3)';
        } else {
            header.style.boxShadow = 'none';
        }
        
        // Hide/show top bar
        if (topBar) {
            if (currentScroll > 50) {
                topBar.style.transform = 'translateY(-100%)';
                topBar.style.opacity = '0';
            } else {
                topBar.style.transform = 'translateY(0)';
                topBar.style.opacity = '1';
            }
        }
        
        lastScroll = currentScroll;
    }, { passive: true });
}

/* ========================================
   SMOOTH SCROLL
   ======================================== */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/* ========================================
   PRODUCT GALLERY
   ======================================== */
function initProductGallery() {
    const mainImage = document.getElementById('productMainImage');
    const thumbs = document.querySelectorAll('.gallery-thumb');
    
    if (!mainImage || thumbs.length === 0) return;
    
    thumbs.forEach(thumb => {
        thumb.addEventListener('click', function() {
            const img = this.querySelector('img');
            if (img) {
                mainImage.src = img.src;
                mainImage.alt = img.alt;
                
                thumbs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });
}

/* ========================================
   PRODUCT TABS
   ======================================== */
function initProductTabs() {
    const tabBtns = document.querySelectorAll('.product-tab-btn');
    const tabPanels = document.querySelectorAll('.product-tab-panel');
    
    if (tabBtns.length === 0) return;
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.dataset.tab;
            
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            tabPanels.forEach(p => {
                p.classList.toggle('active', p.id === target);
            });
        });
    });
}

/* ========================================
   ANIMATED COUNTERS
   ======================================== */
function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    if (counters.length === 0) return;
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => observer.observe(counter));
}

function animateCounter(element) {
    const target = parseInt(element.dataset.counter);
    const duration = 2000;
    const step = target / (duration / 16);
    let current = 0;
    
    const timer = setInterval(() => {
        current += step;
        if (current >= target) {
            element.textContent = target.toLocaleString();
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current).toLocaleString();
        }
    }, 16);
}

/* ========================================
   UTILITY FUNCTIONS
   ======================================== */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeQuoteModal();
        const mobileMenu = document.getElementById('mobileMenu');
        if (mobileMenu && mobileMenu.classList.contains('active')) {
            toggleMobileMenu();
        }
    }
});
