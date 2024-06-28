<?php

namespace Books\Requests;

use Books\Db;

class DEL
{
	/* Smaze danou knizku. */
    public static function del($id): void
    {
		$db = Db::get();
		$statement = $db->prepare('
		
			DELETE FROM `book` WHERE id = :id
			
		');
		$statement->execute([
			'id' => $id
		]);
	} // DEL
}
