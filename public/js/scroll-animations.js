// Scroll Animation System for UPTREND LMS
(function() {
    'use strict';

    const config = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    // Add animation classes to elements
    function initScrollAnimations() {
        const elements = document.querySelectorAll(`
            .card, .bg-white, .bg-gradient-to-r, .bg-gradient-to-br,
            table, form, .stat-card, .loan-card, .application-card,
            .grid > div, .space-y-6 > div, .space-y-4 > div,
            [class*="rounded"], [class*="shadow"], [class*="border"]
        `);

        elements.forEach((el, index) => {
            if (!el.classList.contains('scroll-animate')) {
                el.classList.add('scroll-animate');
                el.style.setProperty('--animation-order', index % 20);
            }
        });
    }

    // Intersection Observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('scroll-visible');
                observer.unobserve(entry.target);
            }
        });
    }, config);

    // Observe elements
    function observeElements() {
        document.querySelectorAll('.scroll-animate:not(.scroll-visible)').forEach(el => {
            observer.observe(el);
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initScrollAnimations();
            observeElements();
        });
    } else {
        initScrollAnimations();
        observeElements();
    }

    // Re-observe on dynamic content
    const mutationObserver = new MutationObserver(() => {
        initScrollAnimations();
        observeElements();
    });

    mutationObserver.observe(document.body, {
        childList: true,
        subtree: true
    });
})();
