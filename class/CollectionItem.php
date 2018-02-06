<?php
namespace bayes\classes;

class CollectionItem
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $record
     */
    public function __construct(array $record)
    {
        $this->data = [$record[0], (float)str_replace(',', '.', $record[1])];
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->data[0];
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->data[1];
    }

    public function weightUp()
    {
        (($this->data[1] + SCALE) > 1) ? null : $this->data[1] += SCALE;
    }

    public function weightDown()
    {
        (($this->data[1] - SCALE) < 0) ? null : $this->data[1] -= SCALE;
    }
}