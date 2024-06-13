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

		$this->getLoader()->add_action('admin_menu', $this, 'addOptionsPage');
		$this->getLoader()->add_action('admin_init', $this, 'registerSettings');

		if (empty(get_option(self::SETTING_DEMO)))
		{
			$this->getLoader()->add_action('admin_notices', $this, 'warningNoApiKey');
		}
	}

	public function warningNoApiKey(): void
	{
		$class   = 'notice notice-error';
		$message = __('Error: you need to set the Demo Value.', $this->getThemeName());

		printf(
			'<div class="%1$s"><p>%2$s <a href="options-general.php?page=%3$s">Child Theme Warning</a></p></div>',
			esc_attr($class),
			esc_html($message),
			self::SETTING_SLUG
		);
	}

	public function registerSettings(): void
	{
		register_setting($this->getThemeName(), self::SETTING_DEMO);
	}

	public function addOptionsPage(): void
	{
		add_submenu_page(
			'options-general.php',
			esc_html__('Child Theme Settings', $this->getThemeName()),
			esc_html__('Child Theme Options', $this->getThemeName()),
			'manage_options',
			self::SETTING_SLUG,
			[$this, 'adminPage']
		);
	}

	public function adminPage(): void
	{
		$this->getView('admin', 'demo', [
			'theme' => $this->getThemeName(),
			'field' => self::SETTING_DEMO,
		]);
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
