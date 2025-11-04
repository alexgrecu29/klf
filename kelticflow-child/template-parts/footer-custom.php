<?php
/**
 * Custom Footer Template - SAFE VERSION
 */

// Safety checks for ACF fields
$footer_bg = '#1a1a1a';
$footer_text = null;
$footer_phone = null;
$footer_email = null;
$footer_address = null;
$footer_address_line1 = null;
$footer_social_links = null;
$copyright_text = 'kelticflow';
$design_company = null;
$design_company_url = null;

// Only try to get ACF fields if the function exists
if (function_exists('get_field')) {
    try {
        $footer_bg = get_field('footer_bg_color', 'option') ?: '#1a1a1a';
        $footer_text = get_field('footer_about_text', 'option');
        $footer_phone = get_field('footer_phone', 'option');
        $footer_email = get_field('footer_email', 'option');
        $footer_address = get_field('footer_address', 'option');
        $footer_address_line1 = get_field('footer_address_line1', 'option');
        $footer_social_links = get_field('footer_social_links', 'option');
        $copyright_text = get_field('footer_copyright_text', 'option') ?: 'kelticflow';
        $design_company = get_field('footer_design_company', 'option');
        $design_company_url = get_field('footer_design_company_url', 'option');
    } catch (Exception $e) {
        // Silently fail if ACF has issues
    }
}

$icon_map = array(
    'facebook'  => 'dashicons-facebook',
    'twitter'   => 'dashicons-twitter',
    'instagram' => 'dashicons-instagram',
    'linkedin'  => 'dashicons-linkedin',
    'youtube'   => 'dashicons-youtube',
    'pinterest' => 'dashicons-pinterest',
    'tiktok'    => 'dashicons-video-alt3'
);

$default_text = 'At kelticflow, innovation and premium quality are the cornerstones of our success.';
$default_address = 'Country, street name 44';
?>

<footer class="custom-site-footer" style="background-color: <?php echo esc_attr($footer_bg); ?>;">
    <div class="footer-main">
        <div class="footer-container">
            
            <!-- About Column -->
            <div class="footer-column footer-about">
                <h3 class="footer-title">Kelticflow</h3>
                <p class="footer-description">
                    <?php echo esc_html($footer_text ?: $default_text); ?>
                </p>
            </div>

            <!-- Quick Links Column -->
            <div class="footer-column footer-links">
                <h3 class="footer-title">Quick Links</h3>
                <?php
                if (has_nav_menu('footer-menu')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu',
                        'container'      => false,
                        'menu_class'     => 'footer-menu',
                        'fallback_cb'    => false
                    ));
                } elseif (has_nav_menu('primary-menu')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'footer-menu',
                        'fallback_cb'    => false
                    ));
                } else {
                    echo '<ul class="footer-menu"><li><a href="' . home_url('/') . '">Home</a></li></ul>';
                }
                ?>
            </div>

            <!-- Contacts Column -->
            <div class="footer-column footer-contacts">
                <h3 class="footer-title">Contacts</h3>
                
                <p class="footer-contact-item">
                    <?php echo esc_html($footer_address_line1 ?: $default_address); ?>
                    <?php if ($footer_address): ?>
                        <br><?php echo esc_html($footer_address); ?>
                    <?php endif; ?>
                </p>
                
                <?php if ($footer_phone): ?>
                    <p class="footer-contact-item">
                        <a href="tel:<?php echo esc_attr(str_replace([' ', '(', ')', '-'], '', $footer_phone)); ?>">
                            <?php echo esc_html($footer_phone); ?>
                        </a>
                    </p>
                <?php endif; ?>
                
                <?php if ($footer_email): ?>
                    <p class="footer-contact-item">
                        <a href="mailto:<?php echo esc_attr($footer_email); ?>">
                            <?php echo esc_html($footer_email); ?>
                        </a>
                    </p>
                <?php endif; ?>

                <!-- Social Media Links -->
                <?php if ($footer_social_links && is_array($footer_social_links)): ?>
                    <div class="footer-social-links">
                        <?php foreach ($footer_social_links as $link): 
                            if (!is_array($link) || !isset($link['platform']) || !isset($link['url'])) continue;
                            
                            $platform = strtolower($link['platform']);
                            $icon = isset($icon_map[$platform]) ? $icon_map[$platform] : 'dashicons-share';
                        ?>
                            <a href="<?php echo esc_url($link['url']); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="footer-social-link social-<?php echo esc_attr($platform); ?>"
                               aria-label="<?php echo esc_attr(ucfirst($platform)); ?>">
                                <span class="dashicons <?php echo esc_attr($icon); ?>"></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Footer Credits -->
    <div class="footer-credits-row">
        <div class="footer-credits-container">
            <p class="footer-copyright">
                &copy; <?php echo date('Y'); ?>, <?php echo esc_html($copyright_text); ?>
            </p>
            <?php if ($design_company): ?>
                <p class="footer-design">
                    Design by: 
                    <?php if ($design_company_url): ?>
                        <a href="<?php echo esc_url($design_company_url); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="design-company-link">
                            <?php echo esc_html($design_company); ?>
                        </a>
                    <?php else: ?>
                        <span class="design-company"><?php echo esc_html($design_company); ?></span>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</footer>