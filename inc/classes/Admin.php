<?php

namespace ClazzyStudioChildTheme;

use ClazzyStudioChildTheme\Traits\HasViewTrait;
use ClazzyStudioChildTheme\Contracts\AbstractBase;

if (! defined('WPINC'))
{
	die;
}

class Admin extends AbstractBase
{
	use HasViewTrait;

	const SETTING_DEMO = 'demo-setting';
	const SETTING_SLUG = 'clazzy-theme-setting';

	private $assetsPath;

	protected function init(): void
	{
		$this->assetsPath = CHILD_THEME_URI . '/inc/resources/dist/';

		$this->getLoader()->add_action('admin_enqueue_scripts', $this, 'enqueueStyles');
		$this->getLoader()->add_action('admin_enqueue_scripts', $this, 'enqueueScripts');
	}

	public function enqueueStyles(): void
	{
		wp_enqueue_style($this->getAssetHandle('child-style'), $this->assetsPath . 'css/admin.min.css', [], $this->getVersion(), 'all');
	}

	public function enqueueScripts(): void
	{
		wp_enqueue_script($this->getAssetHandle('child-script'), $this->assetsPath . 'js/admin.min.js', ['jquery'], $this->getVersion(), false);
	}

	public function getAssetHandle($suffix = 'child-style'): string
	{
		return sprintf('%s-admin-%s', $this->getThemeName(), $suffix);
	}
}
