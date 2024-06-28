<?php

namespace Books\Requests;

use Books\Db;

class GET
{
	/* Vrati vsechny knizky. */
    public static function get(): array
    {
        $db = Db::get();
        $statement = $db->prepare('
		
			SELECT id, name, author FROM `book`
			
		');
		$statement->execute();
		
		/* Ziska vsechny zaznamy. */
		$books = $statement->fetchAll(\PDO::FETCH_ASSOC);
		
		return $books;
    } // GET

	/* Vrati danou knizku. */
    public static function detail($id): array
    {
        $db = Db::get();
        $statement = $db->prepare('
		
			SELECT * FROM `book` WHERE id = :id
			
		');
		$statement->execute([
			'id' => $id
		]);
		
		/* Ziska dany zaznam. */
		$books = $statement->fetch(\PDO::FETCH_ASSOC);
		if (!$books) return array();
		
		return $books;
    } // DETAIL
}
