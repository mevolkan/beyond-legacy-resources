<a class="resource" href="<?php echo esc_url(the_permalink()) ?>">
    <div class="resource-header">
        <?php
        // Display the post thumbnail
        if (has_post_thumbnail()) {
            the_post_thumbnail('medium');
            }
        ?>
    </div>
    <div class="resource-main">
        <span class="resource-title">
            <?php the_title(); ?>
        </span>
        <?php the_excerpt(); ?>
        <?php // Tags
        $tags = get_the_tags();
        if (!empty($tags)) {
            echo '<p>';
            foreach ($tags as $tag) {
                // echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a> ';
                echo esc_html($tag->name);
                }
            echo '</p>';
            } ?>
        <div class="resource-footer">
            <?php
            // Categories
            $categories = get_the_category();
            if (!empty($categories)) {
                echo '<p>';
                foreach ($categories as $category) {
                    // echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a> ';
                    echo '<span>' . esc_html($category->name) . '</span> ';
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
            ?>
        </div>
    </div>
</a>