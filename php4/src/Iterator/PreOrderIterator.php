<?php

namespace Iterator;

use Node;

class PreOrderIterator extends AbstractOrderIterator
{
    public function __construct(Node $root)
    {
		$this->pre_order($root);
		$this->actual = $this->sequence[$this->i];
    } // CONSTRUCT

	public function pre_order($node)
	{
		if ($node == NULL) return;
		array_push($this->sequence, $node);
		$this->pre_order($node->getLeft());
		$this->pre_order($node->getRight());
	} // PRE ORDER
} // PRE ORDER ITERATOR
