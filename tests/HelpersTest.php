<?php

namespace ClazzyStudioChildTheme\Tests;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
	public function test_child_theme_get_template_view(): void
	{
		$this->assertEquals(child_theme_get_template_view('', ''), null);
	}

	/**
	 * @dataProvider child_theme_get_svg_data_provider
	 */
	public function test_child_theme_get_svg(string $testFilename, ?string $testPath, mixed $result): void
	{
		$this->assertEquals(child_theme_get_svg($testFilename, $testPath), $result);
	}

	public function child_theme_get_svg_data_provider(): array
	{
		return [
			[
				'testFilename' => '',
				'testPath'     => '',
				'result'       => null,
			],
			[
				'testFilename' => 'test',
				'testPath'     => '/tests/Assets/',
				'result'       => '<svg></svg>',
			],
			[
				'testFilename' => 'false-test',
				'testPath'     => 'false-path',
				'result'       => false,
			],
		];
	}
}
