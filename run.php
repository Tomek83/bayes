#!/usr/bin/php
<?php
require_once('./vendor/autoload.php');

try {
	$text="Oferty w sklepach i na naszej stronie można zobaczyć i kupić lub zakupić te produkty i produkt wspaniały";

	$obj=new bayes($text);

	print $text."\n";

	print ($obj->examine()) ? "To jest SPAM\n" : "To nie jest SPAM\n";

	#$obj->decision(TRUE);
}
catch (Exception $e) {
	print "Exception ".$e->getMessage()."\n";
}
?>
