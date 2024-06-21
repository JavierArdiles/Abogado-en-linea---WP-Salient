<?php
/**
* The template for displaying pages.
*
* @package Salient WordPress Theme
* @version 13.0
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
$nectar_fp_options = nectar_get_full_page_options();

?>
<div class="container-wrap">
	<div class="<?php if ( $nectar_fp_options['page_full_screen_rows'] !== 'on' ) { echo 'container'; } ?> main-content" role="main">
		<div class="row">
			<?php

			nectar_hook_before_content();

			if ( have_posts() ) :
				while ( have_posts() ) :

					the_post();

					get_template_part('inc/views/templates/temp', 'blog-post-header', ['post' => $post]);

					?>

					<div class="blog-post-content color-grey49">
						<?php the_content(); ?>
					</div>

					<?php

					get_template_part('inc/views/templates/temp', 'blog-post-related', ['post' => $post]);

				endwhile;
			endif;

			nectar_hook_after_content();

			?>
		</div>
	</div>
	<?php nectar_hook_before_container_wrap_close(); ?>
</div>
<?php get_footer(); ?>
