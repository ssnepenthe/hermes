<?php

namespace SSNepenthe\Hermes\Loader;

class JsonFileLoader extends FileLoader
{
    public function supports($resource, $type = null)
    {
        return is_string($resource)
            && 'json' === pathinfo($resource, PATHINFO_EXTENSION)
            && (! $type || 'json' === $type);
    }

    protected function loadAndParse(string $path)
    {
        return json_decode(file_get_contents($path), true);
    }
}
