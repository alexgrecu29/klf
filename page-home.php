<?php
/**
 * Template Name: Home Page
 */

get_header();

// Hero Section Fields
$hero_image = get_field('hero_background_image', 'option');
$hero_title = get_field('hero_title', 'option') ?: 'Kelticflow';
$hero_subtitle = get_field('hero_subtitle', 'option') ?: 'Heating and Plumbing Solutions';

// About Section Fields
$about_image = get_field('about_brand_image', 'option');
$about_title = get_field('about_brand_title', 'option') ?: 'More about Our Brand';
$about_text = get_field('about_brand_text', 'option') ?: 'At kelticflow, innovation and premium quality are at the heart of everything we do. We stay at the forefront of industry advancements, sourcing and providing the latest solutions to ensure your heating and plumbing needs are met to the highest standards.';
$about_button_text = get_field('about_button_text', 'option') ?: 'Read More';
$about_button_link = get_field('about_button_link', 'option');

// Featured Categories
$featured_categories = get_field('featured_categories', 'option');

if (!$featured_categories && function_exists('woocommerce')) {
    $featured_categories = get_terms(array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'number'     => 5,
    ));
}

// Contact Form Fields
$contact_title = get_field('contact_section_title', 'option') ?: 'Contact Us';
$contact_description = get_field('contact_section_description', 'option') ?: 'Ready to connect? We\'re just a message away. Reach out to our dedicated team today, and let\'s start solving your heating and plumbing needs together.';
$contact_form_shortcode = get_field('contact_form_shortcode', 'option'); // Add this ACF field to store the shortcode
?>

<main id="main" class="site-main">
    
    <!-- Hero Section -->
    <section class="hero-section" style="background-image: url('<?php echo esc_url($hero_image['url'] ?? ''); ?>');">
        <div class="hero-overlay">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
                <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
            </div>
        </div>
    </section>

    <!-- About Brand Section -->
    <section class="about-brand-section">
        <div class="about-brand-container">
            <div class="about-brand-image">
                <?php if ($about_image): ?>
                    <img src="<?php echo esc_url($about_image['url']); ?>" alt="<?php echo esc_attr($about_image['alt']); ?>">
                <?php else: ?>
                    <div class="placeholder-image"></div>
                <?php endif; ?>
            </div>
            <div class="about-brand-content">
                <h2><?php echo esc_html($about_title); ?></h2>
                <p><?php echo esc_html($about_text); ?></p>
                <?php if ($about_button_link): ?>
                    <a href="<?php echo esc_url($about_button_link); ?>" class="read-more-btn">
                        <?php echo esc_html($about_button_text); ?> 
                        <span class="arrow">→</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Featured Categories Section -->
    <?php if ($featured_categories): ?>
    <section class="featured-categories-section">
        <div class="featured-categories-container">
            <h2 class="section-title">Featured Categories</h2>
            
            <div class="categories-grid">
                <?php foreach ($featured_categories as $category): 
                    $term = is_object($category) ? $category : get_term($category, 'product_cat');
                    
                    if (!$term || is_wp_error($term)) continue;
                    
                    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                    $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
                    $category_link = get_term_link($term);
                ?>
                    <div class="category-card" data-href="<?php echo esc_url($category_link); ?>">
                        <div class="category-image">
                            <?php if ($image_url): ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($term->name); ?>">
                            <?php else: ?>
                                <div class="placeholder-image"></div>
                            <?php endif; ?>
                        </div>
                        <h3 class="category-name"><?php echo esc_html($term->name); ?></h3>
                        <div class="category-btn-wrapper"><a href="<?php echo esc_url($category_link); ?>" class="view-products-btn">View Products</a></div>
                        
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Contact Form Section -->
    <section class="contact-form-section">
        <div class="contact-form-container">
            <div class="contact-form-content">
                <h2 class="contact-form-title"><?php echo esc_html($contact_title); ?></h2>
                <p class="contact-form-description"><?php echo esc_html($contact_description); ?></p>
            </div>
            <div class="contact-form-wrapper">
                <?php 
                if ($contact_form_shortcode) {
                    echo do_shortcode($contact_form_shortcode);
                } else {
                    // Fallback - try to display the first Contact Form 7 form
                    echo do_shortcode('[contact-form-7 id="7bf5d21" title="Contact Us"]');
                }
                ?>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>