#!/usr/bin/php
<?php

require_once 'vendor/autoload.php';

use bayes\classes\DataFolder;
use bayes\classes\FileFetch;
use bayes\classes\InputText;
use bayes\classes\Collections;

try {

    $collName = (empty($argv[1])) ? null : trim($argv[1]);

    if (is_null($collName)) throw new Exception('collection name not provided');

    $file = new FileFetch();

    $inputText = new InputText($file->getContent(INPUT_FILE));

    $collections = new Collections(new DataFolder(), $file);

    $targetColl = $collections->getCollection($collName);

    foreach ($collections as $collection) {
        $collection->processAll($inputText, $targetColl);
    }

    $targetColl->mergeItems($inputText);

    $collections->saveAll();

} catch (Exception $err) {

    print "Exception: " . $err->getMessage() . "!\n";

}