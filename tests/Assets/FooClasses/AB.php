<?php

namespace Foo\Bar;

use Foo\Bar\Traits\T;
use Foo\Bar\Contracts\D;

class AB extends D
{
	use T;

	public function hello()
	{
		return 'Hello' . $this->bye();
	}
}
