<?php

namespace ClazzyStudioChildTheme\CustomFields;

use ClazzyStudioChildTheme\Contracts\AbstractCustomField;

class BlogPostAuthor extends AbstractCustomField
{
    const AUTHOR = 'blog-post-author';
    const IMAGE = 'blog-post-author-img';

    const GROUP = 'blog-post-meta';
    const TITLE = 'Post author';

	public function register(): void
	{
		$location = [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				],
			],
		];

		$fields = [
            [
                'key' => self::AUTHOR,
                'label' => 'Autor',
                'name' => self::AUTHOR,
                'aria-label' => '',
                'type' => 'text',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'wpml_cf_preferences' => 2,
                'default_value' => '',
                'maxlength' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
            ],
            [
                'key' => self::IMAGE,
                'label' => 'Imagen del autor',
                'name' => self::IMAGE,
                'aria-label' => '',
                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'wpml_cf_preferences' => 1,
                'return_format' => 'array',
                'library' => 'all',
                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
                'preview_size' => 'medium',
            ],
        ];

		$args = [
			'key'                   => self::GROUP,
			'title'                 => self::TITLE,
			'fields'                => $fields,
			'location'              => $location,
			'menu_order'            => 0,
			'position'              => 'side',
			'style'                 => 'seamless',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
			'show_in_rest'          => 0,
		];

		acf_add_local_field_group($args);
	}
}
