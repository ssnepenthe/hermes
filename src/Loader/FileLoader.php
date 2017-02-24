<?php

namespace SSNepenthe\Hermes\Loader;

use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Config\Loader\FileLoader as BaseFileLoader;

abstract class FileLoader extends BaseFileLoader
{
    public function load($resource, $type = null)
    {
        if (! $this->supports($resource, $type)) {
            throw new FileLoaderLoadException($resource);
        }

        $path = $this->locator->locate($resource);
        $this->setCurrentDir(dirname($path));

        $configs = [$this->loadAndParse($path)];

        if (isset($configs[0]['extends'])) {
            $subResource = $configs[0]['extends'];
            unset($configs[0]['extends']);

            $configs = array_merge($this->import($subResource), $configs);
        }

        return $configs;
    }

    abstract protected function loadAndParse(string $path);
}
