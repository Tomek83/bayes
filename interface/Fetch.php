<?php
namespace bayes\interfaces;

interface Fetch
{
    function getContent($filePath);

    function putContent($filePath, $fileContent);
}