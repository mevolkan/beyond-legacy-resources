<?php
/**
 * @file
 * Archive resource.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
$nectar_fp_options = nectar_get_full_page_options();
?>
<div class="container-wrap">
	<div class="
	<?php
	if ( 'on' !== $nectar_fp_options['page_full_screen_rows'] ) {
		echo 'container';
	}
	?>
		main-content" role="main">
		<div class="<?php echo wp_kses_post(apply_filters( 'nectar_main_container_row_class_name', 'row resources' ) );?>">
			<?php
			$args = array(
				'post_type'      => 'resource',
				'posts_per_page' => 10,
			);
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) {
				if ( '' === locate_template( 'partials/loop.php', true, false ) ) {
					include 'partials/loop.php';
				}
				$loop->the_post();
				get_template_part( 'partials/loop' );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</div>
<?php
get_footer();
