<?php
/**
 * Custom Header Template - SAFE VERSION
 */

// Safety checks
$header_logo = function_exists('get_field') ? get_field('header_logo', 'option') : null;
$header_bg = function_exists('get_field') ? get_field('header_bg_color', 'option') : '#1a1a1a';
$accent_color = function_exists('get_field') ? get_field('header_accent_color', 'option') : '#d4af37';
$social_links = function_exists('get_field') ? get_field('social_media_links', 'option') : null;

// Fallback if get_field doesn't work
if (empty($header_bg)) $header_bg = '#1a1a1a';
if (empty($accent_color)) $accent_color = '#d4af37';

$icon_map = array(
    'facebook'  => 'dashicons-facebook',
    'twitter'   => 'dashicons-twitter',
    'instagram' => 'dashicons-instagram',
    'linkedin'  => 'dashicons-linkedin',
    'youtube'   => 'dashicons-youtube',
    'pinterest' => 'dashicons-pinterest',
    'tiktok'    => 'dashicons-video-alt3'
);
?>

<header class="custom-site-header" style="background-color: <?php echo esc_attr($header_bg); ?>; border-bottom: 3px solid <?php echo esc_attr($accent_color); ?>;">
    <div class="header-container">
        
        <!-- Logo Section -->
        <div class="header-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <?php if ($header_logo && is_array($header_logo) && isset($header_logo['url'])): ?>
                    <img src="<?php echo esc_url($header_logo['url']); ?>" 
                         alt="<?php echo esc_attr(isset($header_logo['alt']) ? $header_logo['alt'] : get_bloginfo('name')); ?>" 
                         class="custom-logo">
                <?php else: ?>
                    <span class="site-title"><?php bloginfo('name'); ?></span>
                <?php endif; ?>
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" aria-label="Toggle Menu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <!-- Navigation Menu -->
        <nav class="header-navigation">
            <?php
            if (has_nav_menu('primary-menu')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary-menu',
                    'container'      => false,
                    'menu_class'     => 'primary-menu',
                    'fallback_cb'    => false
                ));
            } else {
                echo '<ul class="primary-menu"><li><a href="' . home_url('/') . '">Home</a></li></ul>';
            }
            ?>
        </nav>

        <!-- Social Media Icons -->
        <?php if ($social_links && is_array($social_links)): ?>
            <div class="header-icons">
                <div class="social-links">
                    <?php foreach ($social_links as $link): 
                        if (!is_array($link) || !isset($link['platform']) || !isset($link['url'])) continue;
                        
                        $platform = strtolower($link['platform']);
                        $icon = isset($icon_map[$platform]) ? $icon_map[$platform] : 'dashicons-share';
                    ?>
                        <a href="<?php echo esc_url($link['url']); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="social-link social-<?php echo esc_attr($platform); ?>"
                           aria-label="<?php echo esc_attr(ucfirst($platform)); ?>">
                            <span class="dashicons <?php echo esc_attr($icon); ?>"></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay"></div>
</header>