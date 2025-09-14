/**
 * Hero Slider JavaScript
 * Mobile-friendly with touch/swipe support
 * Compatible with all modern browsers
 */

(function($) {
    'use strict';
    
    class HeroSlider {
        constructor(element, options = {}) {
            this.slider = $(element);
            this.slides = this.slider.find('.hero-slide');
            this.currentSlide = 0;
            this.totalSlides = this.slides.length;
            this.isTransitioning = false;
            this.autoplayTimer = null;
            this.touchStartX = 0;
            this.touchEndX = 0;
            
            // Default options
            this.options = $.extend({
                autoplay: true,
                autoplay_delay: 5000,
                show_arrows: true,
                show_dots: true,
                transition_speed: 600,
                enable_touch: true,
                swipe_threshold: 50
            }, options);
            
            this.init();
        }
        
        init() {
            if (this.totalSlides <= 1) return;
            
            this.setupBackgroundImages();
            this.bindEvents();
            this.setupAccessibility();
            
            if (this.options.autoplay) {
                this.startAutoplay();
            }
            
            // Initialize first slide
            this.goToSlide(0, false);
            
            // Mark slider as initialized
            this.slider.addClass('initialized');
        }
        
        setupBackgroundImages() {
            this.slides.each((index, slide) => {
                const $slide = $(slide);
                const bgImage = $slide.data('bg-image');
                
                if (bgImage) {
                    // Preload image
                    const img = new Image();
                    img.onload = () => {
                        $slide.css('background-image', `url(${bgImage})`);
                        $slide.removeClass('loading');
                    };
                    img.onerror = () => {
                        $slide.addClass('error');
                    };
                    $slide.addClass('loading');
                    img.src = bgImage;
                }
            });
        }
        
        bindEvents() {
            // Navigation arrows
            if (this.options.show_arrows) {
                this.slider.on('click', '.prev-arrow', (e) => {
                    e.preventDefault();
                    this.prevSlide();
                });
                
                this.slider.on('click', '.next-arrow', (e) => {
                    e.preventDefault();
                    this.nextSlide();
                });
            }
            
            // Pagination dots
            if (this.options.show_dots) {
                this.slider.on('click', '.pagination-dot', (e) => {
                    e.preventDefault();
                    const slideIndex = parseInt($(e.target).data('slide'));
                    this.goToSlide(slideIndex);
                });
            }
            
            // Touch/swipe events
            if (this.options.enable_touch) {
                this.setupTouchEvents();
            }
            
            // Keyboard navigation
            $(document).on('keydown', (e) => {
                if (!this.slider.is(':visible')) return;
                
                switch(e.keyCode) {
                    case 37: // Left arrow
                        e.preventDefault();
                        this.prevSlide();
                        break;
                    case 39: // Right arrow
                        e.preventDefault();
                        this.nextSlide();
                        break;
                }
            });
            
            // Pause on hover
            this.slider.on('mouseenter', () => {
                this.stopAutoplay();
            }).on('mouseleave', () => {
                if (this.options.autoplay) {
                    this.startAutoplay();
                }
            });
            
            // Handle visibility change
            $(document).on('visibilitychange', () => {
                if (document.hidden) {
                    this.stopAutoplay();
                } else if (this.options.autoplay) {
                    this.startAutoplay();
                }
            });
            
            // Window resize handler
            $(window).on('resize', this.debounce(() => {
                this.handleResize();
            }, 250));
        }
        
        setupTouchEvents() {
            const sliderElement = this.slider[0];
            
            // Touch start
            sliderElement.addEventListener('touchstart', (e) => {
                this.touchStartX = e.changedTouches[0].screenX;
                this.stopAutoplay();
            }, { passive: true });
            
            // Touch end
            sliderElement.addEventListener('touchend', (e) => {
                this.touchEndX = e.changedTouches[0].screenX;
                this.handleSwipe();
                
                if (this.options.autoplay) {
                    this.startAutoplay();
                }
            }, { passive: true });
            
            // Prevent default touch behavior on slider
            sliderElement.addEventListener('touchmove', (e) => {
                e.preventDefault();
            }, { passive: false });
        }
        
        handleSwipe() {
            const swipeDistance = this.touchEndX - this.touchStartX;
            
            if (Math.abs(swipeDistance) > this.options.swipe_threshold) {
                if (swipeDistance > 0) {
                    this.prevSlide();
                } else {
                    this.nextSlide();
                }
            }
        }
        
        setupAccessibility() {
            // Add ARIA attributes
            this.slider.attr({
                'role': 'region',
                'aria-label': 'Hero Image Slider'
            });
            
            this.slides.each((index, slide) => {
                $(slide).attr({
                    'role': 'tabpanel',
                    'aria-hidden': index !== 0,
                    'aria-label': `Slide ${index + 1} of ${this.totalSlides}`
                });
            });
            
            // Update dot accessibility
            this.slider.find('.pagination-dot').each((index, dot) => {
                $(dot).attr({
                    'role': 'tab',
                    'aria-selected': index === 0,
                    'tabindex': index === 0 ? '0' : '-1'
                });
            });
        }
        
        nextSlide() {
            const nextIndex = (this.currentSlide + 1) % this.totalSlides;
            this.goToSlide(nextIndex);
        }
        
        prevSlide() {
            const prevIndex = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
            this.goToSlide(prevIndex);
        }
        
        goToSlide(index, animate = true) {
            if (index === this.currentSlide || this.isTransitioning) return;
            
            this.isTransitioning = true;
            
            const $currentSlide = $(this.slides[this.currentSlide]);
            const $nextSlide = $(this.slides[index]);
            
            // Update accessibility attributes
            this.slides.attr('aria-hidden', 'true');
            $nextSlide.attr('aria-hidden', 'false');
            
            if (animate) {
                // Animate transition
                $currentSlide.removeClass('active').addClass('fade-out');
                $nextSlide.addClass('fade-in');
                
                setTimeout(() => {
                    $currentSlide.removeClass('fade-out');
                    $nextSlide.removeClass('fade-in').addClass('active');
                    this.isTransitioning = false;
                }, this.options.transition_speed);
            } else {
                // Instant change
                this.slides.removeClass('active');
                $nextSlide.addClass('active');
                this.isTransitioning = false;
            }
            
            // Update current slide
            this.currentSlide = index;
            
            // Update pagination dots
            this.updatePaginationDots();
            
            // Trigger custom event
            this.slider.trigger('slideChange', [index, $nextSlide]);
        }
        
        updatePaginationDots() {
            const dots = this.slider.find('.pagination-dot');
            dots.removeClass('active').attr({
                'aria-selected': 'false',
                'tabindex': '-1'
            });
            
            const activeDot = dots.eq(this.currentSlide);
            activeDot.addClass('active').attr({
                'aria-selected': 'true',
                'tabindex': '0'
            });
        }
        
        startAutoplay() {
            this.stopAutoplay();
            if (this.totalSlides > 1) {
                this.autoplayTimer = setInterval(() => {
                    this.nextSlide();
                }, this.options.autoplay_delay);
            }
        }
        
        stopAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        }
        
        handleResize() {
            // Recalculate dimensions if needed
            this.slides.each((index, slide) => {
                const $slide = $(slide);
                if ($slide.hasClass('active')) {
                    // Force repaint
                    $slide.hide().show(0);
                }
            });
        }
        
        // Utility function for debouncing
        debounce(func, wait, immediate) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    timeout = null;
                    if (!immediate) func.apply(this, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(this, args);
            };
        }
        
        // Public methods for external control
        destroy() {
            this.stopAutoplay();
            this.slider.off('.heroSlider');
            $(document).off('.heroSlider');
            $(window).off('.heroSlider');
            this.slider.removeData('heroSlider');
        }
        
        pause() {
            this.stopAutoplay();
        }
        
        play() {
            if (this.options.autoplay) {
                this.startAutoplay();
            }
        }
        
        getCurrentSlide() {
            return this.currentSlide;
        }
        
        getTotalSlides() {
            return this.totalSlides;
        }
    }
    
    // jQuery plugin wrapper
    $.fn.heroSlider = function(options) {
        return this.each(function() {
            if (!$(this).data('heroSlider')) {
                $(this).data('heroSlider', new HeroSlider(this, options));
            }
        });
    };
    
    // Auto-initialize sliders
    $(document).ready(function() {
        $('.hero-slider-container').each(function() {
            const $slider = $(this);
            const options = $slider.data('options') || {};
            
            // Merge with global options if available
            if (typeof window.heroSliderOptions !== 'undefined') {
                $.extend(options, window.heroSliderOptions);
            }
            
            $slider.heroSlider(options);
        });
        
        // Add loading complete class
        setTimeout(() => {
            $('.hero-slider-container').addClass('loaded');
        }, 100);
    });
    
    // Intersection Observer for performance optimization
    if ('IntersectionObserver' in window) {
        const sliderObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const slider = $(entry.target).data('heroSlider');
                if (slider) {
                    if (entry.isIntersecting) {
                        slider.play();
                    } else {
                        slider.pause();
                    }
                }
            });
        }, {
            threshold: 0.1
        });
        
        $(document).ready(() => {
            $('.hero-slider-container').each((index, element) => {
                sliderObserver.observe(element);
            });
        });
    }
    
})(jQuery);

// Vanilla JavaScript fallback for non-jQuery environments
if (typeof jQuery === 'undefined') {
    console.warn('Hero Slider: jQuery is required for full functionality. Loading basic functionality only.');
    
    // Basic vanilla JS implementation
    document.addEventListener('DOMContentLoaded', function() {
        const sliders = document.querySelectorAll('.hero-slider-container');
        
        sliders.forEach(slider => {
            const slides = slider.querySelectorAll('.hero-slide');
            const nextBtn = slider.querySelector('.next-arrow');
            const prevBtn = slider.querySelector('.prev-arrow');
            const dots = slider.querySelectorAll('.pagination-dot');
            
            let currentSlide = 0;
            
            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });
                
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });
                
                currentSlide = index;
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    const next = (currentSlide + 1) % slides.length;
                    showSlide(next);
                });
            }
            
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    const prev = (currentSlide - 1 + slides.length) % slides.length;
                    showSlide(prev);
                });
            }
            
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSlide(index);
                });
            });
            
            // Auto-play
            setInterval(() => {
                const next = (currentSlide + 1) % slides.length;
                showSlide(next);
            }, 5000);
        });
    });
}