<?php
/**
 * Hero Section Slider for WordPress
 * Mobile-friendly and responsive slider component
 * 
 * @package HeroSlider
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class HeroSlider {
    
    private $slider_id;
    private $slides;
    private $options;
    
    public function __construct($slider_id = 'hero-slider', $slides = array(), $options = array()) {
        $this->slider_id = $slider_id;
        $this->slides = $slides;
        $this->options = wp_parse_args($options, $this->default_options());
        
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    /**
     * Default slider options
     */
    private function default_options() {
        return array(
            'autoplay' => true,
            'autoplay_delay' => 5000,
            'show_arrows' => true,
            'show_dots' => true,
            'transition_speed' => 600,
            'height' => '100vh',
            'mobile_height' => '70vh',
            'overlay_opacity' => 0.3,
            'enable_touch' => true
        );
    }
    
    /**
     * Enqueue necessary scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'hero-slider-css',
            plugins_url('css/hero-slider.css', __FILE__),
            array(),
            '1.0.0'
        );
        
        wp_enqueue_script(
            'hero-slider-js',
            plugins_url('js/hero-slider.js', __FILE__),
            array('jquery'),
            '1.0.0',
            true
        );
        
        // Pass options to JavaScript
        wp_localize_script('hero-slider-js', 'heroSliderOptions', $this->options);
    }
    
    /**
     * Render the hero slider
     */
    public function render() {
        if (empty($this->slides)) {
            return;
        }
        
        ob_start();
        ?>
        <section id="<?php echo esc_attr($this->slider_id); ?>" class="hero-slider-container" data-options='<?php echo json_encode($this->options); ?>'>
            <div class="hero-slider-wrapper">
                <div class="hero-slides">
                    <?php foreach ($this->slides as $index => $slide): ?>
                        <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" 
                             data-bg-image="<?php echo esc_url($slide['background_image']); ?>">
                            
                            <!-- Background overlay -->
                            <div class="slide-overlay" style="opacity: <?php echo esc_attr($this->options['overlay_opacity']); ?>;"></div>
                            
                            <!-- Slide content -->
                            <div class="slide-content">
                                <div class="container">
                                    <div class="slide-text">
                                        <?php if (!empty($slide['title'])): ?>
                                            <h1 class="slide-title"><?php echo wp_kses_post($slide['title']); ?></h1>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($slide['subtitle'])): ?>
                                            <h2 class="slide-subtitle"><?php echo wp_kses_post($slide['subtitle']); ?></h2>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($slide['description'])): ?>
                                            <p class="slide-description"><?php echo wp_kses_post($slide['description']); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($slide['button_text']) && !empty($slide['button_url'])): ?>
                                            <div class="slide-buttons">
                                                <a href="<?php echo esc_url($slide['button_url']); ?>" 
                                                   class="btn btn-primary slide-btn">
                                                    <?php echo esc_html($slide['button_text']); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (!empty($slide['featured_image'])): ?>
                                        <div class="slide-image">
                                            <img src="<?php echo esc_url($slide['featured_image']); ?>" 
                                                 alt="<?php echo esc_attr($slide['title']); ?>" 
                                                 class="featured-img">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Navigation arrows -->
                <?php if ($this->options['show_arrows'] && count($this->slides) > 1): ?>
                    <div class="slider-navigation">
                        <button class="nav-arrow prev-arrow" aria-label="Previous slide">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button class="nav-arrow next-arrow" aria-label="Next slide">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                <?php endif; ?>
                
                <!-- Pagination dots -->
                <?php if ($this->options['show_dots'] && count($this->slides) > 1): ?>
                    <div class="slider-pagination">
                        <?php foreach ($this->slides as $index => $slide): ?>
                            <button class="pagination-dot <?php echo $index === 0 ? 'active' : ''; ?>" 
                                    data-slide="<?php echo $index; ?>" 
                                    aria-label="Go to slide <?php echo $index + 1; ?>">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Add a slide to the slider
     */
    public function add_slide($slide_data) {
        $this->slides[] = $slide_data;
    }
    
    /**
     * Set multiple slides at once
     */
    public function set_slides($slides) {
        $this->slides = $slides;
    }
}

/**
 * Helper function to create and display hero slider
 */
function display_hero_slider($slides = array(), $options = array()) {
    $slider = new HeroSlider('hero-slider', $slides, $options);
    echo $slider->render();
}

/**
 * Example usage function - can be called from theme templates
 */
function example_hero_slider() {
    $slides = array(
        array(
            'title' => 'Exquisite Wedding Cakes',
            'subtitle' => 'Creating Sweet Memories',
            'description' => 'Handcrafted with love and attention to detail, our wedding cakes are the perfect centerpiece for your special day.',
            'background_image' => get_template_directory_uri() . '/assets/images/wedding-cake-1.jpg',
            'featured_image' => get_template_directory_uri() . '/assets/images/cake-featured-1.png',
            'button_text' => 'View Our Gallery',
            'button_url' => '#gallery'
        ),
        array(
            'title' => 'Custom Designs',
            'subtitle' => 'Your Dream Cake Awaits',
            'description' => 'From elegant tiered cakes to modern artistic designs, we bring your vision to life with premium ingredients.',
            'background_image' => get_template_directory_uri() . '/assets/images/wedding-cake-2.jpg',
            'featured_image' => get_template_directory_uri() . '/assets/images/cake-featured-2.png',
            'button_text' => 'Get Quote',
            'button_url' => '#contact'
        ),
        array(
            'title' => 'Premium Quality',
            'subtitle' => 'Taste the Difference',
            'description' => 'Using only the finest ingredients and traditional techniques to create unforgettable flavors.',
            'background_image' => get_template_directory_uri() . '/assets/images/wedding-cake-3.jpg',
            'featured_image' => get_template_directory_uri() . '/assets/images/cake-featured-3.png',
            'button_text' => 'Order Now',
            'button_url' => '#order'
        )
    );
    
    $options = array(
        'autoplay' => true,
        'autoplay_delay' => 6000,
        'height' => '100vh',
        'mobile_height' => '80vh',
        'overlay_opacity' => 0.4
    );
    
    display_hero_slider($slides, $options);
}
?>