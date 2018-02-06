<?php
namespace bayes\classes;

use bayes\interfaces;

class Collections implements \Iterator
{
    /**
     * @var array
     */
    private $collections = [];

    /**
     * @param interfaces\Folder $dir
     * @param interfaces\Fetch $fetch
     */
    public function __construct(interfaces\Folder $dir, interfaces\Fetch $fetch)
    {
        foreach ($dir->readData() as $name) {
            $this->collections[] = new Collection($name, $fetch);
        }
    }

    /**
     * @param string $collName
     * @throws \Exception
     * @return Collection
     */
    public function getCollection($collName)
    {
        foreach ($this->collections as $collection) {
            if ($collection->getName() == $collName) {
                return $collection;
            }
        }

        throw new \Exception('error getting ' . $collName . ' collection');
    }

    /**
     * @return Collection
     */
    public function getHighestScore()
    {
        $firstColl = $this->collections[0];

        foreach ($this->collections as $collection) {
            if ($collection->getScore() > $firstColl->getScore()) {
                $firstColl = $collection;
            }
        }

        return $firstColl;
    }

    public function saveAll()
    {
        foreach ($this->collections as $collection) {
            $collection->saveFile();
        }
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return (key($this->collections) !== null) ? true : false;
    }

    /**
     * @return Collection
     */
    public function rewind()
    {
        reset($this->collections);
    }

    /**
     * @return Collection
     */
    public function next()
    {
        return next($this->collections);
    }

    /**
     * @return Collection
     */
    public function current()
    {
        return current($this->collections);
    }

    /**
     * @return Collection
     */
    public function key()
    {
        return key($this->collections);
    }
}