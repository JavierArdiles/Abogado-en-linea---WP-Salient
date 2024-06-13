<?php

namespace ClazzyStudioChildTheme\Tests;

use PHPUnit\Framework\TestCase;
use ClazzyStudioChildTheme\Loader;

class LoaderClassTest extends TestCase
{
	/**
	 * @dataProvider hooksDataProvider
	 */
	public function test_add_hooks_to_loader(
		string $hookMethod,
		string $getterMethod,
		array $hooks,
		array $expectedHooks
	): void {
		$loader = new Loader();

		foreach ($hooks as $hook)
		{
			call_user_func_array([$loader, $hookMethod], $hook);
		}

		$actions = $loader->$getterMethod();

		$this->assertEquals($actions, $expectedHooks);
	}

	public function test_execute_functions(): void
	{
		$loader = new Loader();
		$result = $loader->execFn('is_user_logged_in');

		$this->assertTrue($result);
	}

	/**
	 * @dataProvider hooksMixedDataProvider
	 */
	public function test_run_loader(
		array $actions,
		array $filters,
		array $shortcodes
	): void {
		$loader = new Loader();

		foreach ($actions as $hook)
		{
			call_user_func_array([$loader, 'add_action'], $hook);
		}

		foreach ($filters as $hook)
		{
			call_user_func_array([$loader, 'add_filter'], $hook);
		}

		foreach ($shortcodes as $hook)
		{
			call_user_func_array([$loader, 'add_shortcode'], $hook);
		}

		$this->assertCount(count($actions), $loader->getActions());
		$this->assertCount(count($filters), $loader->getFilters());
		$this->assertCount(count($shortcodes), $loader->getShortcodes());

		$this->assertTrue($loader->run());
	}

	public function hooksMixedDataProvider(): array
	{
		return [
			'add 1 action' => [
				'actions' => [
					[
						'hook'      => 'name_action',
						'component' => 'component_action',
						'callback'  => 'callback_action',
					],
				],
				'filters'    => [],
				'shortcodes' => [],
			],

			'add 1 filter' => [
				'actions' => [],
				'filters' => [

					[
						'hook'      => 'name_filter',
						'component' => 'component_filter',
						'callback'  => 'callback_filter',
					],

				],
				'shortcodes' => [],
			],

			'add 1 shortcode' => [
				'actions'    => [],
				'filters'    => [],
				'shortcodes' => [
					[
						'hook'      => 'name_shortcode',
						'component' => 'component_shortcode',
						'callback'  => 'callback_shortcode',
					],
				],
			],

			'add 1 filter and 1 action and 1 shortcode' => [
				'actions' => [
					[
						'hook'      => 'name_action',
						'component' => 'component_action',
						'callback'  => 'callback_action',
					],
				],
				'filters' => [
					[
						'hook'      => 'name_filter',
						'component' => 'component_filter',
						'callback'  => 'callback_filter',
					],
				],
				'shortcodes' => [
					[
						'hook'      => 'name_shortcode',
						'component' => 'component_shortcode',
						'callback'  => 'callback_shortcode',
					],
				],
			],
		];
	}

	public function hooksDataProvider(): array
	{
		return [
			'1 action' => [
				'hookMethod'   => 'add_action',
				'getterMethod' => 'getActions',
				'hooks'        => [
					[
						'hook'          => 'name_test',
						'component'     => 'component_test',
						'callback'      => 'callback_test',
						'priority'      => 9,
						'accepted_args' => 2,
					],
				],
				'expectedHooks' => [
					[
						'hook'          => 'name_test',
						'component'     => 'component_test',
						'callback'      => 'callback_test',
						'priority'      => 9,
						'accepted_args' => 2,
					],
				],
			],

			'2 action' => [
				'hookMethod'   => 'add_action',
				'getterMethod' => 'getActions',
				'hooks'        => [
					[
						'hook'          => 'name_test',
						'component'     => 'component_test',
						'callback'      => 'callback_test',
						'priority'      => 9,
						'accepted_args' => 1,
					],
					[
						'hook'      => 'name_test_two',
						'component' => 'component_test_two',
						'callback'  => 'callback_test_two',
					],
				],
				'expectedHooks' => [
					[
						'hook'          => 'name_test',
						'component'     => 'component_test',
						'callback'      => 'callback_test',
						'priority'      => 9,
						'accepted_args' => 1,
					],
					[
						'hook'          => 'name_test_two',
						'component'     => 'component_test_two',
						'callback'      => 'callback_test_two',
						'priority'      => 10,
						'accepted_args' => 1,
					],
				],
			],

			'1 filter' => [
				'hookMethod'   => 'add_filter',
				'getterMethod' => 'getFilters',
				'hooks'        => [
					[
						'hook'          => 'name_test',
						'component'     => 'component_test',
						'callback'      => 'callback_test',
						'priority'      => 9,
						'accepted_args' => 2,
					],
				],
				'expectedHooks' => [
					[
						'hook'          => 'name_test',
						'component'     => 'component_test',
						'callback'      => 'callback_test',
						'priority'      => 9,
						'accepted_args' => 2,
					],
				],
			],

			'2 filters' => [
				'hookMethod'   => 'add_filter',
				'getterMethod' => 'getFilters',
				'hooks'        => [
					[
						'hook'          => 'name_test',
						'component'     => 'component_test',
						'callback'      => 'callback_test',
						'priority'      => 9,
						'accepted_args' => 1,
					],
					[
						'hook'      => 'name_test_two',
						'component' => 'component_test_two',
						'callback'  => 'callback_test_two',
					],
				],
				'expectedHooks' => [
					[
						'hook'          => 'name_test',
						'component'     => 'component_test',
						'callback'      => 'callback_test',
						'priority'      => 9,
						'accepted_args' => 1,
					],
					[
						'hook'          => 'name_test_two',
						'component'     => 'component_test_two',
						'callback'      => 'callback_test_two',
						'priority'      => 10,
						'accepted_args' => 1,
					],
				],
			],
		];
	}
}
