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

// ------------------ ACF plugin code --------------------------
if ( ! function_exists( 'is_plugin_active' ) ) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Check if ACF PRO is active
if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) {
    // Abort all bundling, ACF PRO plugin takes priority
    return;
}
//--
// Check if another plugin or theme has bundled ACF
if ( defined( 'MY_ACF_PATH' ) ) {
    return;
}
//--
// Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', get_stylesheet_directory() . '/includes/acf/' );
define( 'MY_ACF_URL', get_stylesheet_directory_uri() . '/includes/acf/' );
//--
// Include the ACF plugin.
include_once( MY_ACF_PATH . 'acf.php' );

// Customize the URL setting to fix incorrect asset URLs.
add_filter('acf/settings/url', 'my_acf_settings_url');
function my_acf_settings_url( $url ) {
    return MY_ACF_URL;
}
//--
// Check if the ACF free plugin is activated
if ( is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
    // Free plugin activated
    // Free plugin activated, show notice
    add_action( 'admin_notices', function () {
        ?>
        <div class="updated" style="border-left: 4px solid #ffba00;">
            <p>The ACF plugin cannot be activated at the same time as Third-Party Product and has been deactivated. Please keep ACF installed to allow you to use ACF functionality.</p>
        </div>
        <?php
    }, 99 );

    // Disable ACF free plugin
    deactivate_plugins( 'advanced-custom-fields/acf.php' );
}
//--
// Check if ACF free is installed
if ( ! file_exists( WP_PLUGIN_DIR . '/advanced-custom-fields/acf.php' ) ) {
    // Free plugin not installed
    // Hide the ACF admin menu item.
    add_filter( 'acf/settings/show_admin', '__return_false' );
    // Hide the ACF Updates menu
    add_filter( 'acf/settings/show_updates', '__return_false', 100 );
}

// ------------------ ACF plugin code --------------------------

// Register 'projects' custom post type
function mytheme_register_projects_cpt() {
    $labels = [
        'name'               => 'Projects',
        'singular_name'      => 'Project',
        'menu_name'          => 'Projects',
        'name_admin_bar'     => 'Project',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Project',
        'new_item'           => 'New Project',
        'edit_item'          => 'Edit Project',
        'view_item'          => 'View Project',
        'all_items'          => 'All Projects',
        'search_items'       => 'Search Projects',
        'not_found'          => 'No projects found.',
        'not_found_in_trash' => 'No projects found in Trash.',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'show_in_rest'       => true, 
        'supports'           => ['title', 'editor', 'thumbnail'],
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-portfolio',
        'rewrite'            => ['slug' => 'projects'],
    ];

    register_post_type('projects', $args);
}
add_action('init', 'mytheme_register_projects_cpt');

// Create demo projects on theme activation
add_action('after_switch_theme', 'mytheme_create_demo_projects');

function mytheme_create_demo_projects() {
    if (get_option('mytheme_demo_projects_created')) return;

    $projects = [
        ['title' => 'WordPress Project', 'type' => 'WordPress'],
        ['title' => 'Software Project',  'type' => 'Software'],
        ['title' => 'AI Project',        'type' => 'AI'],
    ];

    foreach ($projects as $proj) {
        $post_id = wp_insert_post([
            'post_title'   => $proj['title'],
            'post_status'  => 'publish',
            'post_type'    => 'projects',
            'post_author'  => 1,
        ]);

        if ($post_id) {
            // Set the ACF field
            if (function_exists('update_field')) {
                update_field('type', $proj['type'], $post_id);
            }
        }
    }

    update_option('mytheme_demo_projects_created', true);
}

// Custom ACF Field called 'type'
add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_68ae7dff75de0',
	'title' => 'Type',
	'fields' => array(
		array(
			'key' => 'field_68ae7dff506c3',
			'label' => 'Type',
			'name' => 'type',
			'aria-label' => '',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'WordPress' => 'WordPress',
				'Software' => 'Software',
				'AI' => 'AI',
			),
			'default_value' => false,
			'return_format' => 'value',
			'multiple' => 0,
			'allow_null' => 0,
			'allow_in_bindings' => 0,
			'ui' => 0,
			'ajax' => 0,
			'placeholder' => '',
			'create_options' => 0,
			'save_options' => 0,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'projects',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );
} );

