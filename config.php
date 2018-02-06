<?php

setlocale(LC_ALL, 'pl_PL.utf8');

mb_internal_encoding('UTF-8');

mb_regex_encoding('UTF-8');

define('DATA_DIR', realpath('./data/'));

define('CONTAINER', DATA_DIR . '/' . basename('%s.txt'));

define('SCALE', 0.2);

define('WORD_LENGTH', 3);

define('TEXT_LENGTH', 30);

define('RECORD_GLUE', ';;');

define('INPUT_FILE', basename('SOURCE.txt'));