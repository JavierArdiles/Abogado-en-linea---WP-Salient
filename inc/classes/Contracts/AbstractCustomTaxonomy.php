<?php

namespace ClazzyStudioChildTheme\Contracts;

// If this file is called directly, abort.
if (! defined('WPINC'))
{
	die;
}

abstract class AbstractCustomTaxonomy extends AbstractBase
{
	abstract public static function key(): string;

	abstract protected function singleName(): string;

	abstract protected function pluralName(): string;

	abstract protected function description(): string;

	abstract protected function objectTypes(): array;

	protected function init(): void
	{
		$this->getLoader()->add_action('init', $this, 'register', 0);
	}

	protected function arguments(array $args): array
	{
		return $args;
	}

	protected function labels(): array
	{
		$plural = $this->pluralName();
		$single = $this->singleName();

		return [
			'name'                  => _x($plural, 'Post Type General Name', $this->getThemeName()),
			'singular_name'         => _x($single, 'Post Type Singular Name', $this->getThemeName()),
			'menu_name'             => __($plural, $this->getThemeName()),
			'all_items'             => sprintf(__('All %s', $this->getThemeName()), $plural),
			'edit_item'             => sprintf(__('Edit %s', $this->getThemeName()), $single),
			'view_item'             => sprintf(__('View %s', $this->getThemeName()), $single),
			'update_item'           => sprintf(__('Update %s', $this->getThemeName()), $single),
			'add_new_item'          => sprintf(__('Add New %s', $this->getThemeName()), $single),
			'new_item_name'         => sprintf(__('New %s Name', $this->getThemeName()), $single),
			'parent_item'           => sprintf(__('Parent %s', $this->getThemeName()), $single),
			'parent_item_colon'     => sprintf(__('Parent %s:', $this->getThemeName()), $single),
			'search_items'          => sprintf(__('Search %s', $this->getThemeName()), $plural),
			'not_found'             => sprintf(__('No %s found', $this->getThemeName()), $single),
			'no_terms'              => sprintf(__('No %s', $this->getThemeName()), $plural),
			'filter_by_item'        => sprintf(__('Filter by %s', $this->getThemeName()), $single),
			'items_list_navigation' => sprintf(__('%s list navigation', $this->getThemeName()), $plural),
			'items_list'            => sprintf(__('%s list', $this->getThemeName()), $plural),
			'back_to_items'         => sprintf(__('← Go to %s', $this->getThemeName()), $plural),
			'item_link'             => sprintf(__('%s Link', $this->getThemeName()), $single),
			'item_link_description' => sprintf(__('A link to a %s', $this->getThemeName()), $single),
		];
	}

	protected function getDefaultArguments(): array
	{
		return [
			'labels'            => $this->labels(),
			'description'       => $this->description(),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_menu'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => [
				'with_front' => false,
			],
			'sort' => true,
		];
	}

	public function getParameters(): array
	{
		return [
			$this->key(),
			$this->objectTypes(),
			$this->arguments($this->getDefaultArguments()),
		];
	}

	public function register(): void
	{
		call_user_func_array('register_taxonomy', $this->getParameters());
	}
}
