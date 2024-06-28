<?php

namespace HW\Lib;

class LinkedList
{
    /** @var LinkedListItem|null */
    protected $first = null;

    /** @var LinkedListItem|null */
    protected $last = null;

    /**
     * @return LinkedListItem|null
     */
    public function getFirst(): ?LinkedListItem
    {
        return $this->first;
    }

    /**
     * @param LinkedListItem|null $first
     * @return LinkedList
     */
    public function setFirst(?LinkedListItem $first): LinkedList
    {
        $this->first = $first;

        return $this;
    }

    /**
     * @return LinkedListItem|null
     */
    public function getLast(): ?LinkedListItem
    {
        return $this->last;
    }

    /**
     * @param LinkedListItem|null $last
     * @return LinkedList
     */
    public function setLast(?LinkedListItem $last): LinkedList
    {
        $this->last = $last;

        return $this;
    }

    /**
     * Place new item at the begining of the list
     *
     * @param string $value
     * @return LinkedListItem
     */
    public function prependList(string $value)
    {
        $item = new LinkedListItem($value);
        $second = $this->getFirst();
        $this->setFirst($item);
        $item->setNext($second);
        $second->setPrev($item);

        return $item;
    }

    /**
     * Place new item at the end of the list
     *
     * @param string $value
     * @return LinkedListItem
     */
    public function appendList(string $value)
    {
        $item = new LinkedListItem($value);
        $penultimate = $this->getLast();
        $this->setLast($item);
        $penultimate->setNext($item);
        $item->setPrev($penultimate);

        return $item;
    }

    /**
     * Insert item before $nextItem and maintain continuity
     *
     * @param LinkedListItem $nextItem
     * @param string         $value
     * @return LinkedListItem
     */
    public function prependItem(LinkedListItem $nextItem, string $value)
    {
		/*
			Predpokladame-li, ze ke vkladani prvku pred
			prvni prvek je pouzita funkce prependList(...).
		*/
        $item = new LinkedListItem($value);
        $item->setNext($nextItem);
        $item->setPrev($nextItem->getPrev());
		$nextItem->getPrev()->setNext($item);
        $nextItem->setPrev($item);

        return $item;
    }

    /**
     * Insert item after $prevItem and maintain continuity
     *
     * @param LinkedListItem $prevItem
     * @param string         $value
     * @return LinkedListItem
     */
    public function appendItem(LinkedListItem $prevItem, string $value)
    {
		/*
			Predpokladame-li, ze ke vkladani prvku za
			posledni prvek je pouzita funkce appendList(...).
		*/
        $item = new LinkedListItem($value);
        $item->setPrev($prevItem);
        $item->setNext($prevItem->getNext());
        $prevItem->getNext()->setPrev($item);
        $prevItem->setNext($item);

        return $item;
    }
}
