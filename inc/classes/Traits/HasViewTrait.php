<?php

namespace ClazzyStudioChildTheme\Traits;

if (! \defined('WPINC'))
{
	die;
}

trait HasViewTrait
{
	public function getView(string $slug, string $name, array $args = []): void
	{
		child_theme_get_template_view($slug, $name, $args);
	}
}
