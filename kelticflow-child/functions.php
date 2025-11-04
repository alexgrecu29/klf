<?php
/**
 * Kelticflow Child Theme Functions
 * Updated with error prevention and fixes
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue styles and scripts
function child_theme_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    
    // Enqueue Proxima Nova from Adobe Fonts (Typekit)
    wp_enqueue_style('proxima-nova-font', 'https://use.typekit.net/pfu3vbo.css', array(), null);
    
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style', 'proxima-nova-font'));
    wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'child_theme_enqueue_styles');

// Enqueue JavaScript files - UPDATED WITH GALLERY SUPPORT
function child_theme_enqueue_scripts() {
    wp_enqueue_script('mobile-menu', get_stylesheet_directory_uri() . '/js/mobile-menu.js', array(), '1.0', true);
    wp_enqueue_script('category-cards', get_stylesheet_directory_uri() . '/js/category-cards.js', array(), '1.0', true);
    wp_enqueue_script('header-scroll', get_stylesheet_directory_uri() . '/js/header-scroll.js', array(), '1.0', true);
    
    // Enqueue product gallery script only on single product pages
    if (is_singular('product')) {
        wp_enqueue_script('product-gallery', get_stylesheet_directory_uri() . '/js/product-gallery.js', array(), '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'child_theme_enqueue_scripts');

// Create ACF Options Pages
if (function_exists('acf_add_options_page')) {
    $options_pages = array(
        array(
            'page_title' => 'Header Settings',
            'menu_title' => 'Header Settings',
            'menu_slug'  => 'header-settings',
            'icon_url'   => 'dashicons-admin-customizer',
            'position'   => 60,
        ),
        array(
            'page_title' => 'Footer Settings',
            'menu_title' => 'Footer Settings',
            'menu_slug'  => 'footer-settings',
            'icon_url'   => 'dashicons-admin-generic',
            'position'   => 61,
        ),
        array(
            'page_title' => 'Home Page Settings',
            'menu_title' => 'Home Page Settings',
            'menu_slug'  => 'home-page-settings',
            'icon_url'   => 'dashicons-admin-home',
            'position'   => 62,
        ),
    );
    
    foreach ($options_pages as $page) {
        $page['capability'] = 'edit_posts';
        $page['redirect'] = false;
        acf_add_options_page($page);
    }
}

// Register navigation menus
function register_custom_menus() {
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'kelticflow-child'),
        'footer-menu'  => __('Footer Menu', 'kelticflow-child'),
    ));
}
add_action('init', 'register_custom_menus');

// Add theme supports
function custom_theme_setup() {
    add_theme_support('custom-logo');
    add_theme_support('menus');
}
add_action('after_setup_theme', 'custom_theme_setup');

// Override block theme defaults
function override_block_theme_styles() {
    ?>
    <style id="custom-theme-overrides">
        /* Hide all default theme headers and footers */
        body > .wp-site-blocks > header:not(.custom-site-header),
        body > .wp-site-blocks > footer:not(.custom-site-footer),
        .wp-block-template-part[class*="header"]:not(.custom-site-header),
        .wp-block-template-part[class*="footer"]:not(.custom-site-footer),
        header.wp-block-template-part:not(.custom-site-header),
        footer.wp-block-template-part:not(.custom-site-footer),
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
        
        /* Ensure custom elements always show */
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
        
        /* Hide WooCommerce block templates */
        .wp-block-template-part {
            display: none !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'override_block_theme_styles', 999);

// Disable block theme templates
add_filter('get_block_templates', '__return_empty_array', 999);

// Add Multiple Favicons for Different Devices
function add_device_specific_favicons() {
    $theme_uri = get_stylesheet_directory_uri();
    ?>
    <!-- Standard favicon -->
    <link rel="icon" type="image/jpg" sizes="32x32" href="<?php echo $theme_uri; ?>/images/favicon-32x32.jpg">
    <link rel="icon" type="image/jpg" sizes="16x16" href="<?php echo $theme_uri; ?>/images/favicon-16x16.jpg">
    
    <!-- Apple Touch Icons (iOS devices) -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $theme_uri; ?>/images/apple-touch-icon-180x180.jpg">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo $theme_uri; ?>/images/apple-touch-icon-152x152.jpg">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo $theme_uri; ?>/images/apple-touch-icon-120x120.jpg">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $theme_uri; ?>/images/apple-touch-icon-76x76.jpg">
    
    <!-- Android Chrome -->
    <link rel="icon" type="image/jpg" sizes="192x192" href="<?php echo $theme_uri; ?>/images/android-chrome-192x192.jpg">
    <link rel="icon" type="image/jpg" sizes="512x512" href="<?php echo $theme_uri; ?>/images/android-chrome-512x512.jpg">
    
    <!-- Microsoft Tiles (Windows) -->
    <meta name="msapplication-TileColor" content="#1a1a1a">
    <meta name="msapplication-TileImage" content="<?php echo $theme_uri; ?>/images/mstile-144x144.jpg">
    <meta name="msapplication-config" content="<?php echo $theme_uri; ?>/images/browserconfig.xml">
    
    <!-- Safari Pinned Tab -->
    <link rel="mask-icon" href="<?php echo $theme_uri; ?>/images/safari-pinned-tab.svg" color="#d4af37">
    
    <!-- Theme Color for Mobile Browsers -->
    <meta name="theme-color" content="#1a1a1a">
    <?php
}
add_action('wp_head', 'add_device_specific_favicons', 5);

// Optional: Disable WordPress default Site Icon (if you added it via Customizer)
add_filter('get_site_icon_url', '__return_false');

// Custom Breadcrumbs Function with Error Prevention
function custom_breadcrumbs() {
    
    // Safety check
    if (!function_exists('is_front_page')) {
        return;
    }
    
    // Settings
    $separator = '›';
    $home_title = 'Home';
    
    // FIXED: Point to your Products page
    $products_page_url = home_url('/products/');
    
    // Get the query & post information
    global $post, $wp_query;
    
    // Don't display on the homepage
    if (!is_front_page()) {
        
        // Build the breadcrumbs
        echo '<div class="breadcrumbs-container">';
        echo '<div class="breadcrumbs">';
        
        // Home page
        echo '<a href="' . esc_url(get_home_url()) . '" class="breadcrumb-item breadcrumb-home">' . esc_html($home_title) . '</a>';
        echo '<span class="breadcrumb-separator">' . esc_html($separator) . '</span>';
        
        // Check for product category FIRST before other archive types
        if (is_tax('product_cat')) {
            
            // WooCommerce product category
            echo '<a href="' . esc_url($products_page_url) . '" class="breadcrumb-item">Products</a>';
            echo '<span class="breadcrumb-separator">' . esc_html($separator) . '</span>';
            
            $term = get_queried_object();
            
            if ($term && !is_wp_error($term)) {
                // Get parent categories
                if (isset($term->parent) && $term->parent) {
                    $parent_terms = array();
                    $current_term = $term;
                    
                    while (isset($current_term->parent) && $current_term->parent) {
                        $current_term = get_term($current_term->parent, 'product_cat');
                        if (!$current_term || is_wp_error($current_term)) break;
                        $parent_terms[] = $current_term;
                    }
                    
                    $parent_terms = array_reverse($parent_terms);
                    
                    foreach ($parent_terms as $parent_term) {
                        echo '<a href="' . esc_url(get_term_link($parent_term)) . '" class="breadcrumb-item">' . esc_html($parent_term->name) . '</a>';
                        echo '<span class="breadcrumb-separator">' . esc_html($separator) . '</span>';
                    }
                }
                
                echo '<span class="breadcrumb-item breadcrumb-current">' . esc_html($term->name) . '</span>';
            }
            
        } else if (is_archive() && !is_tax() && !is_category() && !is_tag()) {
            
            echo '<span class="breadcrumb-item breadcrumb-current">' . post_type_archive_title('', false) . '</span>';
            
        } else if (is_archive() && is_tax() && !is_category() && !is_tag()) {
            
            // If post is a custom post type
            $post_type = get_post_type();
            
            // If it is a custom post type display name and link
            if ($post_type != 'post') {
                
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
                
                echo '<a href="' . esc_url($post_type_archive) . '" class="breadcrumb-item">' . esc_html($post_type_object->labels->name) . '</a>';
                echo '<span class="breadcrumb-separator">' . esc_html($separator) . '</span>';
            }
            
            $custom_tax_name = get_queried_object()->name;
            echo '<span class="breadcrumb-item breadcrumb-current">' . esc_html($custom_tax_name) . '</span>';
            
        } else if (is_single()) {
            
            // Get post type
            $post_type = get_post_type();
            
            // If post type is "post"
            if ($post_type == 'post') {
                
                // Get the categories
                $category = get_the_category();
                
                if (!empty($category)) {
                    // Get last category
                    $last_category = end($category);
                    
                    // Get parent any categories and create array
                    $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','), ',');
                    $cat_parents = explode(',', $get_cat_parents);
                    
                    // Loop through parent categories and store in variable
                    foreach ($cat_parents as $parent) {
                        echo '<span class="breadcrumb-item">' . $parent . '</span>';
                        echo '<span class="breadcrumb-separator">' . esc_html($separator) . '</span>';
                    }
                }
            }
            
            // If post type is "product" (WooCommerce)
            if ($post_type == 'product' && function_exists('wc_get_product')) {
                
                // Link to your Products page
                echo '<a href="' . esc_url($products_page_url) . '" class="breadcrumb-item">Products</a>';
                echo '<span class="breadcrumb-separator">' . esc_html($separator) . '</span>';
                
                // Get product categories
                $terms = get_the_terms($post->ID, 'product_cat');
                
                if ($terms && !is_wp_error($terms)) {
                    $term = end($terms);
                    
                    // Get parent categories
                    if ($term->parent) {
                        $parent_terms = array();
                        $current_term = $term;
                        
                        while ($current_term->parent) {
                            $current_term = get_term($current_term->parent, 'product_cat');
                            if (!$current_term || is_wp_error($current_term)) break;
                            $parent_terms[] = $current_term;
                        }
                        
                        $parent_terms = array_reverse($parent_terms);
                        
                        foreach ($parent_terms as $parent_term) {
                            echo '<a href="' . esc_url(get_term_link($parent_term)) . '" class="breadcrumb-item">' . esc_html($parent_term->name) . '</a>';
                            echo '<span class="breadcrumb-separator">' . esc_html($separator) . '</span>';
                        }
                    }
                    
                    echo '<a href="' . esc_url(get_term_link($term)) . '" class="breadcrumb-item">' . esc_html($term->name) . '</a>';
                    echo '<span class="breadcrumb-separator">' . esc_html($separator) . '</span>';
                }
            }
            
            // If post type is not "post" or "product"
            if ($post_type != 'post' && $post_type != 'product') {
                
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
                
                if ($post_type_object && $post_type_archive) {
                    echo '<a href="' . esc_url($post_type_archive) . '" class="breadcrumb-item">' . esc_html($post_type_object->labels->name) . '</a>';
                    echo '<span class="breadcrumb-separator">' . esc_html($separator) . '</span>';
                }
            }
            
            // Get post title
            echo '<span class="breadcrumb-item breadcrumb-current">' . esc_html(get_the_title()) . '</span>';
            
        } else if (is_category()) {
            
            // Category page
            echo '<span class="breadcrumb-item breadcrumb-current">' . single_cat_title('', false) . '</span>';
            
        } else if (is_page()) {
            
            // Standard page
            if ($post && $post->post_parent) {
                
                // If child page, get parents 
                $anc = get_post_ancestors($post->ID);
                
                // Get parents in the right order
                $anc = array_reverse($anc);
                
                // Parent page loop
                foreach ($anc as $ancestor) {
                    echo '<a href="' . esc_url(get_permalink($ancestor)) . '" class="breadcrumb-item">' . esc_html(get_the_title($ancestor)) . '</a>';
                    echo '<span class="breadcrumb-separator">' . esc_html($separator) . '</span>';
                }
                
                // Current page
                echo '<span class="breadcrumb-item breadcrumb-current">' . esc_html(get_the_title()) . '</span>';
                
            } else {
                
                // Just display current page if not parents
                echo '<span class="breadcrumb-item breadcrumb-current">' . esc_html(get_the_title()) . '</span>';
                
            }
            
        } else if (is_tag()) {
            
            // Tag page
            echo '<span class="breadcrumb-item breadcrumb-current">' . single_tag_title('', false) . '</span>';
            
        } else if (is_day()) {
            
            // Day archive
            echo '<span class="breadcrumb-item breadcrumb-current">Archive for ' . esc_html(get_the_time('F jS, Y')) . '</span>';
            
        } else if (is_month()) {
            
            // Month archive
            echo '<span class="breadcrumb-item breadcrumb-current">Archive for ' . esc_html(get_the_time('F, Y')) . '</span>';
            
        } else if (is_year()) {
            
            // Year archive
            echo '<span class="breadcrumb-item breadcrumb-current">Archive for ' . esc_html(get_the_time('Y')) . '</span>';
            
        } else if (is_author()) {
            
            // Author archive
            global $author;
            $userdata = get_userdata($author);
            if ($userdata) {
                echo '<span class="breadcrumb-item breadcrumb-current">Author: ' . esc_html($userdata->display_name) . '</span>';
            }
            
        } else if (is_search()) {
            
            // Search results page
            echo '<span class="breadcrumb-item breadcrumb-current">Search results for: ' . esc_html(get_search_query()) . '</span>';
            
        } else if (is_404()) {
            
            // 404 page
            echo '<span class="breadcrumb-item breadcrumb-current">Error 404</span>';
            
        }
        
        echo '</div>';
        echo '</div>';
        
    }
    
}

// Disable WooCommerce default breadcrumbs
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Force WooCommerce shop URL to use /products/ in breadcrumbs
add_filter('woocommerce_get_shop_page_permalink', 'custom_shop_page_permalink');
function custom_shop_page_permalink($url) {
    // Always return /products/ for breadcrumbs and links
    return home_url('/products/');
}

// Also override the shop page ID to point to products page
add_filter('woocommerce_get_shop_page_id', 'custom_shop_page_id');
function custom_shop_page_id($page_id) {
    // Get the products page ID
    $products_page = get_page_by_path('products');
    if ($products_page) {
        return $products_page->ID;
    }
    return $page_id;
}

// Force use of child theme template for product categories
add_filter('template_include', 'force_child_theme_category_template', 99);
function force_child_theme_category_template($template) {
    if (is_tax('product_cat')) {
        $child_template = get_stylesheet_directory() . '/taxonomy-product_cat.php';
        if (file_exists($child_template)) {
            return $child_template;
        }
    }
    return $template;
}

// Disable WooCommerce template override
add_filter('woocommerce_locate_template', 'disable_wc_category_override', 10, 3);
function disable_wc_category_override($template, $template_name, $template_path) {
    if ($template_name === 'taxonomy-product_cat.php') {
        $child_template = get_stylesheet_directory() . '/taxonomy-product_cat.php';
        if (file_exists($child_template)) {
            return $child_template;
        }
    }
    return $template;
}

// Ensure WooCommerce is loaded before single product pages
add_action('template_redirect', 'ensure_woocommerce_product_loaded');
function ensure_woocommerce_product_loaded() {
    if (is_singular('product') && function_exists('wc_get_product')) {
        global $product;
        if (!$product || !is_a($product, 'WC_Product')) {
            $product = wc_get_product(get_the_ID());
        }
    }
}

// Ensure WooCommerce product global is set early
add_action('wp', 'setup_product_global', 5);
function setup_product_global() {
    if (is_singular('product') && function_exists('wc_get_product')) {
        global $product;
        if (!$product || !is_a($product, 'WC_Product')) {
            $product = wc_get_product(get_the_ID());
        }
    }
}

// Disable Gutenberg completely
add_filter('use_block_editor_for_post_type', '__return_false', 100);


