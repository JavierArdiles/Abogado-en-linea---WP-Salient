<?php

namespace Foo\Bar\Traits;

if (! \defined('WPINC'))
{
	die;
}

trait T
{
	public function bye()
	{
		return 'Bye';
	}
}
