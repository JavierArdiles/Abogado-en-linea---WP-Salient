<?php

namespace ClazzyStudioChildTheme\Tests\Shortcodes;

use Prophecy\Argument;
use PHPUnit\Framework\TestCase;
use ClazzyStudioChildTheme\Loader;
use Prophecy\PhpUnit\ProphecyTrait;
use ClazzyStudioChildTheme\DemoClasses\CustomType;

class CustomTypeDemoTest extends TestCase
{
	use ProphecyTrait;

	private $loaderMock;

	public function setUp(): void
	{
		$this->loaderMock = $this->prophesize(Loader::class);

		$this->loaderMock->add_action(
			Argument::exact('init'),
			Argument::type(CustomType::class),
			Argument::exact('register'),
			Argument::exact(0)
		)
			->shouldBeCalled();
	}

	public function test_custom_type_parameters(): void
	{
		$loaderMock = $this->loaderMock;

		$demo = new CustomType('test', '1.0', $loaderMock->reveal());

		$params = $demo->getParameters();

		$this->assertEquals('demo_custom_type', $params[0]);
		$this->assertCount(12, $params[1]['labels']);
		$this->assertCount(22, $params[1]);
	}
}
