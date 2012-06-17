    <?php get_header(); ?>
    <body>  
    <div class="container">      
    <?php if ( have_posts() ) : the_post(); ?> 
    <div class="pushdown">
        <?php remove_filter (‘the_content’, ‘wpautop’); ?>
            <?php the_content(); ?>
        </div><!--pushdown-->
    <?php endif; ?>  
    </div><!--container-->  
    <?php get_footer(); ?>  