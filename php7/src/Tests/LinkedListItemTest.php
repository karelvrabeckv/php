<?php

namespace HW\Tests;
namespace HW\Lib;

use HW\Lib\LinkedList;
use PHPUnit\Framework\TestCase;

class LinkedListItemTest extends TestCase
{
    protected $list;

    public function setUp(): void
    {
        parent::setUp();
        $this->list = new LinkedList();
    }

	// ========================================
	// Testovani tridy LinkedListItem.
	// ========================================

	/* Nastaveni a vraceni hodnoty aktualni polozky. */
    public function testGetAndSetValue()
	{
		$item = new LinkedListItem("Karel");
		$item->setValue("Petr");
		$this->assertEquals("Petr", $item->getValue());
	} // TEST GET AND SET VALUE
	
	/* Nastaveni a vraceni hodnoty nasledujici polozky. */
    public function testGetAndSetNext()
	{
		$item1 = new LinkedListItem("Karel");
		$item2 = new LinkedListItem("Petr");
		
		$item1->setNext($item2);
		
		$this->assertEquals("Petr", $item1->getNext()->getValue());
	} // TEST GET AND SET NEXT

	/* Nastaveni a vraceni hodnoty predchazejici polozky. */
    public function testGetAndSetPrev()
	{
		$item1 = new LinkedListItem("Karel");
		$item2 = new LinkedListItem("Petr");
		
		$item2->setPrev($item1);
		
		$this->assertEquals("Karel", $item2->getPrev()->getValue());
	} // TEST GET AND SET PREV
	
	// ========================================
	// Testovani tridy LinkedList.
	// ========================================
	
	/* Nastaveni a vraceni hodnoty prvni polozky. */
    public function testGetAndSetFirst()
	{
		$item = new LinkedListItem("Karel");
		$this->list->setFirst($item);
		
		$this->assertEquals("Karel", $this->list->getFirst()->getValue());
	} // TEST GET AND SET FIRST
	
	/* Nastaveni a vraceni hodnoty posledni polozky. */
    public function testGetAndSetLast()
	{
		$item = new LinkedListItem("Petr");
		$this->list->setLast($item);
		
		$this->assertEquals("Petr", $this->list->getLast()->getValue());
	} // TEST GET AND SET LAST
	
	/* Vlozeni prvku na zacatek seznamu. */
	public function testPrependList()
	{
		/* Nastaveni seznamu a jeho polozek. */
		$item1 = new LinkedListItem("Karel");
		$item2 = new LinkedListItem("Petr");
		
		$item1->setNext($item2);
		$item2->setPrev($item1);
		
		$this->list->setFirst($item1);
		$this->list->setLast($item2);
		
		/* Testovani dane funkce. */
		$this->list->prependList("Josef");
		
		/* Pruchod seznamem v obou smerech. */
		$there = $back = [];
		$it = $this->list->getFirst();
		while ($it)
		{
			array_push($there, $it->getValue());
			
			if ($it->getPrev())
				array_unshift($back, $it->getPrev()->getValue());
			
			if (!$it->getNext())
				array_unshift($back, $it->getValue());
			
			$it = $it->getNext();
		} // while
		
		/* Vlastni testovani. */
		$expected_there = ["Josef", "Karel", "Petr"];
		$expected_back = array_reverse($expected_there);
		for ($i = 0; $i < count($expected_there); $i++)
		{
			$this->assertEquals($there[$i], $expected_there[$i]);
			$this->assertEquals($back[$i], $expected_back[$i]);
		} // for
	} // TEST PREPEND LIST
	
	/* Vlozeni prvku na konec seznamu. */
	public function testAppendList()
	{
		/* Nastaveni seznamu a jeho polozek. */
		$item1 = new LinkedListItem("Karel");
		$item2 = new LinkedListItem("Petr");
		
		$item1->setNext($item2);
		$item2->setPrev($item1);
		
		$this->list->setFirst($item1);
		$this->list->setLast($item2);
		
		/* Testovani dane funkce. */
		$this->list->appendList("Josef");
		
		/* Pruchod seznamem v obou smerech. */
		$there = $back = [];
		$it = $this->list->getFirst();
		while ($it)
		{
			array_push($there, $it->getValue());
			
			if ($it->getPrev())
				array_unshift($back, $it->getPrev()->getValue());
			
			if (!$it->getNext())
				array_unshift($back, $it->getValue());
			
			$it = $it->getNext();
		} // while
		
		/* Vlastni testovani. */
		$expected_there = ["Karel", "Petr", "Josef"];
		$expected_back = array_reverse($expected_there);
		for ($i = 0; $i < count($expected_there); $i++)
		{
			$this->assertEquals($there[$i], $expected_there[$i]);
			$this->assertEquals($back[$i], $expected_back[$i]);
		} // for
	} // TEST APPEND LIST
	
	/* Vlozeni prvku pred danou polozku. */
	public function testPrependItem()
	{
		/* Nastaveni seznamu a jeho polozek. */
		$item1 = new LinkedListItem("Karel");
		$item2 = new LinkedListItem("Petr");
		
		$item1->setNext($item2);
		$item2->setPrev($item1);
		
		$this->list->setFirst($item1);
		$this->list->setLast($item2);
		
		/* Testovani dane funkce. */
		$this->list->prependItem($item2, "Josef");
		
		/* Pruchod seznamem v obou smerech. */
		$there = $back = [];
		$it = $this->list->getFirst();
		while ($it)
		{
			array_push($there, $it->getValue());
			
			if ($it->getPrev())
				array_unshift($back, $it->getPrev()->getValue());
			
			if (!$it->getNext())
				array_unshift($back, $it->getValue());
			
			$it = $it->getNext();
		} // while
		
		/* Vlastni testovani. */
		$expected_there = ["Karel", "Josef", "Petr"];
		$expected_back = array_reverse($expected_there);
		for ($i = 0; $i < count($expected_there); $i++)
		{
			$this->assertEquals($there[$i], $expected_there[$i]);
			$this->assertEquals($back[$i], $expected_back[$i]);
		} // for
	} // TEST PREPEND ITEM	

	/* Vlozeni prvku za danou polozku. */
	public function testAppendItem()
	{
		/* Nastaveni seznamu a jeho polozek. */
		$item1 = new LinkedListItem("Karel");
		$item2 = new LinkedListItem("Petr");
		
		$item1->setNext($item2);
		$item2->setPrev($item1);
		
		$this->list->setFirst($item1);
		$this->list->setLast($item2);
		
		/* Testovani dane funkce. */
		$this->list->appendItem($item1, "Josef");
		
		/* Pruchod seznamem v obou smerech. */
		$there = $back = [];
		$it = $this->list->getFirst();
		while ($it)
		{
			array_push($there, $it->getValue());
			
			if ($it->getPrev())
				array_unshift($back, $it->getPrev()->getValue());
			
			if (!$it->getNext())
				array_unshift($back, $it->getValue());
			
			$it = $it->getNext();
		} // while
		
		/* Vlastni testovani. */
		$expected_there = ["Karel", "Josef", "Petr"];
		$expected_back = array_reverse($expected_there);
		for ($i = 0; $i < count($expected_there); $i++)
		{
			$this->assertEquals($there[$i], $expected_there[$i]);
			$this->assertEquals($back[$i], $expected_back[$i]);
		} // for
	} // TEST APPEND ITEM
}
