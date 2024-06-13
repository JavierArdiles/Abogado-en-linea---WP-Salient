<?php

namespace ClazzyStudioChildTheme\Exceptions;

use Exception;

// If this file is called directly, abort.
if (! defined('WPINC'))
{
	die;
}

class InvalidContractException extends Exception
{
	public function __construct(string $className, string $traitName)
	{
		$message = "Class '{$traitName}' is not a contract valid of '{$className}'.";
		parent::__construct($message);
	}
}
