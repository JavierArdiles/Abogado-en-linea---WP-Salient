<?php

namespace ClazzyStudioChildTheme\Exceptions;

use Exception;

// If this file is called directly, abort.
if (! defined('WPINC'))
{
	die;
}

class InvalidClassException extends Exception
{
	public function __construct(string $className)
	{
		$message = "Class '{$className}' is invalid.";
		parent::__construct($message);
	}
}
