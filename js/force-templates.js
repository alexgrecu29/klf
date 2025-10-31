<?php
/**
 * Kelticflow Child Theme Functions
 */

// Enqueue styles and scripts
function child_theme_enqueue_styles() {
    // Enqueue parent theme styles
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    
    // Enqueue Google Fonts - Poppins
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap', array(), null);
    
    // Enqueue child theme styles
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style', 'google-fonts'));
    
    // Enqueue Dashicons
    wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'child_theme_enqueue_styles');

// Create ACF Options Page for Header Settings
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Header Settings',
        'menu_title'    => 'Header Settings',
        'menu_slug'     => 'header-settings',
        'capability'    => 'edit_posts',
        'icon_url'      => 'dashicons-admin-customizer',
        'position'      => 60,
        'redirect'      => false
    ));
}

// Create ACF Options Page for Footer Settings
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Footer Settings',
        'menu_title'    => 'Footer Settings',
        'menu_slug'     => 'footer-settings',
        'capability'    => 'edit_posts',
        'icon_url'      => 'dashicons-admin-generic',
        'position'      => 61,
        'redirect'      => false
    ));
}

// Create ACF Options Page for Home Page Settings
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Home Page Settings',
        'menu_title'    => 'Home Page Settings',
        'menu_slug'     => 'home-page-settings',
        'capability'    => 'edit_posts',
        'icon_url'      => 'dashicons-admin-home',
        'position'      => 62,
        'redirect'      => false
    ));
}

// Register navigation menus
function register_custom_menus() {
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'kelticflow-child'),
        'footer-menu' => __('Footer Menu', 'kelticflow-child'),
    ));
}
add_action('init', 'register_custom_menus');

// Add theme supports
function custom_theme_setup() {
    add_theme_support('custom-logo');
    add_theme_support('menus');
}
add_action('after_setup_theme', 'custom_theme_setup');

// Enqueue mobile menu script
function enqueue_mobile_menu_script() {
    wp_enqueue_script('mobile-menu', get_stylesheet_directory_uri() . '/js/mobile-menu.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_mobile_menu_script');

// Enqueue force templates script
function enqueue_force_templates_script() {
    wp_enqueue_script('force-templates', get_stylesheet_directory_uri() . '/js/force-templates.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_force_templates_script');

// FORCE custom header to load on ALL pages - highest priority
function force_custom_header() {
    if (!is_admin()) {
        get_template_part('template-parts/header', 'custom');
    }
}
add_action('wp_body_open', 'force_custom_header', 1);

// FORCE custom footer to load on ALL pages - before wp_footer
function force_custom_footer() {
    if (!is_admin()) {
        get_template_part('template-parts/footer', 'custom');
    }
}
add_action('wp_footer', 'force_custom_footer', 1);

// Remove "Powered by WordPress" and other default elements
function remove_wp_footer_output() {
    ob_start(function($html) {
        // Remove "Proudly powered by WordPress" and related content
        $html = preg_replace('/<p[^>]*class="[^"]*powered[^"]*"[^>]*>.*?<\/p>/is', '', $html);
        $html = preg_replace('/Proudly powered by WordPress/i', '', $html);
        $html = preg_replace('/<a[^>]*href=["\']https?:\/\/wordpress\.org[^>]*>.*?<\/a>/is', '', $html);
        return $html;
    });
}
add_action('template_redirect', 'remove_wp_footer_output');

// Add inline CSS to hide default theme elements - VERY HIGH PRIORITY
function hide_default_theme_elements() {
    ?>
    <style id="custom-theme-overrides">
        /* CRITICAL: Hide all default theme headers and footers */
        body > .wp-site-blocks > header:not(.custom-site-header),
        body > .wp-site-blocks > footer:not(.custom-site-footer),
        .wp-block-template-part[class*="header"]:not(.custom-site-header),
        .wp-block-template-part[class*="footer"]:not(.custom-site-footer),
        header.wp-block-template-part,
        footer.wp-block-template-part,
        .wp-block-site-title ~ p,
        footer a[href*="wordpress.org"],
        footer p:has(a[href*="wordpress.org"]),
        p:has(a[href*="wordpress.org"]),
        [class*="powered"],
        [class*="proudly"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
        }
        
        /* CRITICAL: Ensure custom elements always show */
        .custom-site-header,
        .custom-site-footer {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            height: auto !important;
        }
        
        /* Remove padding from all pages */
        .wp-site-blocks,
        body > .wp-site-blocks {
            padding: 0 !important;
        }
        
        /* Ensure main content shows properly */
        main {
            display: block !important;
            visibility: visible !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'hide_default_theme_elements', 999);
add_action('admin_head', 'hide_default_theme_elements', 999);

// Completely disable block theme templates
add_filter('get_block_templates', function($templates) {
    // Return empty array to disable block templates
    return [];
}, 999, 3);

// Force classic theme behavior
add_filter('wp_theme_json_data_theme', function($theme_json) {
    return $theme_json;
}, 999);