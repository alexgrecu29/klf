<?php
/**
 * Template Name: Category Products
 * Description: Display products from a specific category
 */

get_header();

// Get the category from URL parameter or ACF field
$category_slug = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : get_field('product_category');

if ($category_slug) {
    $category = get_term_by('slug', $category_slug, 'product_cat');
} else {
    $category = null;
}
?>

<main id="main" class="site-main custom-category-page">
     <?php custom_breadcrumbs(); ?>
    
    <?php if ($category && !is_wp_error($category)): ?>
    
    <!-- Category Hero Section -->
    <section class="category-hero-section">
        <div class="category-hero-content">
            <h1 class="category-hero-title"><?php echo esc_html($category->name); ?></h1>
            
            <?php if ($category->description): ?>
                <p class="category-hero-description">
                    <?php echo wp_kses_post($category->description); ?>
                </p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    // Query products from this category
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category->slug,
            ),
        ),
    );
    
    $products = new WP_Query($args);
    ?>

    <?php if ($products->have_posts()): ?>
    
    <!-- Products Section -->
    <section class="category-products-section">
        <div class="category-products-container">
            
            <!-- WooCommerce Toolbar -->
            <div class="woocommerce-toolbar">
                <p class="woocommerce-result-count">
                    Showing <?php echo (($paged - 1) * 12) + 1; ?>–<?php echo min($paged * 12, $products->found_posts); ?> of <?php echo $products->found_posts; ?> results
                </p>
            </div>

            <ul class="products columns-3">
                <?php while ($products->have_posts()): $products->the_post(); ?>
                    
                    <li <?php wc_product_class('', $product); ?>>
                        <?php
                        /**
                         * Hook: woocommerce_before_shop_loop_item.
                         */
                        do_action('woocommerce_before_shop_loop_item');
                        ?>

                        <a href="<?php the_permalink(); ?>" class="woocommerce-loop-product__link">
                            <?php
                            /**
                             * Hook: woocommerce_before_shop_loop_item_title.
                             */
                            do_action('woocommerce_before_shop_loop_item_title');
                            ?>

                            <h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
                        </a>

                        <?php
                        /**
                         * Hook: woocommerce_after_shop_loop_item_title.
                         */
                        do_action('woocommerce_after_shop_loop_item_title');
                        ?>

                        <div class="product-btn-wrapper">
                            <?php
                            /**
                             * Hook: woocommerce_after_shop_loop_item.
                             */
                            do_action('woocommerce_after_shop_loop_item');
                            ?>
                        </div>
                    </li>
                    
                <?php endwhile; ?>
            </ul>
            
            <!-- Pagination -->
            <?php
            $total_pages = $products->max_num_pages;
            
            if ($total_pages > 1):
                echo '<nav class="woocommerce-pagination">';
                echo paginate_links(array(
                    'base'      => get_pagenum_link(1) . '%_%',
                    'format'    => '?paged=%#%',
                    'current'   => $paged,
                    'total'     => $total_pages,
                    'prev_text' => '&larr;',
                    'next_text' => '&rarr;',
                ));
                echo '</nav>';
            endif;
            ?>
            
        </div>
    </section>
    
    <?php wp_reset_postdata(); ?>
    
    <?php else: ?>
    
    <!-- No Products Found -->
    <section class="no-products-section">
        <div class="no-products-container">
            <p>No products found in this category.</p>
        </div>
    </section>
    
    <?php endif; ?>
    
    <?php else: ?>
    
    <!-- No Category Selected -->
    <section class="no-products-section">
        <div class="no-products-container">
            <h1>No Category Selected</h1>
            <p>Please select a product category to view.</p>
        </div>
    </section>
    
    <?php endif; ?>

</main>

<?php get_footer(); ?>