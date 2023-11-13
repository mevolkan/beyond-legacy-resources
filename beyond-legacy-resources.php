<?php
/*
 * Plugin Name: Resource Post Type by Volkan
 * Description: This plugin adds a Resource Post Type to your WordPress website.
 * Version: 1.0.0
 * Author: Samuel Nzaro
 * Author URI: https://github.com/mevolkan
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
	}

if (!class_exists('Beyond_Legacy_Resources')) {
	class Beyond_Legacy_Resources
		{

		}
	}

/**
 * Registers a custom post type 'resource'.
 *
 * @since 1.0.0
 *
 * @return void
 */

function bl_register_resource(): void
	{
	$labels = [
		'name' => _x('Resource', 'Post Type General Name', 'resource'),
		'singular_name' => _x('Resource', 'Post Type Singular Name', 'resource'),
		'menu_name' => __('Resources', 'resource'),
		'name_admin_bar' => __('Resources', 'resource'),
		'archives' => __('Resources Archives', 'resource'),
		'attributes' => __('Resources Attributes', 'resource'),
		'parent_item_colon' => __('Parent Resource:', 'resource'),
		'all_items' => __('All Resources', 'resource'),
		'add_new_item' => __('Add New Resource', 'resource'),
		'add_new' => __('Add New', 'resource'),
		'new_item' => __('New Resource', 'resource'),
		'edit_item' => __('Edit Resource', 'resource'),
		'update_item' => __('Update Resource', 'resource'),
		'view_item' => __('View Resource', 'resource'),
		'view_items' => __('View Resources', 'resource'),
		'search_items' => __('Search Resources', 'resource'),
		'not_found' => __('Resource Not Found', 'resource'),
		'not_found_in_trash' => __('Resource Not Found in Trash', 'resource'),
		'featured_image' => __('Featured Image', 'resource'),
		'set_featured_image' => __('Set Featured Image', 'resource'),
		'remove_featured_image' => __('Remove Featured Image', 'resource'),
		'use_featured_image' => __('Use as Featured Image', 'resource'),
		'insert_into_item' => __('Insert into Resource', 'resource'),
		'uploaded_to_this_item' => __('Uploaded to this Resource', 'resource'),
		'items_list' => __('Resources List', 'resource'),
		'items_list_navigation' => __('Resources List Navigation', 'resource'),
		'filter_items_list' => __('Filter Resources List', 'resource'),
	];
	$labels = apply_filters('resource-labels', $labels);

	$args = [
		'label' => __('Resource', 'resource'),
		'description' => __('Resource Description', 'resource'),
		'labels' => $labels,
		'supports' => [
			'title',
			'editor',
			'author',
			'excerpt',
			'thumbnail',
			'revisions',
		],
		'taxonomies' => [
			'category',
			'post_tag',
		],
		'hierarchical' => true,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-media-text',
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'can_export' => true,
		'capability_type' => 'page',
		'show_in_rest' => true,
		'rest_base' => 'resource',
	];
	$args = apply_filters('resource-args', $args);

	register_post_type('resource', $args);
	}

function add_url_metabox()
	{
	add_meta_box('resource_url_metabox', 'Resource URL', 'render_resource_url_metabox', 'resource', 'normal', 'high');
	}
add_action('add_meta_boxes', 'add_url_metabox');

function render_resource_url_metabox($post)
	{
	// Retrieve the saved URL, if any
	$resource_url = get_post_meta($post->ID, '_resource_url', true);
	?>
	<label for="resource-url">Enter a Custom URL:</label>
	<input type="text" id="resource-url" name="resource_url" value="<?php echo esc_attr($resource_url); ?>">
	or Pick from Media Library:
	<input type="button" id="upload-media-button" class="button" value="Pick from Media Library">
	<?php
	}

function save_resource_url_metabox($post_id)
	{
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	if (!current_user_can('edit_post', $post_id))
		return;

	if (isset($_POST['resource_url'])) {
		update_post_meta($post_id, '_resource_url', sanitize_text_field($_POST['resource_url']));
		}
	}
add_action('save_post', 'save_resource_url_metabox');

add_action('admin_enqueue_scripts', 'load_wp_media_files');
function load_wp_media_files()
	{
	// change to the $page where you want to enqueue the script
	//   if( $page == 'options-general.php' ) {
	// Enqueue WordPress media scripts
	wp_enqueue_media();
	// Enqueue custom script that will interact with wp.media
	wp_enqueue_script('resources_script', plugins_url('/js/mediafiles.js', __FILE__), array('jquery'), '0.1');
	//   }
	}

function add_resource_price_meta_box()
	{
	add_meta_box('resource_price_meta', 'Resource Price', 'render_resource_price_meta_box', 'resource', 'normal', 'high');
	}
add_action('add_meta_boxes', 'add_resource_price_meta_box');

function render_resource_price_meta_box($post)
	{
	// Retrieve the saved price, if any
	$resource_price = get_post_meta($post->ID, '_resource_price', true);
	?>
	<label for="resource-price">Resource Price:</label>
	<input type="text" id="resource-price" name="resource_price" value="<?php echo esc_attr($resource_price); ?>">
	<?php
	}

function save_resource_price_meta_box($post_id)
	{
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	if (!current_user_can('edit_post', $post_id))
		return;

	if (isset($_POST['resource_price'])) {
		update_post_meta($post_id, '_resource_price', sanitize_text_field($_POST['resource_price']));
		}
	}
add_action('save_post', 'save_resource_price_meta_box');

/* Filter the single_template with our custom function*/

function load_resource_single_template($template)
	{
	if (is_single() && get_post_type() === 'resource') {
		$theme_file = locate_template(array('single-resource.php'));
		if ($theme_file) {
			return $theme_file;
			} else {
			return plugin_dir_path(__FILE__) . '/includes/templates/single-resource.php';
			}
		}
	return $template;
	}

add_filter('template_include', 'load_resource_single_template');

function load_resource_archive_template($template)
	{
	if (is_post_type_archive('resource')) {
		$theme_file = locate_template(array('archive-resource.php'));
		if ($theme_file) {
			return $theme_file;
			} else {
			return plugin_dir_path(__FILE__) . '/includes/templates/archive-resource.php';
			}
		}
	return $template;
	}

add_filter('template_include', 'load_resource_archive_template');

add_action('init', 'bl_register_resource', 0);

function resource_user_scripts()
	{
	$plugin_url = plugin_dir_url(__FILE__);

	wp_enqueue_style('resource_style', $plugin_url . "/css/styles.css");
	}

add_action('wp_enqueue_scripts', 'resource_user_scripts');

function resource_loop_shortcode($atts)
	{
	global $wp_query;
	ob_start(); // Start output buffering

	$atts = shortcode_atts(
		array(
			'count' => 6,
			//posts per page
			'limit' => 6,
			'columns' => 3
		),
		$atts
	);
	$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

	$args = array(
		'post_type' => 'resource',
		'posts_per_page' => $atts['count'],
		'paged' => $paged,
	);
	$loop = new WP_Query($args);

	if ($loop->have_posts()) {
		echo '<div class="row resources">';
		while ($loop->have_posts()) {
			$loop->the_post();
			if ('' === locate_template('includes/templates/partials/loop.php', true, false)) {
				include('includes/templates/partials/loop.php');
				}
			get_template_part('includes/templates/partials/loop');
			}
		echo '</div>';

		echo '<div class="pagination">';
		$big = 999999999; // need an unlikely integer
		echo paginate_links(
			array(
				// 'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'total' => $loop->max_num_pages,
				'current' => max(1, get_query_var('paged')),
				'format' => '?paged=%#%',
				'prev_text' => 'Previous',
				'next_text' => 'Next',
			)
		);
		echo '</div>';
		}

	wp_reset_postdata();

	return ob_get_clean(); // Return the buffered output
	wp_reset_query();
	}
add_shortcode('resource-loop', 'resource_loop_shortcode');

function add_resources_page_template($templates)
	{
	$templates[plugin_dir_path(__FILE__) . 'includes/templates/resources-template.php'] = __('Resource Page Template', 'resource');
	return $templates;
	}
add_filter('theme_page_templates', 'add_resources_page_template');

function combined_resources_loop($atts)
	{
	global $wp_query;
	ob_start(); // Start output buffering

	$atts = shortcode_atts(
		array(
			'count' => 12,
			//posts per page
			'limit' => 12,
			'columns' => 3,
		),
		$atts
	);
	$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : 1;

	$args = array(
		'post_type' => array('resource', 'post'),
		'posts_per_page' => $atts['count'],
		'paged' => $paged,
	);
	$query = new WP_Query($args);
	include('includes/templates/partials/resources-filter.php');
	if ($query->have_posts()) {
		echo '<div class="row resources filtered-results">';
		while ($query->have_posts()) {
			$query->the_post();
			if ('' === locate_template('includes/templates/partials/loop.php', true, false)) {
				include('includes/templates/partials/loop.php');
				}
			get_template_part('includes/templates/partials/loop');
			}
		echo '</div>';

		echo '<div class="pagination">';
		$big = 999999999; // need an unlikely integer
		echo paginate_links(
			array(
				// 'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'total' => $query->max_num_pages,
				'current' => max(1, get_query_var('paged')),
				'format' => '?paged=%#%',
				'prev_text' => 'Previous',
				'next_text' => 'Next',
			)
		);
		echo '</div>';
		}

	wp_reset_postdata();

	return ob_get_clean(); // Return the buffered output
	wp_reset_query();
	}
add_shortcode('combined-loop', 'combined_resources_loop');

//Ajax Filter
function filter_resources()
	{
	$filter = sanitize_text_field($_POST['filter']); // Sanitize the filter input

	if (in_array($filter, array('all', 'blogs'))) {
		$args = array(
			'post_type' => ($filter === 'all') ? array('post', 'resource') : ($filter === 'blogs' ? 'post' : $filter),
			'posts_per_page' => -1
		);
		}

	if (in_array($filter, array('audio', 'ebook', 'video'))) {
		$tax_query[] = array(
			array(
				'taxonomy' => 'category',
				'field' => 'slug',
				'terms' => $filter,
			),
		);
		$args = array(
			'post_type' => array('resource', 'post'),
			'posts_per_page' => -1,
			'tax_query' => $tax_query
		);
		}

	$query = new WP_Query($args);

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			if ('' === locate_template('includes/templates/partials/loop.php', true, false)) {
				include('includes/templates/partials/loop.php');
				}
			get_template_part('includes/templates/partials/loop');
			}
		} else {
		echo 'No Resource found';
		}

	wp_die();
	}
add_action('wp_ajax_filter_resources', 'filter_resources');
add_action('wp_ajax_nopriv_filter_resources', 'filter_resources');


function localize_ajax_url()
	{
	wp_enqueue_script('resources_script', plugins_url('/js/resources-filter.js', __FILE__), array('jquery'), '0.1');
	wp_localize_script(
		'resources_script',
		'ajaxUrl',
		array(
			'ajaxurl' => admin_url('admin-ajax.php'),
		)
	);
	}
add_action('wp_enqueue_scripts', 'localize_ajax_url');

