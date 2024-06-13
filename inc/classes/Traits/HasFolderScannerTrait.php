<?php

namespace ClazzyStudioChildTheme\Traits;

use ClazzyStudioChildTheme\Exceptions\InvalidClassException;
use ClazzyStudioChildTheme\Exceptions\InvalidContractException;
use ClazzyStudioChildTheme\Exceptions\InvalidDirectoryException;

trait HasFolderScannerTrait
{
	private array $skipFile = ['index.php'];

	public function setSkipFile(array $skipFile): self
	{
		$this->skipFile = $skipFile;

		return $this;
	}

	public function getSkipFile(): array
	{
		return $this->skipFile;
	}

	public function getClassesFromFolderInBulk(array $folders): array
	{
		$classes = [];

		foreach ($folders as $contract => $folder)
		{
			$classesInFolder = $this->getClassesFromFolder($contract, $folder);
			array_push($classes, ...$classesInFolder);
		}

		return $classes;
	}

	public function getClassesFromFolder(string $contractClass, string $relativeFolderPath)
	{
		$classes            = [];
		$absoluteFolderPath = CHILD_THEME_PATH . $relativeFolderPath;

		$files = $this->getFilesByFolder($absoluteFolderPath);

		foreach ($files as $file)
		{
			try
			{
				$filePath  = sprintf('%s/%s', $absoluteFolderPath, $file);
				$classes[] = $this->getClassNameByFilePath($filePath, $contractClass);
			}
			catch (\Throwable $th)
			{
				error_log('Error: ' . $th->getMessage());
				continue;
			}
		}

		return $classes;
	}

	public function getFilesByFolder(string $absoluteFolderPath): array
	{
		$skipFile = $this->getSkipFile();

		if (! is_dir($absoluteFolderPath))
		{
			throw new InvalidDirectoryException($absoluteFolderPath);
		}

		$files = array_filter(scandir($absoluteFolderPath), function($file) use ($absoluteFolderPath, $skipFile)
		{
			$filePath = sprintf('%s/%s', $absoluteFolderPath, $file);

			return is_file($filePath) && ! in_array($file, $skipFile) && mb_strpos($file, '.php');
		});

		return array_values($files);
	}

	public function getClassesByFolder(string $absoluteFolderPath): array
	{
		$classes = [];
		$files   = $this->getFilesByFolder($absoluteFolderPath);

		foreach ($files as $file)
		{
			$filePath  = sprintf('%s/%s', $absoluteFolderPath, $file);
			$classes[] = $this->buildClassName($filePath);
		}

		return $classes;
	}

	public function getClassNameByFilePath(string $filePath, string $contractClass): string
	{
		$className = $this->buildClassName($filePath);

		if (! class_exists($className))
		{
			throw new InvalidClassException($className);
		}

		if (! class_exists($contractClass))
		{
			throw new InvalidClassException($contractClass);
		}

		if (! is_subclass_of($className, $contractClass))
		{
			throw new InvalidContractException($className, $contractClass);
		}

		return $className;
	}

	protected function buildClassName(string $absoluteFolderPath)
	{
		$src = file_get_contents($absoluteFolderPath);

		$tokens = token_get_all($src);

		$namespace = $this->getNamespaceFromFile($tokens);
		$className = $this->getClassNameFromFile($tokens);

		return sprintf('%s\%s', $namespace, $className);
	}

	protected function getClassNameFromFile(array $tokens)
	{
		$classes = [];
		$count   = count($tokens);

		for ($i = 2; $i < $count; $i++)
		{
			if ($tokens[$i - 2][0] == T_CLASS
				&& $tokens[$i - 1][0] == T_WHITESPACE
				&& $tokens[$i][0]     == T_STRING
			) {
				$class_name = $tokens[$i][1];
				$classes[]  = $class_name;
			}
		}

		return $classes[0] ?? '';
	}

	protected function getNamespaceFromFile($tokens): ?string
	{
		[$namespace, $namespaceOk] = $this->buildNamespace($tokens);

		if (! $namespaceOk)
		{
			return null;
		}

		return $namespace;
	}

	protected function buildNamespace(array $tokens)
	{
		$count     = count($tokens);
		$pos       = array_search(T_NAMESPACE, array_column($tokens, 0));
		$namespace = '';

		for ($i = ++$pos; $i < $count; $i++)
		{
			if ($tokens[$i] === ';')
			{
				$status    = true;
				$namespace = trim($namespace);
				break;
			}

			$namespace .= is_array($tokens[$i])
				? $tokens[$i][1]
				: $tokens[$i];
		}

		return [
			$namespace,
			$status ?? false,
		];
	}
}
