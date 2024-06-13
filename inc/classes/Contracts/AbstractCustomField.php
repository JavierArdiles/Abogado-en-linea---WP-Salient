<?php

namespace ClazzyStudioChildTheme\Contracts;

// If this file is called directly, abort.
if (! defined('WPINC'))
{
	die;
}

abstract class AbstractCustomField extends AbstractBase
{
	abstract protected function register(): void;

	protected function init(): void
	{
		if (! $this->isValid())
		{
			return;
		}

		$this->getLoader()->add_action('acf/include_fields', $this, 'register', 0);
	}

	protected function isValid(): bool
	{
		return class_exists('acf') && function_exists('acf_add_local_field_group');
	}
}
