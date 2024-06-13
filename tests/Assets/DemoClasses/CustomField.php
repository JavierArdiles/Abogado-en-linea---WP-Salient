<?php

namespace ClazzyStudioChildTheme\DemoClasses;

use ClazzyStudioChildTheme\Contracts\AbstractCustomField;

class CustomField extends AbstractCustomField
{
	const NAME  = 'demo_custom_field';
	const GROUP = 'demo_custom_field_group';

	public function register(): void
	{
		$location = [
			[
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'post',
			],
		];

		$fields = [
			'key'               => self::NAME,
			'label'             => 'Demo',
			'name'              => self::NAME,
			'aria-label'        => '',
			'type'              => 'text',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '',
				'class' => '',
				'id'    => '',
			],
			'default_value' => '',
			'maxlength'     => '',
			'placeholder'   => '',
			'prepend'       => '',
			'append'        => '',
		];

		$args = [
			'key'                   => self::GROUP,
			'title'                 => 'DemoFields',
			'fields'                => [$fields],
			'location'              => [$location],
			'menu_order'            => 0,
			'position'              => 'acf_after_title',
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
