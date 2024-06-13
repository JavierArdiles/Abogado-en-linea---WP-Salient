<?php

namespace ClazzyStudioChildTheme\DemoClasses;

use ClazzyStudioChildTheme\Contracts\AbstractCustomTaxonomy;

class CustomTaxonomy extends AbstractCustomTaxonomy
{
	public static function key(): string
	{
		return 'demo_taxonomy';
	}

	protected function pluralName(): string
	{
		return 'Taxonomies';
	}

	protected function singleName(): string
	{
		return 'Taxonomy';
	}

	protected function objectTypes(): array
	{
		return ['post'];
	}

	protected function description(): string
	{
		return 'Demo Taxonomy description';
	}
}
