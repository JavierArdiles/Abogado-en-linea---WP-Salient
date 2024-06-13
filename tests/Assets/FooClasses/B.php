<?php

namespace Foo\Bar;

use Foo\Bar\Traits\T;
use Foo\Bar\Contracts\C;

class B extends C
{
	use T;

	public function hello()
	{
		return 'Hello' . $this->bye();
	}
}
