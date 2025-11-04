<?php
/**
 * Custom WooCommerce Category Template
 * Displays products in grid layout for subcategories
 * Displays subcategories for parent categories
 */

// Check if WooCommerce is active
if (!function_exists('woocommerce_product_loop')) {
    get_header();
    echo '<main class="site-main"><p>WooCommerce is not active.</p></main>';
    get_footer();
    return;
}

get_header();

// Get current category
$current_category = get_queried_object();

if (!$current_category || is_wp_error($current_category)) {
    echo '<main class="site-main"><p>Category not found.</p></main>';
    get_footer();
    return;
}

// Remove WooCommerce default elements we don't want
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Check if this category has subcategories
$subcategories = get_terms(array(
    'taxonomy'   => 'product_cat',
    'parent'     => $current_category->term_id,
    'hide_empty' => false,
));

$has_subcategories = !empty($subcategories) && !is_wp_error($subcategories);
?>

<main id="main" class="site-main custom-category-page">
    
    <!-- Custom Breadcrumbs -->
    <?php 
    if (function_exists('custom_breadcrumbs')) {
        custom_breadcrumbs(); 
    }
    ?>
    
    <!-- Category Header Section -->
    <section class="category-hero-section">
        <div class="category-hero-overlay">
            <div class="category-hero-content">
                <h1 class="category-hero-title"><?php echo esc_html($current_category->name); ?></h1>
                
                <?php if (!empty($current_category->description)): ?>
                    <p class="category-hero-description">
                        <?php echo wp_kses_post($current_category->description); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if ($has_subcategories): ?>
    <!-- Subcategories Grid Section (for parent categories) -->
    <section class="category-subcategories-section">
        <div class="category-subcategories-container">
            <h2 class="section-title">Browse Subcategories</h2>
            <div class="categories-grid">
                <?php foreach ($subcategories as $subcategory): 
                    $thumbnail_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                    $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
                    $category_link = get_term_link($subcategory);
                    $product_count = $subcategory->count;
                    
                    if (is_wp_error($category_link)) {
                        continue;
                    }
                ?>
                    <div class="category-card" data-href="<?php echo esc_url($category_link); ?>">
                        <div class="category-image">
                            <?php if ($image_url): ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($subcategory->name); ?>">
                            <?php else: ?>
                                <div class="placeholder-image"></div>
                            <?php endif; ?>
                            <?php if ($product_count > 0): ?>
                                <span class="product-count"><?php echo $product_count; ?> <?php echo $product_count === 1 ? 'Product' : 'Products'; ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="category-name"><?php echo esc_html($subcategory->name); ?></h3>
                        <div class="category-btn-wrapper">
                            <a href="<?php echo esc_url($category_link); ?>" class="view-products-btn">View Products</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Check if there are products to display
    $has_products = false;
    if (have_posts()) {
        $has_products = true;
    }
    
    if ($has_products):
    ?>
    <!-- Products Section -->
    <section class="category-products-section">
        <div class="category-products-container">
            <?php if ($has_subcategories): ?>
                <h2 class="section-title">All Products in <?php echo esc_html($current_category->name); ?></h2>
            <?php endif; ?>
            
            <ul class="products columns-3">
                <?php while (have_posts()): the_post(); 
                    global $product;
                    
                    if (!$product || !is_a($product, 'WC_Product')) {
                        $product = wc_get_product(get_the_ID());
                    }
                    
                    if (!$product) {
                        continue;
                    }
                ?>
                    <li <?php wc_product_class('', $product); ?>>
                        <a href="<?php the_permalink(); ?>" class="woocommerce-loop-product__link">
                            <?php 
                            // Product image
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('woocommerce_thumbnail');
                            } else {
                                echo '<div class="placeholder-image"></div>';
                            }
                            ?>
                            
                            <h2 class="woocommerce-loop-product__title"><?php the_title(); ?></h2>
                        </a>
                        
                        <?php 
                        // Show price if available
                        if ($product->get_price()) {
                            echo '<div class="product-price">';
                            woocommerce_template_loop_price();
                            echo '</div>';
                        }
                        ?>
                        
                        <div class="product-btn-wrapper">
                            <a href="<?php the_permalink(); ?>" class="view-products-btn">
                                View Product
                            </a>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </section>

    <?php
    // Pagination
    woocommerce_pagination();
    ?>

    <?php else: ?>
        <?php if (!$has_subcategories): ?>
            <section class="category-products-section">
                <div class="category-products-container">
                    <div class="no-products-section">
                        <p>No products found in this category.</p>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>

</main>

<script>
// Make category cards clickable
document.addEventListener('DOMContentLoaded', function() {
    const categoryCards = document.querySelectorAll('.category-card');
    
    categoryCards.forEach(function(card) {
        card.addEventListener('click', function(e) {
            // Don't follow link if clicking the button directly
            if (!e.target.classList.contains('view-products-btn')) {
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
get_footer();
?>