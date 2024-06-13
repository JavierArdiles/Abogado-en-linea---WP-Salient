<?php

namespace ClazzyStudioChildTheme\Tests;

use Prophecy\Argument;
use PHPUnit\Framework\TestCase;
use ClazzyStudioChildTheme\Loader;
use Prophecy\PhpUnit\ProphecyTrait;
use ClazzyStudioChildTheme\ChildTheme;
use ClazzyStudioChildTheme\DemoClasses\Shortcode;
use ClazzyStudioChildTheme\DemoClasses\CustomType;
use ClazzyStudioChildTheme\DemoClasses\CustomField;
use ClazzyStudioChildTheme\DemoClasses\CustomTaxonomy;
use ClazzyStudioChildTheme\Exceptions\InvalidClassException;

class ChildThemeClassTest extends TestCase
{
	use ProphecyTrait;

	private $loaderMock;

	public function setUp(): void
	{
		$this->loaderMock = $this->prophesize(Loader::class);
	}

	public function test_init_main_class(): void
	{
		$loaderMock = $this->loaderMock;

		$loaderMock->add_action(
			Argument::type('string'),
			Argument::any(),
			Argument::type('string')
		)
			->shouldBeCalled();

		$loaderMock->add_action(
			Argument::type('string'),
			Argument::any(),
			Argument::type('string'),
			Argument::type('integer')
		)
				->shouldBeCalled();

		$loaderMock->add_filter(
			Argument::exact('rest_authentication_errors'),
			Argument::any(),
			Argument::exact('disableApiRestToAnonymousUser'),
		)
			->shouldBeCalled();

		$loaderMock->run()
			->shouldBeCalled()
			->willReturn(true);

		$main = new ChildTheme('test', '1.0', $loaderMock->reveal());

		$this->assertTrue($main->run());
	}

	public function test_demo_dependencies_main_class_without_acf_plugin_active(): void
	{
		$loaderMock = $this->loaderMock;

		$loaderMock->add_action(
			Argument::exact('init'),
			Argument::type(CustomTaxonomy::class),
			Argument::exact('register'),
			Argument::exact(0)
		)
			->shouldBeCalled();

		$loaderMock->add_action(
			Argument::exact('init'),
			Argument::type(CustomType::class),
			Argument::exact('register'),
			Argument::exact(0)
		)
			->shouldBeCalled();

		$loaderMock->add_shortcode(
			Argument::exact('hola-mundo'),
			Argument::type(Shortcode::class),
			Argument::exact('handler')
		)
			->shouldBeCalled();

		$loaderMock->run()
			->shouldBeCalled()
			->willReturn(true);

		$main = new ChildTheme('test', '1.0', $loaderMock->reveal());

		$main->setDependencies([
			CustomField::class,
			CustomTaxonomy::class,
			CustomType::class,
			Shortcode::class,
		]);

		$this->assertTrue($main->run());
	}

	public function test_getters_in_main_class(): void
	{
		$loaderMock = $this->loaderMock;
		$main       = new ChildTheme('test', '1.0', $loaderMock->reveal());

		$this->assertEquals('1.0', $main->getVersion());
		$this->assertEquals('test', $main->getThemeName());
		$this->assertCount(4, $main->getDefaultDependencies());
		$this->assertCount(4, $main->getFolders());
		$this->assertEquals($loaderMock->reveal(), $main->getLoader());
		$this->assertInstanceOf(Loader::class, $main->getLoader());
	}

	public function test_load_invalid_dependency_exception(): void
	{
		$this->expectException(InvalidClassException::class);
		$this->expectExceptionMessage("Class 'Foos\Bar\AA' is invalid.");

		$loaderMock = $this->loaderMock;

		$main = new ChildTheme('test', '1.0', $loaderMock->reveal());

		$main->initInstance('Foos\Bar\AA');
	}
}
