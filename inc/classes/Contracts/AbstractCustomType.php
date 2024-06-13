<?php

namespace ClazzyStudioChildTheme\Contracts;

// If this file is called directly, abort.
if (! defined('WPINC'))
{
	die;
}

abstract class AbstractCustomType extends AbstractBase
{
	abstract public static function key(): string;

	abstract protected function singleName(): string;

	abstract protected function pluralName(): string;

	abstract protected function menuIcon(): string;

	abstract protected function supports(): array;

	abstract protected function rewrite(): array;

	abstract protected function hasArchive(): bool;

	protected function init(): void
	{
		$this->getLoader()->add_action('init', $this, 'register', 0);
	}

	public function arguments(array $args): array
	{
		return $args;
	}

	protected function labels(): array
	{
		$plural = $this->pluralName();
		$single = $this->singleName();

		return [
			'name'               => _x($plural, 'Post Type General Name', $this->getThemeName()),
			'singular_name'      => _x($single, 'Post Type Singular Name', $this->getThemeName()),
			'menu_name'          => __($plural, $this->getThemeName()),
			'all_items'          => sprintf(__('All %s', $this->getThemeName()), $plural),
			'view_item'          => sprintf(__('Show %s', $this->getThemeName()), $plural),
			'add_new_item'       => sprintf(__('Add New %s', $this->getThemeName()), $single),
			'add_new'            => sprintf(__('New %s', $this->getThemeName()), $single),
			'edit_item'          => sprintf(__('Edit %s', $this->getThemeName()), $single),
			'update_item'        => sprintf(__('Update %s', $this->getThemeName()), $single),
			'search_items'       => sprintf(__('Filter by %s', $this->getThemeName()), $single),
			'not_found'          => __('Not %s found', $this->getThemeName()),
			'not_found_in_trash' => __('Not %s found in trash', $this->getThemeName()),
		];
	}

	protected function getDefaultArguments(): array
	{
		$plural   = $this->pluralName();
		$menuIcon = empty($this->menuIcon()) ? 'dashicons-editor-ul' : $this->menuIcon();

		return [
			'label'                 => __($plural, $this->getThemeName()),
			'description'           => sprintf(__('%s section', $this->getThemeName()), $plural),
			'labels'                => $this->labels(),
			'supports'              => $this->supports(),
			'taxonomies'            => [],
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'show_in_admin_bar'     => false,
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rest_namespace'        => 'wp/v2',
			'menu_position'         => 6,
			'menu_icon'             => $menuIcon,
			'can_export'            => true,
			'has_archive'           => $this->hasArchive(),
			'exclude_from_search'   => true,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
			'show_in_rest'          => true,
			'rewrite'               => $this->rewrite(),
		];
	}

	public function getParameters(): array
	{
		return [
			$this->key(),
			$this->arguments($this->getDefaultArguments()),
		];
	}

	public function register(): void
	{
		call_user_func_array('register_post_type', $this->getParameters());
	}
}
