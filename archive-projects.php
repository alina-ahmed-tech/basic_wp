<?php get_header(); ?>

<h1>Projects</h1>

<?php
if (have_posts()) {
    while (have_posts()) {
        the_post(); ?>
        
        <div>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p>Type: <?php echo esc_html( get_field('type') ); ?></p>
            <h3>OMGG</h3>
        </div>
        
    <?php }
}
?>

<?php get_footer(); ?>
