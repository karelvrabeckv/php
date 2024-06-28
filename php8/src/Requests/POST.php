<?php

namespace Books\Requests;

use Books\Db;

class POST
{
	/* Vlozi danou knizku. */
    public static function insert($name, $author, $publisher, $isbn, $pages): int
    {
		$db = Db::get();
		$statement = $db->prepare('
		
			INSERT INTO `book` (name, author, publisher, isbn, pages)
			VALUES (:name, :author, :publisher, :isbn, :pages)
			
		');
		$statement->execute([
			'name' => $name,
			'author' => $author,
			'publisher' => $publisher,
			'isbn' => $isbn,
			'pages' => $pages
		]);
		
		return $db->lastInsertId();
	} // INSERT
}
