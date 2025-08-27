<?php

function displayTabTitle() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme','displayTabTitle');

function myfiles() {
    wp_enqueue_style('theme-style', get_stylesheet_uri());
    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() .'/main.js',
    );
}
add_action('wp_enqueue_scripts','myfiles');


// Hook into theme activation
add_action('after_switch_theme', 'mytheme_create_demo_posts');

function mytheme_create_demo_posts() {
    // Check if demo posts already exist to prevent duplicates
    if (get_option('mytheme_demo_posts_created')) {
        return;
    }

    // Post 1
    $post1_id = wp_insert_post([
        'post_title'    => 'Responsive Web Design Principles',
        'post_content'  => 'This is a demo post about responsive web design principles. Here you can add any example content you want.',
        'post_status'   => 'publish',
        'post_author'   => 1, 
        'post_category' => [1],
    ]);

    // Post 2
    $post2_id = wp_insert_post([
        'post_title'    => 'Web Optimisation for Performance, Speed and SEO',
        'post_content'  => 'This is a demo post about optimizing your website for performance, speed, and SEO. Add your content here.',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_category' => [1],
    ]);

    // Mark demo posts as created
    if ($post1_id && $post2_id) {
        update_option('mytheme_demo_posts_created', true);
    }
}