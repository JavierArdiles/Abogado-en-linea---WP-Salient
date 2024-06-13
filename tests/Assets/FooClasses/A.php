<?php

namespace Foo\Bar;

use Foo\Bar\Contracts\C;

class A extends C
{
	public function hello()
	{
		return 'Hello';
	}
}
