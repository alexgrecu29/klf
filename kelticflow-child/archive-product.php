<?php
/**
 * Archive Product Template - Override for Shop Page
 * Shows categories on main shop page, products elsewhere
 */

get_header();

// Check if this is the main shop page (not a category or tag page)
$is_shop_page = is_shop() && !is_product_category() && !is_product_tag();

if ($is_shop_page) {
    // Display categories on main shop page
    ?>
    
    <main id="main" class="site-main all-categories-page">
        <?php custom_breadcrumbs(); ?>
        
        <!-- Page Header Section -->
        <section class="all-categories-hero-section">
            <div class="all-categories-hero-overlay">
                <div class="all-categories-hero-content">
                    <h1 class="all-categories-hero-title">Products</h1>
                    <p class="all-categories-hero-description">
                        Browse our complete range of products by category
                    </p>
                </div>
            </div>
        </section>

        <?php
        // Get all parent categories (top-level categories only)
        $parent_categories = get_terms(array(
            'taxonomy'   => 'product_cat',
            'parent'     => 0,
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ));
        ?>

        <?php if ($parent_categories && !is_wp_error($parent_categories)): ?>
        <!-- All Categories Grid Section -->
        <section class="all-categories-section">
            <div class="all-categories-container">
                <div class="all-categories-grid">
                    <?php foreach ($parent_categories as $category): 
                        // Skip the uncategorized category
                        if ($category->slug === 'uncategorized') {
                            continue;
                        }
                        
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
                        $category_link = get_term_link($category);
                        $product_count = $category->count;
                    ?>
                        <div class="category-card" data-href="<?php echo esc_url($category_link); ?>">
                            <div class="category-image">
                                <?php if ($image_url): ?>
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
                                <?php else: ?>
                                    <div class="placeholder-image"></div>
                                <?php endif; ?>
                            </div>
                            <h3 class="category-name"><?php echo esc_html($category->name); ?></h3>
                            <div class="category-btn-wrapper">
                                <a href="<?php echo esc_url($category_link); ?>" class="view-products-btn">
                                    View Products
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php else: ?>
        <!-- No Categories Found -->
        <section class="no-categories-section">
            <div class="no-categories-container">
                <p>No product categories found.</p>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <script>
    // Enqueue the category cards script
    document.addEventListener('DOMContentLoaded', function() {
        const categoryCards = document.querySelectorAll('.category-card');
        
        categoryCards.forEach(function(card) {
            card.addEventListener('click', function(e) {
                // Don't follow link if clicking the button directly
                if (!e.target.classList.contains('view-products-btn') && !e.target.classList.contains('arrow')) {
                    const url = this.getAttribute('data-href');
                    if (url) {
                        window.location.href = url;
                    }
                }
            });
        });
    });
    </script>

    <?php
} else {
    // For other WooCommerce pages (search, tags, etc.), show default WooCommerce layout
    ?>
    <main id="main" class="site-main woocommerce-page">
        <?php custom_breadcrumbs(); ?>
        
        <div class="woocommerce-content">
            <?php
            if (have_posts()) {
                do_action('woocommerce_before_main_content');
                
                woocommerce_product_loop_start();
                
                while (have_posts()) {
                    the_post();
                    wc_get_template_part('content', 'product');
                }
                
                woocommerce_product_loop_end();
                
                do_action('woocommerce_after_main_content');
                
                woocommerce_pagination();
            } else {
                do_action('woocommerce_no_products_found');
            }
            ?>
        </div>
    </main>
    <?php
}

get_footer();
?>