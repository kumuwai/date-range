<?php namespace Kumuwai\DateRange;


/**
 * This is a stub to return Laravel-style configuration settings 
 * in a non-Laravel environment. 
 */
class Config
{
	private $file;
	private $path;

	public function __construct($path = Null)
	{
		$this->path = $path ?: __DIR__.'/../../config/config.php';
	}

	public function get($name)
	{
		if ( ! $this->file )
			$this->file = $this->loadFile($this->path);

		return $this->resolve($this->file, $this->removeNamespace($name), '');
	}

	private function loadFile($path)
	{
		$realpath = realpath($path);

		if ( ! is_file($realpath))
			throw new FileNotFoundException("File does not exist at path {$realpath}");

		return include($realpath);
	}

	private function removeNamespace($name)
	{
		$pos = strpos($name, '::');
		return substr($name, $pos === False ? 0 : $pos + 2);
	}

	private function resolve(array $arr, $path, $default = null)
	{
		$current = $arr;
		$p = strtok($path, '.');

		while ($p !== false) {
			if (!isset($current[$p])) {
				return $default;
			}
			$current = $current[$p];
			$p = strtok('.');
		}

		return $current;
	}

}

