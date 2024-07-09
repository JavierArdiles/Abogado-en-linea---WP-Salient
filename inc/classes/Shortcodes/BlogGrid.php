<?php

namespace ClazzyStudioChildTheme\Shortcodes;

use ClazzyStudioChildTheme\Traits\HasViewTrait;
use ClazzyStudioChildTheme\Contracts\AbstractShortcode;
use ClazzyStudioChildTheme\Shared;

class BlogGrid extends AbstractShortcode
{
	use HasViewTrait;

	public static function shortcode(): string
	{
		return 'blog-grid';
	}

    public function init(): void
    {
        $this->getLoader()->add_shortcode($this->shortcode(), $this, 'handler');

        $this->getLoader()->add_action('wp_ajax_get_more_blog_posts', $this, 'GetMorePosts');
        $this->getLoader()->add_action('wp_ajax_nopriv_get_more_blog_posts', $this, 'GetMorePosts');
    }

	public function handler(mixed $atts) : ?string
	{
		$title = $atts['title'] ?? '';

        $args = [
            'title' => $title,
            'posts' => $this->getPosts(),
            'all_posts' => $this->getPosts(-1),
            'categories' => get_categories([
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
            ]),
        ];

		$this->getView('shortcodes/sc', $this->shortcode(), $args);

		return null;
	}

	public function getPosts(int $count = 12, int $offset = 0): array
    {
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $count,
            'offset' => $offset,
        ];

        if(isset($_GET['categoria']) && !empty($_GET['categoria'])) {
            $args['category_name'] = $_GET['categoria'];
        }

        if(isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
            $args['s'] = $_GET['busqueda'];
        }

        return get_posts($args);
    }

    public function GetMorePosts(): void
    {
        if (check_ajax_referer(Shared::NONCE)) {
            $offset = sanitize_text_field($_POST['offset'] ?? '');

            ob_start();

            $posts = $this->getPosts(12, $offset);

            foreach ($posts as $blogPost) {
                get_template_part('inc/views/templates/temp', 'blog-post-preview', ['post' => $blogPost]);
            }

            $html_items = ob_get_contents();

            ob_end_clean();

            wp_send_json_success([
                'items' => $html_items,
                'count' => count($posts),
                'limit' => 12,
            ]);
        } else {
            wp_send_json_error();
        }
    }
}
