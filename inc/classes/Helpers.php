<?php

if (!function_exists('child_theme_get_template_view')) {
	function child_theme_get_template_view(string $slug, string $name, array $args = []): void
	{
		global $post;

		if (empty($name) || empty($slug)) {
			return;
		}

		$slug = sprintf('inc/views/%s', $slug);

		get_template_part($slug, $name, array_merge($args, [
			'theme' => CHILD_THEME_NAME,
		]));
	}
}

if (!function_exists('child_theme_get_svg')) {
	function child_theme_get_svg(string $filename, ?string $path = null)
	{
		if (empty($filename)) {
			return;
		}

		if (empty($path)) {
			$path = '/inc/resources/images/';
		}

		return @file_get_contents(CHILD_THEME_PATH . $path . $filename . '.svg') ?? false;
	}
}

if (!function_exists('console_log')) {
	function console_log($output, $with_script_tags = true): void
	{
		$js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
		if ($with_script_tags) {
			$js_code = '<script>' . $js_code . '</script>';
		}
		echo $js_code;
	}
}
