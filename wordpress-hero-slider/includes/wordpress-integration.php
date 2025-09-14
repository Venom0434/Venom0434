<?php
/**
 * WordPress Theme Integration Example
 * Add this to your theme's functions.php file
 */

// Include the hero slider class
require_once get_template_directory() . '/wordpress-hero-slider/hero-slider.php';

/**
 * Example function to display hero slider on homepage
 */
function theme_display_homepage_hero() {
    // Only show on homepage
    if (!is_front_page()) {
        return;
    }
    
    // Define slides with real content from WordPress
    $slides = array(
        array(
            'title' => get_theme_mod('hero_slide1_title', 'Exquisite Wedding Cakes'),
            'subtitle' => get_theme_mod('hero_slide1_subtitle', 'Creating Sweet Memories'),
            'description' => get_theme_mod('hero_slide1_desc', 'Handcrafted with love and attention to detail, our wedding cakes are the perfect centerpiece for your special day.'),
            'background_image' => get_theme_mod('hero_slide1_bg', get_template_directory_uri() . '/assets/images/wedding-cake-bg-1.jpg'),
            'featured_image' => get_theme_mod('hero_slide1_img', get_template_directory_uri() . '/assets/images/cake-featured-1.png'),
            'button_text' => get_theme_mod('hero_slide1_btn_text', 'View Gallery'),
            'button_url' => get_theme_mod('hero_slide1_btn_url', '#gallery')
        ),
        array(
            'title' => get_theme_mod('hero_slide2_title', 'Custom Designs'),
            'subtitle' => get_theme_mod('hero_slide2_subtitle', 'Your Dream Cake Awaits'),
            'description' => get_theme_mod('hero_slide2_desc', 'From elegant tiered cakes to modern artistic designs, we bring your vision to life with premium ingredients.'),
            'background_image' => get_theme_mod('hero_slide2_bg', get_template_directory_uri() . '/assets/images/wedding-cake-bg-2.jpg'),
            'featured_image' => get_theme_mod('hero_slide2_img', get_template_directory_uri() . '/assets/images/cake-featured-2.png'),
            'button_text' => get_theme_mod('hero_slide2_btn_text', 'Get Quote'),
            'button_url' => get_theme_mod('hero_slide2_btn_url', '#contact')
        ),
        array(
            'title' => get_theme_mod('hero_slide3_title', 'Premium Quality'),
            'subtitle' => get_theme_mod('hero_slide3_subtitle', 'Taste the Difference'),
            'description' => get_theme_mod('hero_slide3_desc', 'Using only the finest ingredients and traditional techniques to create unforgettable flavors.'),
            'background_image' => get_theme_mod('hero_slide3_bg', get_template_directory_uri() . '/assets/images/wedding-cake-bg-3.jpg'),
            'featured_image' => get_theme_mod('hero_slide3_img', get_template_directory_uri() . '/assets/images/cake-featured-3.png'),
            'button_text' => get_theme_mod('hero_slide3_btn_text', 'Order Now'),
            'button_url' => get_theme_mod('hero_slide3_btn_url', '#order')
        )
    );
    
    // Slider options
    $options = array(
        'autoplay' => get_theme_mod('hero_autoplay', true),
        'autoplay_delay' => get_theme_mod('hero_delay', 6000),
        'height' => get_theme_mod('hero_height', '100vh'),
        'mobile_height' => get_theme_mod('hero_mobile_height', '80vh'),
        'overlay_opacity' => get_theme_mod('hero_overlay_opacity', 0.4),
        'show_arrows' => get_theme_mod('hero_show_arrows', true),
        'show_dots' => get_theme_mod('hero_show_dots', true)
    );
    
    // Display the slider
    display_hero_slider($slides, $options);
}

// Hook to display hero on homepage
add_action('wp_head', 'theme_display_homepage_hero');

/**
 * Add theme customizer options for hero slider
 */
function theme_hero_slider_customizer($wp_customize) {
    
    // Add Hero Slider Section
    $wp_customize->add_section('hero_slider_section', array(
        'title' => __('Hero Slider', 'textdomain'),
        'priority' => 30,
    ));
    
    // Slider General Settings
    $wp_customize->add_setting('hero_autoplay', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('hero_autoplay', array(
        'label' => __('Enable Autoplay', 'textdomain'),
        'section' => 'hero_slider_section',
        'type' => 'checkbox',
    ));
    
    $wp_customize->add_setting('hero_delay', array(
        'default' => 6000,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('hero_delay', array(
        'label' => __('Autoplay Delay (ms)', 'textdomain'),
        'section' => 'hero_slider_section',
        'type' => 'number',
    ));
    
    // Slide 1 Settings
    for ($i = 1; $i <= 3; $i++) {
        // Title
        $wp_customize->add_setting("hero_slide{$i}_title", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control("hero_slide{$i}_title", array(
            'label' => sprintf(__('Slide %d Title', 'textdomain'), $i),
            'section' => 'hero_slider_section',
            'type' => 'text',
        ));
        
        // Subtitle
        $wp_customize->add_setting("hero_slide{$i}_subtitle", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control("hero_slide{$i}_subtitle", array(
            'label' => sprintf(__('Slide %d Subtitle', 'textdomain'), $i),
            'section' => 'hero_slider_section',
            'type' => 'text',
        ));
        
        // Description
        $wp_customize->add_setting("hero_slide{$i}_desc", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        
        $wp_customize->add_control("hero_slide{$i}_desc", array(
            'label' => sprintf(__('Slide %d Description', 'textdomain'), $i),
            'section' => 'hero_slider_section',
            'type' => 'textarea',
        ));
        
        // Background Image
        $wp_customize->add_setting("hero_slide{$i}_bg", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "hero_slide{$i}_bg", array(
            'label' => sprintf(__('Slide %d Background Image', 'textdomain'), $i),
            'section' => 'hero_slider_section',
        )));
        
        // Featured Image
        $wp_customize->add_setting("hero_slide{$i}_img", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "hero_slide{$i}_img", array(
            'label' => sprintf(__('Slide %d Featured Image', 'textdomain'), $i),
            'section' => 'hero_slider_section',
        )));
        
        // Button Text
        $wp_customize->add_setting("hero_slide{$i}_btn_text", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control("hero_slide{$i}_btn_text", array(
            'label' => sprintf(__('Slide %d Button Text', 'textdomain'), $i),
            'section' => 'hero_slider_section',
            'type' => 'text',
        ));
        
        // Button URL
        $wp_customize->add_setting("hero_slide{$i}_btn_url", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        $wp_customize->add_control("hero_slide{$i}_btn_url", array(
            'label' => sprintf(__('Slide %d Button URL', 'textdomain'), $i),
            'section' => 'hero_slider_section',
            'type' => 'url',
        ));
    }
}

add_action('customize_register', 'theme_hero_slider_customizer');

/**
 * Shortcode for hero slider
 * Usage: [hero_slider slides="3" autoplay="true"]
 */
function hero_slider_shortcode($atts) {
    $atts = shortcode_atts(array(
        'slides' => '3',
        'autoplay' => 'true',
        'delay' => '6000',
        'height' => '70vh',
        'show_arrows' => 'true',
        'show_dots' => 'true'
    ), $atts, 'hero_slider');
    
    // Convert string booleans to actual booleans
    $autoplay = ($atts['autoplay'] === 'true');
    $show_arrows = ($atts['show_arrows'] === 'true');
    $show_dots = ($atts['show_dots'] === 'true');
    
    // Default slides for shortcode
    $slides = array();
    for ($i = 1; $i <= intval($atts['slides']); $i++) {
        $slides[] = array(
            'title' => "Slide {$i} Title",
            'subtitle' => "Slide {$i} Subtitle",
            'description' => "This is the description for slide {$i}. Customize this content through the WordPress customizer.",
            'background_image' => get_template_directory_uri() . "/assets/images/slide-{$i}-bg.jpg",
            'featured_image' => get_template_directory_uri() . "/assets/images/slide-{$i}-feature.jpg",
            'button_text' => 'Learn More',
            'button_url' => '#'
        );
    }
    
    $options = array(
        'autoplay' => $autoplay,
        'autoplay_delay' => intval($atts['delay']),
        'height' => sanitize_text_field($atts['height']),
        'show_arrows' => $show_arrows,
        'show_dots' => $show_dots
    );
    
    $slider = new HeroSlider('shortcode-hero-slider', $slides, $options);
    return $slider->render();
}

add_shortcode('hero_slider', 'hero_slider_shortcode');

/**
 * Add admin styles for better customizer experience
 */
function hero_slider_admin_styles() {
    echo '<style>
        .customize-control-title {
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .customize-control[id*="hero_slide"] {
            border-bottom: 1px solid #ddd;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        
        .customize-control[id*="hero_slide"][id*="title"] {
            margin-top: 20px;
        }
        
        .customize-control[id*="hero_slide"][id*="title"] .customize-control-title {
            background: #0073aa;
            color: white;
            padding: 8px 12px;
            margin: -12px -12px 12px -12px;
            border-radius: 3px;
        }
    </style>';
}

add_action('customize_controls_print_styles', 'hero_slider_admin_styles');
?>