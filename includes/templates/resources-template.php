<?php
/*
Template Name: Resource Page template
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header(); // Include your header template


?>

<section id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <header class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
        </header>

        <div class="entry-content">
            <?php
            // Page content goes here
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>

    </main>
</section>

<div class="custom-template-additional-content">
    <h2>Additional Content</h2>
    <p>This content is added after the page content and can be customized as needed.</p>
</div>

<?php get_footer(); // Include your footer template ?>