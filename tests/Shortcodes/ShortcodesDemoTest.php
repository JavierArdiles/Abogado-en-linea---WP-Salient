<?php

namespace ClazzyStudioChildTheme\Tests\Shortcodes;

use Prophecy\Argument;
use PHPUnit\Framework\TestCase;
use ClazzyStudioChildTheme\Loader;
use Prophecy\PhpUnit\ProphecyTrait;
use ClazzyStudioChildTheme\DemoClasses\Shortcode;

class ShortcodesDemoTest extends TestCase
{
	use ProphecyTrait;

	private $loaderMock;

	public function setUp(): void
	{
		$this->loaderMock = $this->prophesize(Loader::class);

		$this->loaderMock->add_shortcode(
			Argument::type('string'),
			Argument::any(),
			Argument::type('string'),
		)
			->shouldBeCalled();
	}

	public function test_handler(): void
	{
		$loaderMock = $this->loaderMock;

		$demo = new Shortcode('test', '1.0', $loaderMock->reveal());

		$this->assertEquals('Hola mundo', $demo->handler('test'));
	}
}
