<?php

namespace ClazzyStudioChildTheme\Contracts;

use ClazzyStudioChildTheme\Loader;

// If this file is called directly, abort.
if (! defined('WPINC'))
{
	die;
}

abstract class AbstractBase
{
	private $theme_name;
	private $version;
	private $loader;

	public function __construct(string $theme_name, string $version, Loader $loader)
	{
		$this->theme_name = $theme_name;
		$this->version    = $version;
		$this->loader     = $loader;

		$this->init();
	}

	abstract protected function init(): void;

	public function getThemeName(): string
	{
		return $this->theme_name;
	}

	public function getLoader(): Loader
	{
		return $this->loader;
	}

	public function getVersion(): string
	{
		return $this->version;
	}
}
