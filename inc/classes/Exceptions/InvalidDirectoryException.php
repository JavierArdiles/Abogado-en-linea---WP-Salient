<?php

namespace ClazzyStudioChildTheme\Exceptions;

use Exception;

// If this file is called directly, abort.
if (! defined('WPINC'))
{
	die;
}

class InvalidDirectoryException extends Exception
{
	public function __construct(string $path)
	{
		$message = "Directory '{$path}' is invalid.";
		parent::__construct($message);
	}
}
