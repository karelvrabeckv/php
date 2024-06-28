<?php

namespace Iterator;

use Node;

class PostOrderIterator extends AbstractOrderIterator
{
    public function __construct(Node $root)
    {
		$this->post_order($root);
		$this->actual = $this->sequence[$this->i];
    } // CONSTRUCT

	public function post_order($node)
	{
		if ($node == NULL) return;
		$this->post_order($node->getLeft());
		$this->post_order($node->getRight());
		array_push($this->sequence, $node);
	} // POST ORDER
} // POST ORDER ITERATOR
