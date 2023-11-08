<?php
/*
Template Name: Full-width page layout
Template Post Type: resource
*/

get_header('review');
?>
<div id="" class="container-wrap">
    <main id="main" class="container" role="main">

        <?php
        while (have_posts()):
            the_post();

            // Include your custom single post content here
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="resource-image">
                    <?php
                    // Display the post thumbnail
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('medium');
                        }
                    ?>
                </div>
                <div class="resource-content">
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <div class="entry-footer">
                        <?php // Tags
                            $tags = get_the_tags();
                            if (!empty($tags)) {
                                echo '<p>Tag';
                                foreach ($tags as $tag) {
                                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a> ';

                                    }
                                echo '</p>';
                                } ?>
                    </div>
                    <div>
                        <?php
                        // Categories
                        $categories = get_the_category();
                        if (!empty($categories)) {
                            echo '<p>Type ';
                            foreach ($categories as $category) {
                                // echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a> ';
                                echo '<span class="bold">' . esc_html($category->name) . '</span> ';
                                }
                            echo '</p>';
                            }
                        // Custom price field
                        $resource_price = get_post_meta(get_the_ID(), '_resource_price', true);
                        if (!empty($resource_price)) {
                            echo '<p class="resource-price">Price: ' . esc_html($resource_price) . '</p>';
                            } else {
                            echo '<p class="resource-price">Free</p>';
                            }

                        $resource_url = get_post_meta(get_the_ID(), '_resource_url', true);
                        if (!empty($resource_url)) {
                            echo '<a class="nectar-button medium regular accent-color  regular-button" role="button" style="color: rgb(10, 49, 53); visibility: visible;" href="#" data-color-override="false" data-hover-color-override="false" data-hover-text-color-override="#fff" href="' . esc_url($resource_url) . '">Visit Resource</a>';
                            }
                        ?>
                    </div>
                </div>

            </article>

            <?php
        endwhile;
        ?>

    </main>
</div>

<?php
get_footer();
