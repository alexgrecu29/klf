<?php
/**
 * Template Name: About Us
 */

get_header();

// Get hero section fields
$hero_image = get_field('about_hero_image');
$hero_title = get_field('about_hero_title') ?: get_the_title();
$hero_subtitle = get_field('about_hero_subtitle');
?>

<main id="main" class="site-main about-us-page">
    
    <?php 
    // Breadcrumbs
    if (function_exists('custom_breadcrumbs')) {
        custom_breadcrumbs(); 
    }
    ?>
    
    <!-- Hero Section -->
    <?php if ($hero_image): ?>
    <section class="about-hero-section" style="background-image: url('<?php echo esc_url($hero_image['url']); ?>');">
        <div class="about-hero-overlay">
            <div class="about-hero-content">
                <h1 class="about-hero-title"><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_subtitle): ?>
                    <p class="about-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <?php 
    // Check if the flexible content field has rows of data
    if (have_rows('about_us_content')): 
        
        while (have_rows('about_us_content')): the_row();
            
            // Text Content Block
            if (get_row_layout() == 'text_content_block'):
                $heading = get_sub_field('heading');
                $text = get_sub_field('text_content');
                ?>
                <section class="about-text-section">
                    <div class="about-text-container">
                        <?php if ($heading): ?>
                            <h2 class="about-heading"><?php echo esc_html($heading); ?></h2>
                        <?php endif; ?>
                        <?php if ($text): ?>
                            <div class="about-text-content">
                                <?php echo wp_kses_post($text); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php
            
            // Image and Text Block
            elseif (get_row_layout() == 'image_text_block'):
                $heading = get_sub_field('heading');
                $text = get_sub_field('text_content');
                $image = get_sub_field('image');
                $layout = get_sub_field('layout');
                ?>
                <section class="about-image-text-section">
                    <div class="about-image-text-container <?php echo esc_attr($layout); ?>">
                        <div class="about-image-column">
                            <?php if ($image): ?>
                                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>">
                            <?php else: ?>
                                <div class="image-placeholder"></div>
                            <?php endif; ?>
                        </div>
                        <div class="about-content-column">
                            <?php if ($heading): ?>
                                <h2 class="about-section-heading"><?php echo esc_html($heading); ?></h2>
                            <?php endif; ?>
                            <?php if ($text): ?>
                                <div class="about-section-text">
                                    <?php echo wp_kses_post($text); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                <?php
            
            // Full Width Text Block
            elseif (get_row_layout() == 'full_width_text'):
                $heading = get_sub_field('heading');
                $text = get_sub_field('text_content');
                $background_color = get_sub_field('background_color') ?: '#f9f9f9';
                ?>
                <section class="about-full-width-section" style="background-color: <?php echo esc_attr($background_color); ?>;">
                    <div class="about-full-width-container">
                        <?php if ($heading): ?>
                            <h2 class="about-full-width-heading"><?php echo esc_html($heading); ?></h2>
                        <?php endif; ?>
                        <?php if ($text): ?>
                            <div class="about-full-width-text">
                                <?php echo wp_kses_post($text); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
                <?php
            
            endif;
            
        endwhile;
        
    else:
        ?>
        <section class="about-text-section">
            <div class="about-text-container">
                <h2 class="about-heading">About Us</h2>
                <div class="about-text-content">
                    <p>Crafted with precision and elegance, our towel rails not only serve the practical purpose of keeping your towels warm and ready for use but also add a touch of sophistication to your space.</p>
                </div>
            </div>
        </section>
        
        <section class="about-image-text-section">
            <div class="about-image-text-container image_right">
                <div class="about-image-column">
                    <div class="image-placeholder"></div>
                </div>
                <div class="about-content-column">
                    <h2 class="about-section-heading">Our Promise of Excellence</h2>
                    <div class="about-section-text">
                        <p>Our commitment to providing premium quality products is unwavering; each item in our inventory is carefully selected to meet our exacting standards for performance and durability.</p>
                    </div>
                </div>
            </div>
        </section>
        <?php
    endif;
    ?>

</main>

<?php get_footer(); ?>