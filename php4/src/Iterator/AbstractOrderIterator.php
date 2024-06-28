<?php

namespace Iterator;

use Node;

abstract class AbstractOrderIterator implements \Iterator
{
	protected $actual;
	protected $sequence = [];
	protected $i = 0;

    public function current()
    {
        return $this->actual;
    } // CURRENT

    public function next()
    {
		if ($this->i + 1 < count($this->sequence))
			$this->actual = $this->sequence[++$this->i];
		else
			$this->actual = NULL;
    } // NEXT

    public function key()
    {
        return $this->actual->getValue();
    } // KEY

    public function valid()
    {
        return $this->actual !== NULL;
    } // VALID

    public function rewind()
    {
		$this->actual = $this->sequence[$this->i = 0];
    } // REWIND
} // ABSTRACT ORDER ITERATOR
