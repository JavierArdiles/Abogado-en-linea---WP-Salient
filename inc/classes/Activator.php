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
		$this->getLoader()->add_action('tgmpa_register', $this, 'registerRequiredPlugins');
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

	public function registerRequiredPlugins(): void
	{
		$gitlabUpdater = $this->getGithubRepoData('otzi122', 'wp-gitlab-updater');

		$plugins[] = [
			'name'             => 'Gitlab Updater',
			'slug'             => 'gitlab-updater',
			'source'           => $gitlabUpdater['source']  ?? '#',
			'version'          => $gitlabUpdater['version'] ?? 'Error',
			'required'         => false,
			'force_activation' => false,
		];

		if (! class_exists('acf'))
		{
			$plugins[] = [
				'name'     => 'Advanced Custom Fields',
				'slug'     => 'advanced-custom-fields',
				'required' => false,
			];
		}

		$plugins[] = [
			'name'             => 'Login with Google',
			'slug'             => 'login-with-google',
			'required'         => false,
			'force_activation' => false,
		];

		$plugins[] = [
			'name'             => 'iThemes Security',
			'slug'             => 'better-wp-security',
			'required'         => false,
			'force_activation' => false,
		];

		$config = [
			'id'           => 'clazzy-child-theme',
			'default_path' => '',
			'menu'         => 'tgmpa-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => '',
			'is_automatic' => true,
			'message'      => '',
		];

		tgmpa($plugins, $config);
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
