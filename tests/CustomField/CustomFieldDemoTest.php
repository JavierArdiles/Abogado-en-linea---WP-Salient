<?php

namespace ClazzyStudioChildTheme\Tests\Shortcodes;

use Prophecy\Argument;
use PHPUnit\Framework\TestCase;
use ClazzyStudioChildTheme\Loader;
use Prophecy\PhpUnit\ProphecyTrait;
use ClazzyStudioChildTheme\DemoClasses\CustomField;

class CustomFieldDemoTest extends TestCase
{
	use ProphecyTrait;

	private $loaderMock;

	/**
	 * @dataProvider acfPluginStateDataProvider
	 */
	public function test_init_custom_fields(
		bool $AcfPluginIsActive,
		int $expectedExecutions
	): void {
		$loaderMock = $this->prophesize(Loader::class);

		$loaderMock->add_action(
			Argument::exact('acf/include_fields'),
			Argument::type(CustomField::class),
			Argument::exact('register'),
			Argument::exact(0)
		)
			->shouldBeCalledTimes($expectedExecutions);

		$customFieldMock = $this->getMockBuilder(CustomField::class)
			->enableOriginalConstructor()
			->setConstructorArgs(['test', '1.0', $loaderMock->reveal()])
			->onlyMethods(['isValid'])
			->getMock();

		$customFieldMock->method('isValid')
			->willReturn($AcfPluginIsActive);

		$customFieldMock->__construct('test', '1.0', $loaderMock->reveal());
	}

	public function acfPluginStateDataProvider(): array
	{
		return [
			'init class with acf plugin enabled' => [
				'AcfPluginIsActive'  => true,
				'expectedExecutions' => 1,
			],
			'init class with acf plugin disabled' => [
				'AcfPluginIsActive'  => false,
				'expectedExecutions' => 0,
			],
		];
	}
}
