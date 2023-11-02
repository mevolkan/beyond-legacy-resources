<?php
/*
 * Plugin Name: Resource Post Type by Volkan
 * Description: This plugin adds aResource Post Type to your WordPress website.
 * Version: 1.0.0
 * Author: Samuel Nzaro
 * Author URI: https://wpturbo.dev
 */

/**
 * Registers a custom post type 'BL_resource'.
 *
 * @since 1.0.0
 *
 * @return void
 */

function bl_register_resource(): void
{
	$labels = [
		'name' => _x('Resources', 'Post Type General Name', 'bl_resource'),
		'singular_name' => _x('Resource', 'Post Type Singular Name', 'bl_resource'),
		'menu_name' => __('Resources', 'bl_resource'),
		'name_admin_bar' => __('Resources', 'bl_resource'),
		'archives' => __('Resources Archives', 'bl_resource'),
		'attributes' => __('Resources Attributes', 'bl_resource'),
		'parent_item_colon' => __('Parent Resource:', 'bl_resource'),
		'all_items' => __('All Resources', 'bl_resource'),
		'add_new_item' => __('Add New Resource', 'bl_resource'),
		'add_new' => __('Add New', 'bl_resource'),
		'new_item' => __('New Resource', 'bl_resource'),
		'edit_item' => __('Edit Resource', 'bl_resource'),
		'update_item' => __('Update Resource', 'bl_resource'),
		'view_item' => __('View Resource', 'bl_resource'),
		'view_items' => __('View Resources', 'bl_resource'),
		'search_items' => __('Search Resources', 'bl_resource'),
		'not_found' => __('Resource Not Found', 'bl_resource'),
		'not_found_in_trash' => __('Resource Not Found in Trash', 'bl_resource'),
		'featured_image' => __('Featured Image', 'bl_resource'),
		'set_featured_image' => __('Set Featured Image', 'bl_resource'),
		'remove_featured_image' => __('Remove Featured Image', 'bl_resource'),
		'use_featured_image' => __('Use as Featured Image', 'bl_resource'),
		'insert_into_item' => __('Insert into Resource', 'bl_resource'),
		'uploaded_to_this_item' => __('Uploaded to this Resource', 'bl_resource'),
		'items_list' => __('Resources List', 'bl_resource'),
		'items_list_navigation' => __('Resources List Navigation', 'bl_resource'),
		'filter_items_list' => __('Filter Resources List', 'bl_resource'),
	];
	$labels = apply_filters('BL_resource-labels', $labels);

	$args = [
		'label' => __('Resource', 'bl_resource'),
		'description' => __('My Post Type Description', 'bl_resource'),
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
			'post_tag'
		],
		'hierarchical' => true,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-admin-post',
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'can_export' => true,
		'capability_type' => 'page',
		'show_in_rest' => true,
		'rest_base' => 'bl_resource',
	];
	$args = apply_filters('BL_resource-args', $args);

	register_post_type('BL_resource', $args);
}

function add_url_metabox()
{
	add_meta_box('resource_url_metabox', 'Resource URL', 'render_resource_url_metabox', 'bl_resource', 'normal', 'high');
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
function load_wp_media_files($page)
{
	// change to the $page where you want to enqueue the script
	//   if( $page == 'options-general.php' ) {
	// Enqueue WordPress media scripts
	wp_enqueue_media();
	// Enqueue custom script that will interact with wp.media
	wp_enqueue_script('myprefix_script', plugins_url('/js/mediafiles.js', __FILE__), array('jquery'), '0.1');
	//   }
}

add_action('init', 'bl_register_resource', 0);