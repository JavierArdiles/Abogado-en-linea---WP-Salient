<?php

namespace ClazzyStudioChildTheme;

use ClazzyStudioChildTheme\Contracts\AbstractBase;
use ClazzyStudioChildTheme\Contracts\AbstractShortcode;
use ClazzyStudioChildTheme\Contracts\AbstractCustomType;
use ClazzyStudioChildTheme\Traits\HasFolderScannerTrait;
use ClazzyStudioChildTheme\Contracts\AbstractCustomField;
use ClazzyStudioChildTheme\Contracts\AbstractCustomTaxonomy;
use ClazzyStudioChildTheme\Exceptions\InvalidClassException;

if (! defined('WPINC'))
{
	die;
}

class ChildTheme extends AbstractBase
{
	use HasFolderScannerTrait;

	const DIR_SHORTCODES        = 'inc/classes/Shortcodes';
	const DIR_CUSTOM_FIELDS     = 'inc/classes/CustomFields';
	const DIR_CUSTOM_TYPES      = 'inc/classes/CustomTypes';
	const DIR_CUSTOM_TAXONOMIES = 'inc/classes/CustomTaxonomies';

	private array $defaultDependencies = [
		Admin::class,
		Shared::class,
		i18n::class,
		Activator::class,
	];

	private array $dependencies;

	private array $autoloadFolders = [
		AbstractCustomField::class    => self::DIR_CUSTOM_FIELDS,
		AbstractCustomType::class     => self::DIR_CUSTOM_TYPES,
		AbstractCustomTaxonomy::class => self::DIR_CUSTOM_TAXONOMIES,
		AbstractShortcode::class      => self::DIR_SHORTCODES,
	];

	protected $loader;

	public function init(): void
	{
		$defaultFolders = $this->getFolders();

		$this->setDependencies([
			...$this->getDefaultDependencies(),
			...$this->getClassesFromFolderInBulk($defaultFolders),
		]);
	}

	protected function initDependencies(array $dependencies = []): void
	{
		foreach ($dependencies as $className)
		{
			try
			{
				$this->initInstance($className);
			}
			catch (\Throwable $th)
			{
				error_log('Error: ' . $th->getMessage());
				continue;
			}
		}
	}

	public function initInstance(string $className): void
	{
		if (! class_exists($className))
		{
			throw new InvalidClassException($className);
		}

		$this->$className = new $className(
			$this->getThemeName(),
			$this->getVersion(),
			$this->getLoader()
		);
	}

	public function getDefaultDependencies(): array
	{
		return $this->defaultDependencies;
	}

	public function getDependencies(): array
	{
		return $this->dependencies;
	}

	public function getFolders(): array
	{
		return $this->autoloadFolders;
	}

	public function setDependencies(array $dependencies): self
	{
		$this->dependencies = $dependencies;

		return $this;
	}

	public function run(): bool
	{
		$this->initDependencies($this->getDependencies());
		$this->getLoader()->run();

		return true;
	}
}
