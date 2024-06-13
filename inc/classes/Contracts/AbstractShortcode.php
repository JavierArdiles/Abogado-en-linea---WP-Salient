<?php

namespace ClazzyStudioChildTheme\Contracts;

// If this file is called directly, abort.
if (! defined('WPINC'))
{
	die;
}

abstract class AbstractShortcode extends AbstractBase
{
	protected $shortcode;

	abstract public static function shortcode(): string;

	abstract public function handler(mixed $atts) : ?string;

	protected function init(): void
	{
		$this->getLoader()->add_shortcode($this->shortcode(), $this, 'handler');
	}
}
