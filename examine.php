#!/usr/bin/php
<?php

require_once 'vendor/autoload.php';

use bayes\classes\DataFolder;
use bayes\classes\FileFetch;
use bayes\classes\InputText;
use bayes\classes\Collections;

try {

    $file = new FileFetch();

    $inputText = new InputText($file->getContent(INPUT_FILE));

    $collections = new Collections(new DataFolder(), $file);

    foreach ($collections as $collection) $collection->matchAll($inputText);

    printf("Source text fits most to %s!\n", $collections->getHighestScore()->getName());

} catch (Exception $err) {

    print "Exception: " . $err->getMessage() . "!\n";

}