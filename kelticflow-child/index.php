<?php
/**
 * Main Index Template - Fallback for all pages
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="content-wrapper">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                    </header>
                    
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
                
            <?php endwhile; ?>
            
            <?php the_posts_pagination(); ?>
            
        <?php else : ?>
            
            <div class="no-content">
                <h1>Nothing Found</h1>
                <p>It looks like nothing was found at this location.</p>
            </div>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
?>