<?php

namespace Books\Requests;

use Books\Db;

class PUT
{
	/* Aktualizuje danou knizku. */
    public static function update($id, $name, $author, $publisher, $isbn, $pages): void
    {
		$db = Db::get();
		$statement = $db->prepare('
		
			UPDATE `book` SET
			name = :name,
			author = :author,
			publisher = :publisher,
			isbn = :isbn,
			pages = :pages
			WHERE id = :id
			
		');
		$statement->execute([
			'name' => $name,
			'author' => $author,
			'publisher' => $publisher,
			'isbn' => $isbn,
			'pages' => $pages,
			'id' => $id
		]);
	} // UPDATE
}
