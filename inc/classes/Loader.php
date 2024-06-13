<?php

namespace ClazzyStudioChildTheme;

if (! \defined('WPINC'))
{
	die;
}

class Loader
{
	protected $actions;
	protected $filters;
	protected $shortcodes;

	public function __construct()
	{
		$this->actions    = [];
		$this->filters    = [];
		$this->shortcodes = [];
	}

	public function add_action(string $hook, $component, string $callback, int $priority = 10, int $accepted_args = 1): void
	{
		$this->actions = $this->transformHook($this->actions, $hook, $component, $callback, $priority, $accepted_args);
	}

	public function add_filter(string $hook, $component, string $callback, int $priority = 10, int $accepted_args = 1): void
	{
		$this->filters = $this->transformHook($this->filters, $hook, $component, $callback, $priority, $accepted_args);
	}

	public function add_shortcode(string $hook, $component, string $callback): void
	{
		$this->shortcodes = $this->transformHook($this->shortcodes, $hook, $component, $callback, 0, 0);
	}

	private function transformHook(array $hooks, string $hook, $component, string $callback, int $priority, int $accepted_args): array
	{
		$hooks[] = [
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		];

		return $hooks;
	}

	public function run(): bool
	{
		foreach ($this->getFilters() as $hook)
		{
			add_filter($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
		}

		foreach ($this->getActions() as $hook)
		{
			add_action($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
		}

		if (count($this->getShortcodes()))
		{
			add_action('init', [$this, 'runShortcodes']);
		}

		return true;
	}

	public function runShortcodes(): void
	{
		foreach ($this->getShortcodes() as $hook)
		{
			add_shortcode($hook['hook'], [$hook['component'], $hook['callback']]);
		}
	}

	public function execFn(callable $fn, array $params = []): mixed
	{
		return call_user_func_array($fn, $params);
	}

	public function getFilters(): array
	{
		return $this->filters;
	}

	public function getActions(): array
	{
		return $this->actions;
	}

	public function getShortcodes(): array
	{
		return $this->shortcodes;
	}
}
