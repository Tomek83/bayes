#!/usr/bin/php
<?php

setlocale(LC_ALL, 'pl_PL.utf8');
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');

define('PATH', dirname(__FILE__));
define('L', PATH.'/data/L.txt'); # Zbiór L Spam
define('P', PATH.'/data/P.txt'); # Zbiór P Nie Spam
define('SCALE', 0.2); # Skala o jaką zwiększamy lub zmiejszamy wagi dla słów
define('WORD_LENGTH', 2); # Słowa muszą miec więcej niż WORD_LENGTH znaków
define('TEXT_LENGTH', 20); # Minimalna długość badanego tekstu

?>
