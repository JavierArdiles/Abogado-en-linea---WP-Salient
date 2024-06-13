<?php

namespace ClazzyStudioChildTheme\DemoClasses;

use ClazzyStudioChildTheme\Contracts\AbstractCustomType;

class CustomType extends AbstractCustomType
{
	public static function key(): string
	{
		return 'demo_custom_type';
	}

	protected function pluralName(): string
	{
		return 'CustomTypes';
	}

	protected function singleName(): string
	{
		return 'CustomType';
	}

	protected function menuIcon(): string
	{
		return 'dashicons-menu-ul';
	}

	protected function supports(): array
	{
		return ['title'];
	}

	protected function rewrite(): array
	{
		return [];
	}

	protected function hasArchive(): bool
	{
		return false;
	}

	public function arguments(array $args): array
	{
		$args['menu_position'] = 7;

		return $args;
	}
}
