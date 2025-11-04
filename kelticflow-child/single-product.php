<?php
/**
 * Single Product Template - FINAL WORKING VERSION
 * - Uses .mobx and data-rel for ModuloBox
 * - Skips first image in hidden links (avoid duplicate)
 * - Adds data-thumb for lightbox thumbnails
 */

if (!is_user_logged_in()) {
    ini_set('display_errors', 0);
}

get_header();

if (!have_posts()) {
    ?>
    <main class="site-main">
        <div class="product-page-container">
            <p>Product not found.</p>
        </div>
    </main>
    <?php
    get_footer();
    exit;
}

while (have_posts()): 
    the_post();
    
    $product_id = get_the_ID();
    
    global $product;
    if (!$product || !is_a($product, 'WC_Product')) {
        $product = wc_get_product($product_id);
    }
    
    if (!$product) {
        ?>
        <main class="site-main">
            <div class="product-page-container">
                <p>Unable to load product details.</p>
            </div>
        </main>
        <?php
        continue;
    }
    
    // Get ACF fields
    $data_sheet_pdf = null;
    $dimension_drawing = null;
    $fitting_instruction = null;
    $product_code = null;
    $finish = null;
    $powder_coating = null;
    $material = null;
    $en_standard = null;
    $height = null;
    $width = null;
    $pipe_centres = null;
    $bars_pcs = null;
    $btu_output = null;
    
    if (function_exists('get_field')) {
        try {
            $data_sheet_pdf = get_field('data_sheet_pdf', $product_id);
            $dimension_drawing = get_field('dimension_drawing', $product_id);
            $fitting_instruction = get_field('fitting_instruction', $product_id);
            $product_code = get_field('product_code', $product_id);
            $finish = get_field('finish', $product_id);
            $powder_coating = get_field('powder_coating', $product_id);
            $material = get_field('material', $product_id);
            $en_standard = get_field('en_standard', $product_id);
            $height = get_field('height', $product_id);
            $width = get_field('width', $product_id);
            $pipe_centres = get_field('pipe_centres', $product_id);
            $bars_pcs = get_field('bars_pcs', $product_id);
            $btu_output = get_field('btu_output', $product_id);
        } catch (Exception $e) {
        }
    }
    
    if (empty($product_code) && $product) {
        $product_code = $product->get_sku();
    }
?>

<main id="main" class="site-main single-product-page">
    <?php 
    if (function_exists('custom_breadcrumbs')) {
        custom_breadcrumbs(); 
    }
    ?>
    
    <div class="product-page-container">
        
        <!-- Product Image Gallery -->
        <div class="product-image-section">
            <?php
            $attachment_ids = array();
            
            if (has_post_thumbnail()) {
                $attachment_ids[] = get_post_thumbnail_id();
            }
            
            if ($product) {
                $gallery_ids = $product->get_gallery_image_ids();
                if ($gallery_ids) {
                    $attachment_ids = array_merge($attachment_ids, $gallery_ids);
                }
            }
            ?>
            
            <?php if (!empty($attachment_ids)): ?>
                <!-- Main Image -->
                <div class="product-main-image-wrapper">
                    <?php
                    $main_image_id = $attachment_ids[0];
                    $main_image_url = wp_get_attachment_image_url($main_image_id, 'large');
                    $main_image_full = wp_get_attachment_image_url($main_image_id, 'full');
                    $main_thumb = wp_get_attachment_image_url($main_image_id, 'thumbnail');
                    $image_alt = get_post_meta($main_image_id, '_wp_attachment_image_alt', true);
                    $image_title = get_the_title($main_image_id);
                    ?>
                    <a href="<?php echo esc_url($main_image_full); ?>" 
                       class="mobx"
                       data-rel="product-gallery"
                       data-title="<?php echo esc_attr($image_title ? $image_title : get_the_title()); ?>"
                       data-thumb="<?php echo esc_url($main_thumb); ?>">
                        <img src="<?php echo esc_url($main_image_url); ?>" 
                             alt="<?php echo esc_attr($image_alt ? $image_alt : get_the_title()); ?>" 
                             class="product-main-image"
                             id="mainProductImage">
                        <div class="image-zoom-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                <line x1="11" y1="8" x2="11" y2="14"></line>
                                <line x1="8" y1="11" x2="14" y2="11"></line>
                            </svg>
                        </div>
                    </a>
                </div>
                
                <!-- Thumbnail Gallery -->
                <?php if (count($attachment_ids) > 1): ?>
                    <div class="product-thumbnails">
                        <?php foreach ($attachment_ids as $index => $attachment_id): 
                            $thumb_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                            $large_url = wp_get_attachment_image_url($attachment_id, 'large');
                            $full_url = wp_get_attachment_image_url($attachment_id, 'full');
                            $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                            $image_title = get_the_title($attachment_id);
                            $active_class = ($index === 0) ? 'active' : '';
                        ?>
                            <div class="product-thumbnail-wrapper <?php echo $active_class; ?>" 
                                 data-large="<?php echo esc_url($large_url); ?>"
                                 data-full="<?php echo esc_url($full_url); ?>"
                                 data-thumb="<?php echo esc_url($thumb_url); ?>"
                                 data-index="<?php echo $index; ?>">
                                <?php 
                                // Only add hidden ModuloBox link for images AFTER the first one
                                // (First image is already the main image, don't duplicate it)
                                if ($index > 0): 
                                ?>
                                    <a href="<?php echo esc_url($full_url); ?>" 
                                       class="mobx thumbnail-mobx-link"
                                       data-rel="product-gallery"
                                       data-title="<?php echo esc_attr($image_title ? $image_title : get_the_title()); ?> - Image <?php echo ($index + 1); ?>"
                                       data-thumb="<?php echo esc_url($thumb_url); ?>"
                                       style="position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none;">
                                    </a>
                                <?php endif; ?>
                                <!-- Visible Thumbnail -->
                                <img src="<?php echo esc_url($thumb_url); ?>" 
                                     alt="<?php echo esc_attr($image_alt ? $image_alt : get_the_title()); ?>"
                                     class="thumbnail-image"
                                     data-index="<?php echo $index; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
            <?php else: ?>
                <div class="product-main-image-wrapper">
                    <div class="product-placeholder-image">
                        <svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="200" height="200" fill="#e0e0e0"/>
                            <path d="M70 80L90 100L110 80L130 100L150 80" stroke="#999" stroke-width="2"/>
                            <circle cx="80" cy="70" r="8" fill="#999"/>
                        </svg>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Product Details -->
        <div class="product-details-section">
            
            <h1 class="product-title"><?php the_title(); ?></h1>
            
            <?php if (!empty($product_code) || !empty($finish) || !empty($powder_coating) || !empty($material) || !empty($en_standard)): ?>
            <div class="product-details-wrapper">
            <div class="product-info-block">
                <h2 class="section-heading">Product Information</h2>
                
                <?php if (!empty($product_code)): ?>
                    <p class="product-info-item">
                        <strong>Code:</strong> <?php echo esc_html($product_code); ?>
                    </p>
                <?php endif; ?>
                
                <?php if (!empty($finish)): ?>
                    <p class="product-info-item">
                        <strong>Finish:</strong> <?php echo esc_html($finish); ?>
                    </p>
                <?php endif; ?>
                
                <?php if (!empty($powder_coating)): ?>
                    <p class="product-info-item">
                        <strong>Powder Coating:</strong> <?php echo esc_html($powder_coating); ?>
                    </p>
                <?php endif; ?>
                
                <?php if (!empty($material)): ?>
                    <p class="product-info-item">
                        <strong>Material:</strong> <?php echo esc_html($material); ?>
                    </p>
                <?php endif; ?>
                
                <?php if (!empty($en_standard)): ?>
                    <p class="product-info-item">
                        <strong><?php echo esc_html($en_standard); ?></strong>
                    </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($height) || !empty($width) || !empty($pipe_centres) || !empty($bars_pcs) || !empty($btu_output)): ?>
            <div class="product-dimension-block">
                <h2 class="section-heading">Product Dimension</h2>
                
                <?php if (!empty($height)): ?>
                    <p class="product-info-item">
                        <strong>Height:</strong> <?php echo esc_html($height); ?>
                    </p>
                <?php endif; ?>
                
                <?php if (!empty($width)): ?>
                    <p class="product-info-item">
                        <strong>Width:</strong> <?php echo esc_html($width); ?>
                    </p>
                <?php endif; ?>
                
                <?php if (!empty($pipe_centres)): ?>
                    <p class="product-info-item">
                        <strong>Pipe Centres:</strong> <?php echo esc_html($pipe_centres); ?>
                    </p>
                <?php endif; ?>
                
                <?php if (!empty($bars_pcs)): ?>
                    <p class="product-info-item">
                        <strong>Bars/Pcs:</strong> <?php echo esc_html($bars_pcs); ?>
                    </p>
                <?php endif; ?>
                
                <?php if (!empty($btu_output)): ?>
                    <p class="product-info-item">
                        <strong>BTU/gdt50:</strong> <?php echo esc_html($btu_output); ?>
                    </p>
                <?php endif; ?>
            </div>
            </div>
            <?php endif; ?>
            
            <?php 
            $has_downloads = false;
            $data_sheet_url = '';
            $dimension_url = '';
            $fitting_url = '';
            
            if (is_array($data_sheet_pdf) && isset($data_sheet_pdf['url'])) {
                $has_downloads = true;
                $data_sheet_url = $data_sheet_pdf['url'];
            }
            if (is_array($dimension_drawing) && isset($dimension_drawing['url'])) {
                $has_downloads = true;
                $dimension_url = $dimension_drawing['url'];
            }
            if (is_array($fitting_instruction) && isset($fitting_instruction['url'])) {
                $has_downloads = true;
                $fitting_url = $fitting_instruction['url'];
            }
            
            if ($has_downloads): 
            ?>
            <div class="product-downloads-block">
                <h2 class="section-heading">Product Downloads</h2>
                
                <div class="download-buttons">
                    <?php if (!empty($data_sheet_url)): ?>
                        <a href="<?php echo esc_url($data_sheet_url); ?>" 
                           class="download-btn" 
                           download 
                           target="_blank"
                           rel="noopener noreferrer">
                            Data Sheet PDF
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($dimension_url)): ?>
                        <a href="<?php echo esc_url($dimension_url); ?>" 
                           class="download-btn" 
                           download 
                           target="_blank"
                           rel="noopener noreferrer">
                            Dimension Drawing
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($fitting_url)): ?>
                        <a href="<?php echo esc_url($fitting_url); ?>" 
                           class="download-btn" 
                           download 
                           target="_blank"
                           rel="noopener noreferrer">
                            Fitting Instruction
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
        </div>
        
    </div>
    
</main>

<?php
endwhile;

get_footer();
?>