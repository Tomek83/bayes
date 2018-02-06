<?php
namespace bayes\classes;

use bayes\interfaces;

class Collection implements \Iterator
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var interfaces\Fetch
     */
    private $fetch;
    /**
     * @var float
     */
    private $score = 0.0;
    /**
     * @var array
     */
    private $items = [];

    /**
     * @param string $collName
     * @param interfaces\Fetch $fetch
     */
    public function __construct($collName, interfaces\Fetch $fetch)
    {
        $this->name = $collName;

        $this->fetch = $fetch;

        $filePath = sprintf(CONTAINER, $collName);

        $fileData = $this->fetch->getContent($filePath);

        foreach (explode("\n", $fileData) as $line) {
            if (!empty($line) && $record = explode(RECORD_GLUE, trim($line))) {
                $this->items[] = new CollectionItem($record);
            }
        }
    }

    /**
     * @param InputText $inputText
     */
    public function matchAll(InputText $inputText)
    {
        foreach ($inputText->getWords() as $word) {
            foreach ($this->items as $item) {
                if ($item->getWord() == $word) $this->scoreUp($item->getWeight());
            }
        }
    }

    /**
     * @param InputText $inputText
     * @param Collection $targetColl
     */
    public function processAll(InputText $inputText, Collection $targetColl)
    {
        foreach ($inputText->getWords() as $key => $word) {
            foreach ($this->items as $item) {
                if (mb_ereg_match('^' . $word . '$', $item->getWord())) {
                    if ($this->name == $targetColl->getName()) {
                        $item->weightUp();
                        $inputText->dropWord($key);
                    } else {
                        $item->weightDown();
                    }
                }
            }
        }
    }

    /**
     * @param float $weight
     */
    private function scoreUp($weight)
    {
        $this->score += $weight;
    }

    /**
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function itemsToArray()
    {
        $result = [];

        foreach ($this->items as $item) {
            if ($item->getWeight() > 0) {
                $result[] = [$item->getWord(), $item->getWeight()];
            }
        }

        return $result;
    }

    /**
     * @param InputText $inputText
     */
    public function mergeItems(InputText $inputText)
    {
        $inputText->clearDropped();

        foreach ($inputText->getWords() as $word) {
            $this->items[] = new CollectionItem([$word, SCALE]);
        }
    }

    public function saveFile()
    {
        $filePath = sprintf(CONTAINER, $this->name);

        $result = array_map(function ($value) {
            return implode(RECORD_GLUE, $value);
        }, $this->itemsToArray());

        $this->fetch->putContent($filePath, implode("\n", $result));
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return (key($this->items) !== null) ? true : false;
    }

    /**
     * @return CollectionItem
     */
    public function rewind()
    {
        reset($this->items);
    }

    /**
     * @return CollectionItem
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * @return CollectionItem
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * @return CollectionItem
     */
    public function key()
    {
        return key($this->items);
    }
}