<?php
namespace bayes\classes;

use bayes\interfaces\Folder;

class DataFolder implements Folder
{
    /**
     * @return array
     * @throws \Exception
     */
    function readData()
    {
        $result = [];

        if (!is_dir(DATA_DIR)) throw new \Exception('error reading data folder');

        foreach (scandir(DATA_DIR) as $file) {
            if (preg_match('/^([a-z0-9\_]+)\.txt$/i', $file, $match)) {
                $result[] = trim($match[1]);
            }
        }

        return $result;
    }
}