<?php

namespace SSNepenthe\Hermes\Loader;

class PhpFileLoader extends FileLoader
{
    public function supports($resource, $type = null)
    {
        return is_string($resource)
            && 'php' === pathinfo($resource, PATHINFO_EXTENSION)
            && (! $type || 'php' === $type);
    }

    protected function loadAndParse(string $path)
    {
        return static::includeFile($path);
    }

    protected static function includeFile(string $path)
    {
        return include $path;
    }
}
