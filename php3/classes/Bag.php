<?php

class Bag
{
	protected $collection = [];
	
	/* Prida prvek do tasky. */
    public function add($item)
	{
        array_push($this->collection, $item);
    } // ADD

	/* Vyprazdni tasku. */
    public function clear()
	{
        unset($this->collection); // znici celou kolekci
		$this->collection = array(); // vytvori novou kolekci
    } // CLEAR

	/* Testuje, zdali se prvek nachazi v tasce a naopak. */
    public function contains($item)
	{
		return in_array($item, $this->collection);
    } // CONTAINS

	/* Vraci pocet vyskytu daneho prvku v tasce. */
    public function elementSize($item)
	{
		$sum = 0;
		
        for ($i = 0; $i < count($this->collection); $i++)
			if ($this->collection[$i] == $item) $sum++;
			
		return $sum;
    } // ELEMENT SIZE

	/* Testuje, zdali je taska prazdna a naopak. */
    public function isEmpty()
	{
        return empty($this->collection);
    } // IS EMPTY

	/* Odebere prvek z tasky. */
    public function remove($item)
	{
		$key = array_search($item, $this->collection);
		if ($key === FALSE) return; // tri rovnitka, aby rozlisil FALSE a 0
        unset($this->collection[$key]);
		$this->collection = array_values($this->collection); // preindexovani
    } // REMOVE

	/* Zjisti celkovy pocet prvku v tasce. */
    public function size()
	{
        return count($this->collection);
    } // SIZE
} // BAG

class Set extends Bag
{
	/* Prida prvek do mnoziny. */
    public function add($item)
	{
        for ($i = 0; $i < count($this->collection); $i++)
			if ($this->collection[$i] == $item)
				return;
		
        array_push($this->collection, $item);
    } // ADD
} // SET
