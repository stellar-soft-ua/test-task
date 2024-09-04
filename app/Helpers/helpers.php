<?php

if (! function_exists('arrayToDotNotation')) {

    function arrayToDotNotation(array $inputArray): array
    {
        $ritit = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($inputArray));
        $result = [];
        foreach ($ritit as $leafValue) {
            $keys = [];
            foreach (range(0, $ritit->getDepth()) as $depth) {
                $keys[] = $ritit->getSubIterator($depth)->key();
            }
            $result[implode('.', $keys)] = $leafValue;
        }

        return $result;
    }

}
