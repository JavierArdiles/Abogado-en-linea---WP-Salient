<?php

namespace ClazzyStudioChildTheme;

use ClazzyStudioChildTheme\Contracts\AbstractBase;

if (! \defined('WPINC'))
{
	die;
}

class Shared extends AbstractBase
{
	private $assetsPath;

	protected function init(): void
	{
		$this->assetsPath = CHILD_THEME_URI . '/inc/resources/dist/';

		$this->getLoader()->add_action('wp_enqueue_scripts', $this, 'enqueueStyles', 100);
		$this->getLoader()->add_action('wp_enqueue_scripts', $this, 'enqueueScripts', 11);
		$this->getLoader()->add_filter('rest_authentication_errors', $this, 'disableApiRestToAnonymousUser');
	}

	public function enqueueStyles(): void
	{
		$loader = $this->getLoader();

		$loader->execFn(
			'wp_enqueue_style',
			[
				$this->getAssetHandle(),
				CHILD_THEME_URI . '/style.css',
				'',
				$this->getVersion(),
			]
		);

		if ($loader->execFn('is_rtl'))
		{
			$loader->execFn(
				'wp_enqueue_style',
				[
					$this->getAssetHandle('rtl'),
					CHILD_THEME_URI . '/rtl.css',
					[],
					$this->getVersion(),
					'screen',
				]
			);
		}

		$loader->execFn(
			'wp_enqueue_style',
			[
				$this->getAssetHandle('child-style-shared'),
				$this->assetsPath . 'css/shared.min.css',
				[$this->getAssetHandle()],
				$this->getVersion(),
			]
		);
	}

	public function enqueueScripts(): void
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script(
			$this->getAssetHandle('child-script-shared'),
			$this->assetsPath . 'js/shared.min.js',
			[],
			$this->getVersion(),
			true
		);
	}

	public function getAssetHandle($suffix = 'child-style'): string
	{
		return sprintf('%s-%s', $this->getThemeName(), $suffix);
	}

	public function disableApiRestToAnonymousUser(mixed $result): mixed
	{
		if (! empty($result) || $this->isRequestValid())
		{
			return $result;
		}

		if (! $this->getLoader()->execFn('is_user_logged_in'))
		{
			return new \WP_Error('rest_not_logged_in', __('REST API not available for anonymous users.'), ['status' => 401]);
		}

		return $result;
	}

	public function isRequestValid(): bool
	{
		return mb_strpos(($GLOBALS['wp']->request ?? ''), 'contact-form-7') !== false;
	}
}
