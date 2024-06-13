<?php

namespace ClazzyStudioChildTheme\Tests;

use Prophecy\Argument;
use PHPUnit\Framework\TestCase;
use ClazzyStudioChildTheme\Loader;
use Prophecy\PhpUnit\ProphecyTrait;
use ClazzyStudioChildTheme\Activator;

class ActivatorTest extends TestCase
{
	use ProphecyTrait;

	private $loaderMock;

	public function setUp(): void
	{
		$this->loaderMock = $this->prophesize(Loader::class);

		$this->loaderMock->add_action(
			Argument::exact('after_switch_theme'),
			Argument::type(Activator::class),
			Argument::exact('isActive'),
		)
			->shouldBeCalled();

		$this->loaderMock->add_action(
			Argument::exact('tgmpa_register'),
			Argument::any(Activator::class),
			Argument::exact('registerRequiredPlugins'),
		)
			->shouldBeCalled();
	}

	public function testInitClass(): void
	{
		$loaderMock = $this->loaderMock;
		new Activator('test', '1.0', $loaderMock->reveal());
	}

	public function testIsActive(): void
	{
		$loaderMock = $this->loaderMock;

		$activator = new Activator('test', '1.0', $loaderMock->reveal());

		$this->assertNull($activator->isActive());
	}

	public function testRegisterRequiredPlugins(): void
	{
		$loaderMock = $this->loaderMock;

		$activator = new Activator('test', '1.0', $loaderMock->reveal());

		$this->assertNull($activator->registerRequiredPlugins());
	}

	/**
	 * @dataProvider github_repo_data_provider
	 */
	public function testGetGithubRepoData(string $owner, string $repo): void
	{
		$loaderMock = $this->loaderMock;

		$activator = new Activator('test', '1.0', $loaderMock->reveal());

		$this->assertEquals([], $activator->getGithubRepoData($owner, $repo));
	}

	public function github_repo_data_provider(): array
	{
		return [
			[
				'owner' => 'test-owner',
				'repo'  => 'test-repo',
			],
			[
				'owner' => 'JavierArdiles',
				'repo'  => 'JavierArdiles',
			],
		];
	}
}
