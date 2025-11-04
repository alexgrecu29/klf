<?php
/**
 * Template Name: Contact Us
 */

get_header();

// Get ACF fields or use defaults
$contact_title = get_field('contact_page_title') ?: 'Contact Us';
$contact_description = get_field('contact_page_description');
$contact_form_shortcode = get_field('contact_form_shortcode');
$sidebar_image = get_field('contact_sidebar_image');

// If no form shortcode is set, try to get the default Contact Form 7
if (!$contact_form_shortcode) {
    $contact_form_shortcode = '[contact-form-7 id="7bf5d21" title="Contact Us"]';
}
?>

<main id="main" class="site-main contact-us-page">
    
    <?php 
    // Breadcrumbs
    if (function_exists('custom_breadcrumbs')) {
        custom_breadcrumbs(); 
    }
    ?>
    
    <section class="contact-page-section">
        <div class="contact-page-title">
        
        <h1 class="contact-page-title"><?php echo esc_html($contact_title); ?></h1>
                
                <?php if ($contact_description): ?>
                    <div class="contact-page-description">
                        <?php echo wp_kses_post($contact_description); ?>
                    </div>
                <?php endif; ?>
                </div>
        <div class="contact-page-container">
            
            <!-- Contact Form Column -->
            <div class="contact-form-column">
                
                
                <div class="contact-form-wrapper">
                    <?php echo do_shortcode($contact_form_shortcode); ?>
                </div>
            </div>
            
            <!-- Sidebar Column -->
            <div class="contact-sidebar-column">
                <?php if ($sidebar_image): ?>
                    <div class="contact-sidebar-image">
                        <img src="<?php echo esc_url($sidebar_image['url']); ?>" alt="<?php echo esc_attr($sidebar_image['alt']); ?>">
                    </div>
                <?php else: ?>
                    <div class="contact-sidebar-placeholder">
                        <span class="dashicons dashicons-format-image"></span>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
    </section>

</main>

<?php get_footer(); ?>