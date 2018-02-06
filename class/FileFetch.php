<?php
namespace bayes\classes;

use bayes\interfaces\Fetch;

class FileFetch implements Fetch
{
    /**
     * @param string $filePath
     * @throws \Exception
     * @return string
     */
    public function getContent($filePath)
    {
        if (!file_exists($filePath)) throw new \Exception('error opening file ' . basename($filePath));
        return trim(@file_get_contents($filePath));
    }

    /**
     * @param string $filePath
     * @param string $fileContent
     * @return int
     */
    public function putContent($filePath, $fileContent)
    {
        return @file_put_contents($filePath, trim($fileContent));
    }
}