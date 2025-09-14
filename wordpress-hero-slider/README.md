# WordPress Hero Slider

A mobile-friendly, responsive hero section slider for WordPress websites. Inspired by wedding cake gallery themes with smooth animations, touch/swipe support, and full accessibility features.

## Features

- ✅ **Fully Responsive**: Mobile-first design that adapts to all screen sizes
- ✅ **Touch/Swipe Support**: Native gesture support for mobile and tablet devices
- ✅ **WordPress Integration**: Ready-to-use PHP class with WordPress hooks
- ✅ **Accessibility**: ARIA attributes, keyboard navigation, and screen reader support
- ✅ **SEO Friendly**: Semantic HTML structure with proper heading hierarchy
- ✅ **Performance Optimized**: Lazy loading, efficient animations, and minimal dependencies
- ✅ **Customizable**: Extensive options and easy styling customization
- ✅ **Cross-Browser**: Compatible with all modern browsers
- ✅ **Auto-play**: Smart auto-play with pause on hover and visibility detection

## Demo

Open `demo.html` in your browser to see the slider in action with sample content.

## Installation

### For WordPress Themes

1. Copy the `wordpress-hero-slider` folder to your theme directory
2. Include the main file in your theme's `functions.php`:

```php
require_once get_template_directory() . '/wordpress-hero-slider/hero-slider.php';
```

### For WordPress Plugins

1. Copy the files to your plugin directory
2. Enqueue the styles and scripts in your plugin
3. Use the HeroSlider class to create sliders

## Usage

### Basic WordPress Implementation

```php
<?php
// In your template file (e.g., front-page.php, header.php)

$slides = array(
    array(
        'title' => 'Your Slide Title',
        'subtitle' => 'Your Subtitle',
        'description' => 'Your description text',
        'background_image' => 'path/to/background.jpg',
        'featured_image' => 'path/to/featured.jpg', // Optional
        'button_text' => 'Call to Action',
        'button_url' => '#link'
    ),
    // Add more slides...
);

$options = array(
    'autoplay' => true,
    'autoplay_delay' => 6000,
    'height' => '100vh',
    'mobile_height' => '80vh',
    'overlay_opacity' => 0.4
);

display_hero_slider($slides, $options);
?>
```

### Advanced WordPress Implementation

```php
<?php
// Create a custom slider instance
$slider = new HeroSlider('my-hero-slider', array(), array(
    'autoplay' => true,
    'autoplay_delay' => 5000,
    'show_arrows' => true,
    'show_dots' => true
));

// Add slides dynamically
$slider->add_slide(array(
    'title' => 'Slide 1 Title',
    'subtitle' => 'Slide 1 Subtitle',
    'description' => 'Slide 1 description',
    'background_image' => get_template_directory_uri() . '/images/slide1.jpg',
    'button_text' => 'Learn More',
    'button_url' => '/about'
));

$slider->add_slide(array(
    'title' => 'Slide 2 Title',
    'subtitle' => 'Slide 2 Subtitle',
    'description' => 'Slide 2 description',
    'background_image' => get_template_directory_uri() . '/images/slide2.jpg',
    'button_text' => 'Get Started',
    'button_url' => '/contact'
));

// Render the slider
echo $slider->render();
?>
```

### HTML Implementation (without WordPress)

```html
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/hero-slider.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <section class="hero-slider-container" data-options='{"autoplay": true, "autoplay_delay": 5000}'>
        <div class="hero-slider-wrapper">
            <div class="hero-slides">
                <div class="hero-slide active" data-bg-image="path/to/background1.jpg">
                    <div class="slide-overlay"></div>
                    <div class="slide-content">
                        <div class="container">
                            <div class="slide-text">
                                <h1 class="slide-title">Your Title</h1>
                                <h2 class="slide-subtitle">Your Subtitle</h2>
                                <p class="slide-description">Your description</p>
                                <div class="slide-buttons">
                                    <a href="#" class="btn btn-primary slide-btn">Call to Action</a>
                                </div>
                            </div>
                            <div class="slide-image">
                                <img src="path/to/featured.jpg" alt="Featured" class="featured-img">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- More slides... -->
            </div>
            
            <!-- Navigation -->
            <div class="slider-navigation">
                <button class="nav-arrow prev-arrow">‹</button>
                <button class="nav-arrow next-arrow">›</button>
            </div>
            
            <!-- Pagination -->
            <div class="slider-pagination">
                <button class="pagination-dot active" data-slide="0"></button>
                <!-- More dots... -->
            </div>
        </div>
    </section>
    
    <script src="js/hero-slider.js"></script>
</body>
</html>
```

## Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `autoplay` | boolean | `true` | Enable/disable automatic slide progression |
| `autoplay_delay` | number | `5000` | Time between slides in milliseconds |
| `show_arrows` | boolean | `true` | Show/hide navigation arrows |
| `show_dots` | boolean | `true` | Show/hide pagination dots |
| `transition_speed` | number | `600` | Transition duration in milliseconds |
| `height` | string | `'100vh'` | Desktop height (CSS value) |
| `mobile_height` | string | `'70vh'` | Mobile height (CSS value) |
| `overlay_opacity` | number | `0.3` | Background overlay opacity (0-1) |
| `enable_touch` | boolean | `true` | Enable touch/swipe gestures |
| `swipe_threshold` | number | `50` | Minimum swipe distance in pixels |

## Slide Data Structure

Each slide should be an array/object with the following properties:

```php
array(
    'title' => 'Slide Title',                    // Required
    'subtitle' => 'Slide Subtitle',              // Optional
    'description' => 'Slide description text',   // Optional
    'background_image' => 'path/to/bg.jpg',      // Required
    'featured_image' => 'path/to/feature.jpg',   // Optional
    'button_text' => 'Button Text',              // Optional
    'button_url' => 'https://example.com'        // Optional (required if button_text is set)
)
```

## Customization

### CSS Customization

The slider uses CSS custom properties (variables) for easy customization:

```css
.hero-slider-container {
    --primary-color: #d4af37;
    --text-color: #ffffff;
    --overlay-color: rgba(0, 0, 0, 0.5);
    --border-radius: 15px;
    --transition-speed: 0.6s;
}
```

### JavaScript API

Access the slider instance for programmatic control:

```javascript
// Get slider instance
const slider = $('#hero-slider').data('heroSlider');

// Control methods
slider.nextSlide();           // Go to next slide
slider.prevSlide();           // Go to previous slide
slider.goToSlide(2);          // Go to specific slide (0-indexed)
slider.pause();               // Pause autoplay
slider.play();                // Resume autoplay
slider.destroy();             // Destroy slider instance

// Get information
slider.getCurrentSlide();     // Get current slide index
slider.getTotalSlides();      // Get total number of slides
```

### Events

Listen for slider events:

```javascript
$('#hero-slider').on('slideChange', function(event, slideIndex, slideElement) {
    console.log('Slide changed to:', slideIndex);
});
```

## Responsive Breakpoints

The slider uses the following responsive breakpoints:

- **Mobile**: < 768px
- **Tablet**: 768px - 1023px
- **Desktop**: 1024px - 1399px
- **Large Desktop**: ≥ 1400px

## Browser Support

- Chrome 60+
- Firefox 60+
- Safari 12+
- Edge 79+
- iOS Safari 12+
- Android Chrome 60+

## Performance Tips

1. **Optimize Images**: Use appropriately sized images (1920x1080 for backgrounds)
2. **Use WebP Format**: For better compression and quality
3. **Lazy Loading**: Background images are loaded as needed
4. **Reduce Slides**: Limit to 3-5 slides for optimal performance
5. **CDN**: Host images on a CDN for faster loading

## Accessibility Features

- ARIA labels and roles
- Keyboard navigation (arrow keys)
- Screen reader support
- Focus management
- High contrast mode support
- Reduced motion support
- Semantic HTML structure

## SEO Considerations

- Proper heading hierarchy (h1, h2)
- Alt text for images
- Semantic HTML structure
- Fast loading times
- Mobile-first responsive design

## Troubleshooting

### Common Issues

**Slider not working:**
- Ensure jQuery is loaded before the slider script
- Check browser console for JavaScript errors
- Verify all required files are included

**Images not displaying:**
- Check image paths are correct
- Ensure images are accessible (not blocked by permissions)
- Verify image formats are supported (JPG, PNG, WebP)

**Mobile issues:**
- Test touch events on actual devices
- Check viewport meta tag is present
- Ensure touch-action CSS is properly set

### Debug Mode

Enable debug mode by adding to your JavaScript:

```javascript
window.heroSliderDebug = true;
```

This will log slider events and state changes to the browser console.

## License

This hero slider is free to use for personal and commercial projects. Attribution is appreciated but not required.

## Support

For WordPress-specific implementation help, consult the WordPress documentation or your theme developer. For general HTML/CSS/JavaScript questions, refer to web development resources and communities.