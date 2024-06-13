<?php

namespace ClazzyStudioChildTheme\Tests\Shortcodes;

use Prophecy\Argument;
use PHPUnit\Framework\TestCase;
use ClazzyStudioChildTheme\Loader;
use Prophecy\PhpUnit\ProphecyTrait;
use ClazzyStudioChildTheme\DemoClasses\CustomTaxonomy;

class CustomTaxonomyDemoTest extends TestCase
{
	use ProphecyTrait;

	private $loaderMock;

	public function setUp(): void
	{
		$this->loaderMock = $this->prophesize(Loader::class);

		$this->loaderMock->add_action(
			Argument::exact('init'),
			Argument::type(CustomTaxonomy::class),
			Argument::exact('register'),
			Argument::exact(0)
		)
			->shouldBeCalled();
	}

	public function test_custom_taxonomies_parameters(): void
	{
		$loaderMock = $this->loaderMock;

		$demo = new CustomTaxonomy('test', '1.0', $loaderMock->reveal());

		$params = $demo->getParameters();

		$this->assertEquals('demo_taxonomy', $params[0]);
		$this->assertCount(1, $params[1]);
		$this->assertCount(20, $params[2]['labels']);
		$this->assertCount(9, $params[2]);
	}
}
