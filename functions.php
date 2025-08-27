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

