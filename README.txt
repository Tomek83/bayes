This PHP package provides simple implementation of bayes text analysis algorithm. With this tool you can determine genre of a text. Available genres are collected in data folder in form of a text files. Adding new genre is simple, just add new empty file to data folder and give it’s name for instance ‘genrename.txt’. By default source text for learning and examining is placed in SOURCE.txt file. Phpunit test is available for this class. You can install this package in your project via composer

Learning process

This tool needs to be learn somehow, so it needs to collect sample texts for each genre. We can do it by filling SOURCE.txt file with desired text and then telling what genre SOURCE.txt file contains. Spending more time on learning gives more accurate results when examining

Usage (PHP CLI):

./learn.php genrename

genrename – name of a genre file in data folder

Examining process

This process will tell you what sort of a text SOURCE.txt file contains. If for some reasons results are bad then you can run learning process again on that same source. Key feature of this algorithm is that it’s learning from you

Usage (PHP CLI):

./examine.php

Output:

Source text fits most to genrename!
