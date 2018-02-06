<?php
namespace bayes\classes;

use bayes\interfaces\Text;

class InputText implements Text
{
    /**
     * @var array
     */
    private $words = [];
    /**
     * @var array
     */
    private $keys = [];

    /**
     * @param string $inputText
     * @throws \Exception
     */
    public function __construct($inputText)
    {
        if (mb_strlen($inputText) < TEXT_LENGTH) throw new \Exception('input text must have at least ' . TEXT_LENGTH . ' characters');

        $this->words = array_filter(array_unique(preg_split('/\s+/', mb_ereg_replace('[[:punct:]]', '', mb_strtolower($inputText)))), function ($value) {
            return (mb_strlen($value) > WORD_LENGTH) ? true : false;
        });
    }

    /**
     * @return array
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param integer $key
     */
    public function dropWord($key)
    {
        $this->keys[] = $key;
    }

    public function clearDropped()
    {
        foreach ($this->keys as $key) {
            unset($this->words[$key]);
        }
    }
}