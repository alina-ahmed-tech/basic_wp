<?php get_header() ?>

<?php
    while(have_posts()) {
        the_post(); ?>
        <div>
            <h2><a href="<?php the_permalink()?>"><?php the_title() ?></a></h2>
        </div>
    <?php }
?>

<p>type: <?php echo esc_html ( get_field('type') ); ?></p>

<p>Footer</p>