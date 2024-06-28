<?php

namespace Iterator;

use Node;

class InOrderIterator extends AbstractOrderIterator
{
    public function __construct(Node $root)
    {
		$this->in_order($root);
		$this->actual = $this->sequence[$this->i];
    } // CONSTRUCT

	public function in_order($node)
	{
		if ($node == NULL) return;
		$this->in_order($node->getLeft());
		array_push($this->sequence, $node);
		$this->in_order($node->getRight());
	} // IN ORDER
} // IN ORDER ITERATOR
