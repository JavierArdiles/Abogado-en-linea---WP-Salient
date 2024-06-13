<?php

namespace Foos\Bar;

use Foo\Bar\Traits\T;
use Foo\Bar\Contracts\D;

class AA extends D
{
	use T;

	public function hello()
	{
		return 'Hello' . $this->bye();
	}
}
