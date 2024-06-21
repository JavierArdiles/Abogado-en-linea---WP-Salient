<?php

$related = get_posts([
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post__not_in' => [$args['post']->ID],
    'tax_query' => [
        'relation' => 'OR',
        [
            'taxonomy' => 'category',
            'terms' => wp_get_post_terms($args['post']->ID, 'category', ['fields' => 'ids']),
            'fields' => 'term_id',
        ],
    ],
    'orderby' => 'date',
]);

if (!empty($related)) {

?>

    <div class="blog-post-related">
        <h2 class="blog-post-related__title text-title-secondary color-grey49 margin-cero">
            Artículos relacionados
        </h2>
        <div class="blog-post-related__list blog-grid__list">

            <?php

            foreach ($related as $rel) {
                get_template_part('inc/views/templates/temp', 'blog-post-preview', ['post' => $rel]);
            }

            ?>

        </div>
    </div>

<?php

}
