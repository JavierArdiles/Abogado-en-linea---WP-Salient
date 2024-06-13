<?php

namespace ClazzyStudioChildTheme\Tests;

use Foo\Bar\Contracts\C;
use Foo\Bar\Contracts\D;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use ClazzyStudioChildTheme\DemoClasses\Shortcode;
use ClazzyStudioChildTheme\DemoClasses\CustomType;
use ClazzyStudioChildTheme\DemoClasses\CustomField;
use ClazzyStudioChildTheme\DemoClasses\CustomTaxonomy;
use ClazzyStudioChildTheme\Contracts\AbstractShortcode;
use ClazzyStudioChildTheme\Contracts\AbstractCustomType;
use ClazzyStudioChildTheme\Traits\HasFolderScannerTrait;
use ClazzyStudioChildTheme\Contracts\AbstractCustomField;
use ClazzyStudioChildTheme\Contracts\AbstractCustomTaxonomy;
use ClazzyStudioChildTheme\Exceptions\InvalidClassException;
use ClazzyStudioChildTheme\Exceptions\InvalidDirectoryException;

class FolderScannerTest extends TestCase
{
	use HasFolderScannerTrait, ProphecyTrait;

	protected $traitFolder;

	public function setUp(): void
	{
		ini_set('error_log', 'log_file_name.log');

		$this->traitFolder = __DIR__ . '/../Assets/FooClasses';
	}

	/**
	 * @dataProvider scanFolderUsesCasesDataProvider
	 */
	public function test_scan_folder(array $skipFiles, array $expectedFiles): void
	{
		$this->setSkipFile($skipFiles);
		$files = $this->getFilesByFolder($this->traitFolder);

		$this->assertEquals($files, $expectedFiles);
	}

	public function test_get_classes_by_folder(): void
	{
		$files = $this->getClassesByFolder($this->traitFolder);

		$this->assertEquals($files, [
			'Foo\Bar\A',
			'Foos\Bar\AA',
			'Foo\Bar\AB',
			'Foo\Bar\B',
		]);
	}

	public function test_get_dependencies_by_folder_and_contract(): void
	{
		$files = $this->getClassesFromFolder(C::class, 'tests/Assets/FooClasses');

		$this->assertEquals($files, [
			'Foo\Bar\A',
			'Foo\Bar\B',
		]);
	}

	public function test_get_dependencies_by_folder_and_contract_in_bulk(): void
	{
		$result = $this->getClassesFromFolderInBulk([
			AbstractCustomField::class    => 'tests/Assets/DemoClasses',
			AbstractCustomType::class     => 'tests/Assets/DemoClasses',
			AbstractCustomTaxonomy::class => 'tests/Assets/DemoClasses',
			AbstractShortcode::class      => 'tests/Assets/DemoClasses',
		]);

		$this->assertEquals([
			CustomField::class,
			CustomType::class,
			CustomTaxonomy::class,
			Shortcode::class,
		], $result);
	}

	public function test_skip_file_scan_folder(): void
	{
		$skipFile = ['test.php'];

		$this->setSkipFile($skipFile);
		$this->assertEquals($skipFile, $this->getSkipFile());
	}

	public function test_class_invalid_exception(): void
	{
		$this->expectException(InvalidClassException::class);
		$this->expectExceptionMessage("Class 'Foos\Bar\AA' is invalid.");

		$filePath = $this->traitFolder . '/AA.php';

		$files = $this->getClassNameByFilePath($filePath, D::class);
	}

	public function test_contract_invalid_exception(): void
	{
		$this->expectException(InvalidClassException::class);
		$this->expectExceptionMessage("Class 'ClazzyStudioChildTheme\Test\CustomField' is invalid.");

		$filePath = __DIR__ . '/../Assets/DemoClasses/CustomField.php';

		$files = $this->getClassNameByFilePath($filePath, 'ClazzyStudioChildTheme\Test\CustomField');
	}

	public function test_directory_invalid_exception(): void
	{
		$this->expectException(InvalidDirectoryException::class);
		$this->expectExceptionMessage("Directory 'InvalidPath/' is invalid.");

		$files = $this->getFilesByFolder('InvalidPath/');
	}

	public function scanFolderUsesCasesDataProvider(): array
	{
		return [
			'all files of a folder' => [
				'skip'           => [],
				'expected files' => [
					'A.php',
					'AA.php',
					'AB.php',
					'B.php',
					'index.php',
				],
			],
			'get files of a folder skipping one' => [
				'skip'           => ['index.php'],
				'expected files' => [
					'A.php',
					'AA.php',
					'AB.php',
					'B.php',
				],
			],
		];
	}
}
