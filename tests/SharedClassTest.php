<?php

namespace ClazzyStudioChildTheme\Tests;

use Prophecy\Argument;
use PHPUnit\Framework\TestCase;
use ClazzyStudioChildTheme\Loader;
use ClazzyStudioChildTheme\Shared;
use Prophecy\PhpUnit\ProphecyTrait;

use function PHPUnit\Framework\isNull;

class SharedClassTest extends TestCase
{
	use ProphecyTrait;

	private $loaderMock;

	public function setUp(): void
	{
		$this->loaderMock = $this->prophesize(Loader::class);
	}

	public function test_init_shared_class(): void
	{
		$loaderMock = $this->loaderMock;

		$this->loaderMock->add_action(
			Argument::exact('wp_enqueue_scripts'),
			Argument::type(Shared::class),
			Argument::type('string'),
			Argument::type('integer')
		)
			->shouldBeCalled();

		$this->loaderMock->add_filter(
			Argument::exact('rest_authentication_errors'),
			Argument::any(),
			Argument::exact('disableApiRestToAnonymousUser'),
		)
			->shouldBeCalled();

		new Shared('test', '1.0', $loaderMock->reveal());
	}

	public function test_get_asset_handle(): void
	{
		$loaderMock = $this->loaderMock;

		$this->loaderMock->add_action(
			Argument::exact('wp_enqueue_scripts'),
			Argument::type(Shared::class),
			Argument::type('string'),
			Argument::type('integer')
		)
			->shouldBeCalled();

		$this->loaderMock->add_filter(
			Argument::exact('rest_authentication_errors'),
			Argument::any(),
			Argument::exact('disableApiRestToAnonymousUser'),
		)
			->shouldBeCalled();

		$shared = new Shared('test', '1.0', $loaderMock->reveal());

		$this->assertEquals($shared->getAssetHandle('child-style'), 'test-child-style');
		$this->assertEquals($shared->getAssetHandle('child'), 'test-child');
	}

	/**
	 * @dataProvider styleScenariosDataProvider
	 */
	public function test_enqueue_styles_handle(
		bool $isRtlEnable,
		int $expectedExecutions
	): void {
		$loaderMock = $this->loaderMock;

		$this->loaderMock->add_action(
			Argument::exact('wp_enqueue_scripts'),
			Argument::type(Shared::class),
			Argument::type('string'),
			Argument::type('integer')
		)
			->shouldBeCalled();

		$this->loaderMock->add_filter(
			Argument::exact('rest_authentication_errors'),
			Argument::any(),
			Argument::exact('disableApiRestToAnonymousUser'),
		)
			->shouldBeCalled();

		$this->loaderMock->execFn(
			Argument::exact('wp_enqueue_style'),
			Argument::type('array'),
		)
			->shouldBeCalledTimes($expectedExecutions);

		$this->loaderMock->execFn(Argument::exact('is_rtl'))
			->willReturn($isRtlEnable)
			->shouldBeCalled();

		$shared = new Shared('test', '1.0', $loaderMock->reveal());
		$shared->enqueueStyles();
	}

	/**
	 * @dataProvider apiRequestsDataProvider
	 */
	public function test_disable_api_rest_to_anonymous_user(
		?bool $loggedUser,
		?bool $previusError,
		string $request,
		mixed $expectedResult,
	): void {
		$loaderMock = $this->loaderMock;

		if (! isNull($loggedUser))
		{
			$loaderMock->execFunction('is_user_logged_in')
				->shouldBeCalled()
				->willReturn($loggedUser);

		}

		$GLOBALS['wp']->request = $request;

		$shared = new Shared('test', '1.0', $loaderMock->reveal());
		$result = $shared->disableApiRestToAnonymousUser($previusError);

		$this->assertEquals($expectedResult, $result);
	}

	public function styleScenariosDataProvider(): array
	{
		return [
			'rtl is disabled' => [
				'isRtlEnable'        => false,
				'expectedExecutions' => 2,
			],
			'rtl is enabled' => [
				'isRtlEnable'        => true,
				'expectedExecutions' => 3,
			],
		];
	}

	public function apiRequestsDataProvider(): array
	{
		return [
			'anonymous user send a ramdom request and have another error' => [
				'loggedUser'     => null,
				'previusError'   => true,
				'request'        => 'wp-json/ramdom',
				'expectedResult' => true,
			],
			'anonymous user send a ramdom request' => [
				'loggedUser'     => false,
				'previusError'   => null,
				'request'        => 'wp-json/ramdom',
				'expectedResult' => new \WP_Error(
					'rest_not_logged_in',
					__('REST API not available for anonymous users.'),
					['status' => 401]
				),
			],
			'anonymous user send a contact form request' => [
				'loggedUser'     => false,
				'previusError'   => null,
				'request'        => 'wp-json/contact-form-7/v1/contact-forms/37/feedback/schema',
				'expectedResult' => null,
			],
			'logged user send a contact form request' => [
				'loggedUser'     => true,
				'previusError'   => null,
				'request'        => 'wp-json/contact-form-7/v1/contact-forms/37/feedback/schema',
				'expectedResult' => null,
			],
		];
	}
}
