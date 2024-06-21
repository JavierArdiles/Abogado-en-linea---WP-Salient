<?php



?>

<div class="blog-grid">
    <div class="blog-grid__top-row">
        <h2 class="blog-grid__title text-title-secondary margin-cero color-grey49">
            <?php echo $args['title']; ?>
        </h2>
        <form name="blog-filter" action="<?php echo home_url($_SERVER['REQUEST_URI']) . '/#lista'; ?>" id="blog-filter" class="blog-grid__filter">
            <label class="blog-grid__filter__field blog-grid__filter__field--select">
                <select name="categoria" value="<?php echo $_GET['categoria'] ?? ''; ?>">
                    <option value="" default <?php echo isset($_GET['categoria']) ? '' : 'selected'; ?>>
                        Selecciona categoría
                    </option>

                    <?php

                    if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {

                    ?>

                        <option value="">
                            Todas
                        </option>

                    <?php

                    }

                    foreach ($args['categories'] as $cat) {

                    ?>

                        <option value="<?php echo $cat->slug; ?>" <?php echo isset($_GET['categoria']) && $_GET['categoria'] == $cat->slug ? 'selected' : ''; ?>>
                            <?php echo $cat->name; ?>
                        </option>

                    <?php

                    }

                    ?>

                </select>
            </label>
            <label class="blog-grid__filter__field blog-grid__filter__field--input">
                <button type="submit" class="submit">
                    <?php echo child_theme_get_svg('search'); ?>
                </button>
                <input name="busqueda" type="text" placeholder="Busca un articulo de ayuda" <?php echo isset($_GET['busqueda']) && !empty($_GET['busqueda']) ? 'value="' . $_GET['busqueda'] . '"' : ''; ?> />
            </label>
        </form>
    </div>
    <div class="blog-grid__list" id="lista">

        <?php

        foreach ($args['posts'] as $blogPost) {
            get_template_part('inc/views/templates/temp', 'blog-post-preview', ['post' => $blogPost]);
        }

        ?>

    </div>

    <?php

    if (count($args['posts']) < count($args['all_posts'])) {
        get_template_part('inc/views/templates/temp', 'load-more', ['css_class' => 'blog-grid__load-more']);
    }

    ?>

</div>