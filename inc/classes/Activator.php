<?php

namespace ClazzyStudioChildTheme;

use ClazzyStudioChildTheme\Contracts\AbstractBase;

if (! defined('WPINC'))
{
	die;
}

class Activator extends AbstractBase
{
	public function init(): void
	{
		$this->getLoader()->add_action('after_switch_theme', $this, 'isActive');
	}

	public function isActive(): void
	{
		$prefix = sprintf('%s_theme_activated', $this->getThemeName());

		if (get_option($prefix) != '1')
		{
			update_option($prefix, '1');
			$this->active();
		}
	}

	protected function active(): void
	{
		//
	}

	public function getGithubRepoData(string $owner, string $repo): array
	{
		$request = wp_safe_remote_get("https://api.github.com/repos/$owner/$repo/tags");

		$response_code = wp_remote_retrieve_response_code($request);

		if (is_wp_error($request) || 200 !== $response_code)
		{
			return [];
		}

		$response = wp_remote_retrieve_body($request);

		$pluginTags = json_decode($response);

		if (empty($pluginTags))
		{
			return [];
		}

		return [
			'source'  => "https://github.com/$owner/$repo/archive/refs/heads/master.zip",
			'version' => $pluginTags[0]->name,
		];
	}
}
