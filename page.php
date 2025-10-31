<?php
/**
 * General Page Template
 */

get_header();
?>

<main id="main" class="site-main">
     <?php custom_breadcrumbs(); ?>
    <?php
    while (have_posts()) :
        the_post();
        ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <?php if (has_post_thumbnail()): ?>
                <div class="page-featured-image">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
            
            <div class="page-content-wrapper">
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="page-content">
                    <?php the_content(); ?>
                </div>
            </div>
            
        </article>
        
    <?php endwhile; ?>
</main>

<?php
get_footer();
?>