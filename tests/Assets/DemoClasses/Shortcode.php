<?php

namespace ClazzyStudioChildTheme\DemoClasses;

use ClazzyStudioChildTheme\Traits\HasViewTrait;
use ClazzyStudioChildTheme\Contracts\AbstractShortcode;

class Shortcode extends AbstractShortcode
{
	use HasViewTrait;

	public static function shortcode(): string
	{
		return 'hola-mundo';
	}

	public function handler(mixed $atts) : ?string
	{
		$args = shortcode_atts([
			'text'        => '',
			'link'        => '#',
			'hide_values' => '',
		], $atts);

		return 'Hola mundo';
	}
}
