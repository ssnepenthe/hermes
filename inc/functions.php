<?php

namespace SSNepenthe\Hermes;

function result_return_value(array $result, $default = '')
{
    if (empty($result)) {
        return $default;
    }

    if (1 === count($result)) {
        return current($result);
    }

    return $result;
}
