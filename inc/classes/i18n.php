<?php

namespace ClazzyStudioChildTheme;

use ClazzyStudioChildTheme\Contracts\AbstractBase;

if (! \defined('WPINC'))
{
	die;
}

class i18n extends AbstractBase
{
	public function init(): void
	{
		$this->loadTextdomain();
	}

	protected function loadTextdomain(): void
	{
		load_theme_textdomain(
			$this->getThemeName(),
			sprintf('%s/inc/languages/', get_template_directory())
		);
	}
}
