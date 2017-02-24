<?php

namespace SSNepenthe\Hermes\Loader;

use Symfony\Component\Yaml\Yaml;

class YamlFileLoader extends FileLoader
{
    public function supports($resource, $type = null)
    {
        $extension = pathinfo($resource, PATHINFO_EXTENSION);

        return is_string($resource)
            && ('yaml' === $extension || 'yml' === $extension)
            && (! $type || 'yaml' === $type || 'yml' === $type);
    }

    protected function loadAndParse(string $path)
    {
        return Yaml::parse(file_get_contents($path));
    }
}
