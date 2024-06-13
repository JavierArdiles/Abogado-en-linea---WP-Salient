<?php

namespace ClazzyStudioChildTheme\Tests;

use Prophecy\Argument;
use PHPUnit\Framework\TestCase;
use ClazzyStudioChildTheme\Admin;
use ClazzyStudioChildTheme\Loader;
use Prophecy\PhpUnit\ProphecyTrait;

class AdminClassTest extends TestCase
{
	use ProphecyTrait;

	private $loaderMock;

	public function setUp(): void
	{
		$this->loaderMock = $this->prophesize(Loader::class);

		$this->loaderMock->add_action(
			Argument::type('string'),
			Argument::type(Admin::class),
			Argument::type('string')
		)
			->shouldBeCalled();
	}

	public function test_init_admin_class(): void
	{
		$loaderMock = $this->loaderMock;
		new Admin('test', '1.0', $loaderMock->reveal());
	}

	/**
	 * @dataProvider pathAssetsDataProvider
	 */
	public function test_get_asset_handle(
		string $suffix,
		string $expectedHandle
	): void {
		$loaderMock = $this->loaderMock;

		$admin = new Admin('test', '1.0', $loaderMock->reveal());

		$this->assertEquals($expectedHandle, $admin->getAssetHandle($suffix));
	}

	public function pathAssetsDataProvider(): array
	{
		return [
			'child style' => [
				'suffix'         => 'child-style',
				'expectedHandle' => 'test-admin-child-style',
			],
			'child' => [
				'suffix'         => 'child',
				'expectedHandle' => 'test-admin-child',
			],
		];
	}

	public function test_global(): void
	{
		$loaderMock = $this->loaderMock;

		$admin = new Admin('test', '1.0', $loaderMock->reveal());

		ob_start();

		$admin->warningNoApiKey();

		$warningMessage = ob_get_clean();

		ob_start();

		include_once __DIR__ . '/Assets/Test_warning_no_api_key.html';

		$expectedAdminPage = ob_get_clean();

		$this->assertEquals($warningMessage, $expectedAdminPage);
	}
}
