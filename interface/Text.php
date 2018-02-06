<?php
namespace bayes\interfaces;

interface Text
{
    function getWords();

    function dropWord($key);

    function clearDropped();
}