<?php

use PHPUnit\Framework\TestCase;
use bayes\classes\DataFolder;
use bayes\classes\FileFetch;
use bayes\classes\InputText;
use bayes\classes\Collections;

class BayesTest extends TestCase
{
    /**
     * @var Collections
     */
    public $collections;

    /**
     * @var InputText
     */
    public $inputText;

    public function setUp()
    {
        $fileMock = $this->createMock(FileFetch::class);

        $fileMock
            ->expects($this->exactly(3))
            ->method('getContent')
            ->willReturnOnConsecutiveCalls(
                "politycy a szczególnie Pani Premier nie chodzą na zakupy, gry trafi się okazja wybierają polski sklep",
                "politycy;;0,4\npremier;;0,2\npolski;;0,2\ninstytut;;0,2",
                "oferta;;0,6\nokazja;;0,4\nzakupy;;0,2\nsklep;;0,4"
            );

        $dataMock = $this->createMock(DataFolder::class);

        $dataMock
            ->expects($this->once())
            ->method('readData')
            ->willReturn(['politics', 'offers']);

        $this->inputText = new InputText($fileMock->getContent(null));

        $this->collections = new Collections($dataMock, $fileMock);
    }

    public function tearDown()
    {
        unset($this->inputText);

        $this->inputText = null;

        unset($this->collections);

        $this->collections = null;
    }

    public function testCollection()
    {
        $politics = $this->collections->getCollection('politics')->itemsToArray();

        $offers = $this->collections->getCollection('offers')->itemsToArray();

        $this->assertEquals([['politycy', 0.4], ['premier', 0.2], ['polski', 0.2], ['instytut', 0.2]], $politics);

        $this->assertEquals([['oferta', 0.6], ['okazja', 0.4], ['zakupy', 0.2], ['sklep', 0.4]], $offers);
    }

    /**
     * @param string $inputValue
     * @dataProvider inputExpected
     */
    public function testInput($inputValue)
    {
        $this->assertContains($inputValue, $this->inputText->getWords());
    }

    public function testProcess()
    {
        $targetColl = $this->collections->getCollection('politics');

        foreach ($this->collections as $collection) $collection->processAll($this->inputText, $targetColl);

        $politics = $this->collections->getCollection('politics')->itemsToArray();

        $offers = $this->collections->getCollection('offers')->itemsToArray();

        $this->assertEquals([['politycy', 0.6], ['premier', 0.4], ['polski', 0.4], ['instytut', 0.2]], $politics);

        $this->assertEquals([['oferta', 0.6], ['okazja', 0.2], ['sklep', 0.2]], $offers);

        $targetColl->mergeItems($this->inputText);

        $this->assertEquals([
            ['politycy', 0.6], ['premier', 0.4], ['polski', 0.4], ['instytut', 0.2], ['szczególnie', 0.2], ['pani', 0.2], ['chodzą', 0.2], ['zakupy', 0.2], ['trafi', 0.2], ['okazja', 0.2], ['wybierają', 0.2], ['sklep', 0.2]
        ], $targetColl->itemsToArray());
    }

    function testHighestScore()
    {
        foreach ($this->collections as $collection) $collection->matchAll($this->inputText);

        $collection = $this->collections->getHighestScore();

        $this->assertEquals('offers', $collection->getName());

        $this->assertEquals(1, $collection->getScore());
    }

    public function inputExpected()
    {
        return [
            ['politycy'],
            ['premier'],
            ['zakupy'],
            ['wybierają'],
            ['okazja']
        ];
    }
}