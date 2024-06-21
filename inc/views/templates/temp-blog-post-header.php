<?php

use ClazzyStudioChildTheme\CustomFields\BlogPostAuthor;

$categories = wp_get_post_categories($args['post']->ID, ['fields' => 'names']);
$author = get_field(BlogPostAuthor::AUTHOR, $args['post']->ID);
$authorImg = get_field(BlogPostAuthor::IMAGE, $args['post']->ID);

?>

<div class="blog-post-header">
    <div class="blog-post-header__thumbnail-cont absolute-center z-1">
        <?php echo get_the_post_thumbnail($args['post']->ID); ?>
    </div>
    <div class="blog-post-header__text z1 pos-rel">

        <?php

        if (!empty($categories)) {

        ?>

            <div class="blog-categories blog-post-header__categories">

                <?php

                foreach ($categories as $cat) {

                ?>

                    <p class="blog-category blog-post-header__category">
                        <?php echo $cat; ?>
                    </p>

                <?php

                }

                ?>

            </div>

        <?php

        }

        ?>

        <h1 class="blog-post-header__title text-title-secondary color-white ta-c margin-cero">
            <?php echo $args['post']->post_title; ?>
        </h1>
        <p class="blog-post-header__meta text-subtitle-small color-white padding-cero">
            <span class="author fw-500">
                <span class="image">

                    <?php

                    if (!empty($authorImg)) {

                    ?>

                        <img src="<?php echo $authorImg['url']; ?>" alt="<?php echo $authorImg['alt']; ?>" />

                    <?php

                    }

                    ?>

                </span>
                <?php echo $author; ?>
            </span>
            <span class="date">
            <?php echo get_the_date('j', $args['post']->ID) . ' de ' . get_the_date('F, Y', $args['post']->ID); ?>
            </span>
        </p>
    </div>
</div>