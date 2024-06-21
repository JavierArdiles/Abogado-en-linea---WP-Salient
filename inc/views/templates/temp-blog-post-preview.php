<?php

use ClazzyStudioChildTheme\CustomFields\BlogPostAuthor;

$categories = wp_get_post_categories($args['post']->ID, ['fields' => 'names']);
$author = get_field(BlogPostAuthor::AUTHOR, $args['post']->ID);
$authorImg = get_field(BlogPostAuthor::IMAGE, $args['post']->ID);

?>

<div class="blog-post-preview">
    <a class="blog-post-preview__link" href="<?php echo get_the_permalink($args['post']->ID); ?>" title="Ver nota"></a>
    <div class="blog-post-preview__thumbnail-cont">
        <?php echo get_the_post_thumbnail($args['post']->ID); ?>
    </div>
    <div class="blog-post-preview__text">

        <?php

        if (!empty($categories)) {

        ?>

            <div class="blog-post-preview__categories blog-categories">

                <?php

                foreach ($categories as $cat) {

                ?>

                    <p class="blog-post-preview__category blog-category">
                        <?php echo $cat; ?>
                    </p>

                <?php

                }

                ?>

            </div>

        <?php

        }

        ?>

        <h3 class="blog-post-preview__title">
            <?php echo $args['post']->post_title; ?>
        </h3>
        <div class="blog-post-preview__meta">
            <p class="author">

                <?php

                if (!empty($authorImg)) {

                ?>

                    <img src="<?php echo $authorImg['url']; ?>" alt="<?php echo $author; ?>" />

                <?

                } else {

                ?>

            <span class="default-author"></span>

        <?php

                }
                echo $author;

        ?>

        </p>
        <p class="date">
            <?php echo get_the_date('j', $args['post']->ID) . ' de ' . get_the_date('F, Y', $args['post']->ID); ?>
        </p>
        </div>
    </div>
</div>