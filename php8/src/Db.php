<?php

namespace Books;

class Db
{
    protected static $pdo = null;

	/* Vytvori databazi. */
    public static function get(): \PDO
    {
        return self::$pdo ?? (self::$pdo = new \PDO(
                'sqlite:hw-08.db',
                null,
                null,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                ]
            ));
    } // GET
	
	/* Vytvori tabulku pro knizky. */
    public static function create(): void
	{
        $db = Db::get();
        $db->query('
		
			CREATE TABLE IF NOT EXISTS `book` (
				id         INTEGER      PRIMARY KEY  AUTOINCREMENT,
				name       VARCHAR(50)  NOT NULL,
				author     VARCHAR(50)  NOT NULL,
				publisher  VARCHAR(50)  NOT NULL,
				isbn       VARCHAR(30)  NOT NULL,
				pages      INTEGER      NOT NULL
			);
			
		');
    } // CREATE
}
