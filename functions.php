<?php

define('CHILD_THEME_NAME', 'Salient');
define('CHILD_THEME_VERSION', '1.1.3');
define('CHILD_THEME_URI', get_stylesheet_directory_uri());
define('CHILD_THEME_PATH', __DIR__ . DIRECTORY_SEPARATOR);

/**
 * If this file is called directly, abort.
 */
if (! \defined('WPINC')) {
	die;
}

/**
 * Include the autoloader so we can dynamically include the rest of the classes.
 */
$loader = __DIR__ . '/vendor/autoload.php';

if (file_exists($loader))
{
	require $loader;
}

spl_autoload_register(function(string $className): void
{
	$includeFolder = 'inc\classes';
	$class         = str_replace(['ClazzyStudioChildTheme', '\\'], [$includeFolder, DIRECTORY_SEPARATOR], $className);
	$classPath     = CHILD_THEME_PATH . $class . '.php';

	$parts      = explode('\\', $className);
	$class      = array_pop($parts);
	$folders    = mb_strtolower(implode(DIRECTORY_SEPARATOR, $parts));
	$wpPath     = dirname(__FILE__) . DIRECTORY_SEPARATOR . $includeFolder . DIRECTORY_SEPARATOR . $folders . DIRECTORY_SEPARATOR . $class . '.php';

	if (file_exists($classPath))
	{
		include_once $classPath;
		return;
	}

	if (file_exists($wpPath))
	{
		include_once $wpPath;
		return;
	}
});

require_once __DIR__ . '/inc/plugins/TGM_Plugin_Activation.php';
require_once __DIR__ . '/inc/classes/Helpers.php';

$mainClass = new \ClazzyStudioChildTheme\ChildTheme(CHILD_THEME_NAME, CHILD_THEME_VERSION, new \ClazzyStudioChildTheme\Loader() );
$mainClass->run();