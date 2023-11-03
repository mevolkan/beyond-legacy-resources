<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

get_header();
$nectar_fp_options = nectar_get_full_page_options();
?>
<div class="container-wrap">
    <div class="<?php if ($nectar_fp_options['page_full_screen_rows'] !== 'on') {
                    echo 'container';
                } ?> main-content" role="main">
        <div class="<?php echo apply_filters('nectar_main_container_row_class_name', 'row resources'); ?>">
            <?php
            $args = array(
                'post_type' => 'resource',
                'posts_per_page' => 10,
            );
            $loop = new WP_Query($args);
            while ($loop->have_posts()) {
                $loop->the_post();
            ?>
                <div class="resource">
                    <h2>
                        <?php the_title(); ?>
                    </h2>

                    <?php
                    // Display the post thumbnail
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('thumbnail');
                    }
                    ?>

                    <?php the_excerpt(); ?>

                    <?php
                    // Custom URL field
                    $custom_url = get_post_meta(get_the_ID(), '_resource_url', true);
                    if (!empty($custom_url)) {
                        echo '<a href="' . esc_url($custom_url) . '">Visit Resource</a>';
                    }

                    // Custom price field
                    $resource_price = get_post_meta(get_the_ID(), '_resource_price', true);
                    if (!empty($resource_price)) {
                        echo '<p>Price: ' . esc_html($resource_price) . '</p>';
                    } else {
                        echo '<p>Free</p>';
                    }

                    // Categories
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        echo '<p>';
                        foreach ($categories as $category) {
                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a> ';
                        }
                        echo '</p>';
                    }

                    // Tags
                    $tags = get_the_tags();
                    if (!empty($tags)) {
                        echo '<p>';
                        foreach ($tags as $tag) {
                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a> ';
                        }
                        echo '</p>';
                    }
                    ?>
                </div>
            <?php
            }
            wp_reset_postdata(); // Reset the post data to the main loop.
            ?>
        </div>
    </div>
</div>
<?php
get_footer();
