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
        $this->path = $path ?: __DIR__.'/config/config.php';
    }

    /**
     * Get Laravel-style configuration settings
     * date-range::path.to.key
     * 
     * @param  string $name full path to the key you want to find
     */
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

    /**
     * Resolve a dot-notation path from a multidimensional array
     * 
     * @param  array  $arr     array in which to search
     * @param  string $path    path to search (eg, path.to.key)
     * @param  any    $default value to return if path not found
     */
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

