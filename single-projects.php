<?php get_header(); ?>

<?php
if (have_posts()) {
    while (have_posts()) {
        the_post(); ?>
        
        <article>
            <h1><?php the_title(); ?></h1>
            
            <div>
                <?php the_content(); ?>
            </div>
            
            <p><strong>Type:</strong> <?php echo esc_html( get_field('type') ); ?></p>
        </article>
        
    <?php }
}
?>

<?php get_footer(); ?>
