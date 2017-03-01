<?php

namespace SSNepenthe\Hermes\Extractor;

abstract class FromChildren extends BaseExtractor
{
    protected function extractValueFromChildNodes(\DOMElement $element)
    {
        $values = [];

        foreach ($element->childNodes as $child) {
            if (! in_array($child->nodeType, [1, 3], true)) {
                continue;
            }


            if ('_text' === $this->attr) {
                $valueProp = 1 === $child->nodeType ? 'nodeValue' : 'wholeText';

                $values[] = $child->{$valueProp};
            } else {
                $values[] = $child->getAttribute($this->attr);
            }
        }

        $values = array_filter(array_map('trim', $values));

        return implode(' ', $values);
    }
}
