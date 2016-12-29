#!/usr/bin/php
<?php
class BayesSimpleTest extends PHPUnit_Framework_TestCase {
    public function testSimple1 ()
    {
	$tekst='pójdziemy do kina lub na spacer w zależności jaka będzie pogoda';
	$object=new bayes($tekst);
	$this->assertInstanceOf('bayes', $object);
	$this->assertFalse($object->examine());
	return $object;
    }
/**
 * @depends testSimple1
*/
    public function testSimple2 (bayes $object)
    {
	$L=$object->return_L ();
	$this->assertInternalType('array', $L);
	return $L;
    }
/**
 * @depends testSimple1
*/
    public function testSimple3 (bayes $object)
    {
	$P=$object->return_P ();
	$this->assertInternalType('array', $P);
	return $P;
    }
/**
 * @depends testSimple2
 * @depends testSimple3
*/
    public function testSimple4 (array $L, array $P)
    {
	$L=array_pop($L);
	$this->assertInternalType('string', $L[0]);
	$this->assertInternalType('float', $L[1]);
	$P=array_pop($P);
	$this->assertInternalType('string', $P[0]);
	$this->assertInternalType('float', $P[1]);
    }
/**
 * @expectedException Exception
*/
    public function testSimple5 ()
    {
	$tekst='za krótki tekst';
	$object=new bayes($tekst);
    }
}
?>
