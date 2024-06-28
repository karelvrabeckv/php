<?php

namespace App\Model;

use App\Db;

class Account
{
    /** @var integer */
    protected $id;

    /** @var string */
    protected $number;

    /** @var string */
    protected $code;

    /**
     * Account constructor.
     *
     * @param int    $id
     * @param string $number
     * @param string $code
     */
    public function __construct(int $id, string $number, string $code)
    {
        $this->id = $id;
        $this->number = $number;
        $this->code = $code;
    }

    /**
     * Creates DB table using CREATE TABLE ...
     */
    public static function createTable(): void
    {
        $db = Db::get();
        $db->query('
		
			CREATE TABLE IF NOT EXISTS `account` (
				a_id     INTEGER     PRIMARY KEY AUTOINCREMENT,
				a_number VARCHAR(15) NOT NULL,
				a_code   VARCHAR(15) NOT NULL
			);
			
			ALTER TABLE `transaction` ADD
			FOREIGN KEY (t_from) REFERENCES account(t_from), 
			FOREIGN KEY (t_to) REFERENCES account(t_to);
			
		');
    }

    /**
     * Drops DB table using DROP TABLE ...
     */
    public static function dropTable(): void
    {
        $db = Db::get();
        $db->query('
		
			DROP TABLE IF EXISTS `account`
			
		');
    }

    /**
     * Find account record by number and bank code
     *
     * @param string $number
     * @param string $code
     * @return Account|null
     */
    public static function find(string $number, string $code): ?self
    {
        $db = Db::get();
        $statement = $db->prepare('
		
			SELECT * FROM `account` WHERE a_number = :number AND a_code = :code
			
		');
		$statement->execute([
			'number' => $number,
			'code' => $code
		]);
		
		/* Ziska hledany zaznam. */
		$row = $statement->fetch();
		
		if (empty($row))
			return null;
		
		return new Account($row['a_id'], $row['a_number'], $row['a_code']);
    }

    /**
     * Inserts new account record and returns its instance; or returns existing account instance
     *
     * @param string $number
     * @param string $code
     * @return static
     */
    public static function findOrCreate(string $number, string $code): self
    {	
        $db = Db::get();
        $statement = $db->prepare('
		
			SELECT * FROM `account` WHERE a_number = :number AND a_code = :code
			
		');
		$statement->execute([
			'number' => $number,
			'code' => $code
		]);
		
		/* Ziska hledany zaznam. */
		$row = $statement->fetch();
		
		/* Vytvori novy zaznam, pokud nic nenasel. */
		if (empty($row))
		{
			$statement = $db->prepare('
			
				INSERT INTO `account` (a_number, a_code) VALUES (:number, :code)
				
			');
			$statement->execute([
				'number' => $number,
				'code' => $code
			]);
			
			return new Account($db->lastInsertId(), $number, $code);
		} // if
		
		return new Account($row['a_id'], $row['a_number'], $row['a_code']);
    }

    /**
     * Returns array of Transaction instances related to this Account, consider both transaction direction
     *
     * @return Transaction[]|array
     */
    public function getTransactions(): array
    {
        $db = Db::get();
        $statement = $db->prepare('
		
			SELECT * FROM `transaction` WHERE t_from = :from OR t_to = :to
			
		');
		$statement->execute([
			'from' => $this->id,
			'to' => $this->id
		]);
		
		/* Pole pro instance transakci. */
		$transactions = [];
		
		/* Projde vsechny transakce tykajici se daneho uctu. */
		foreach ($statement->fetchAll() as $row)
		{
			/* Ziska ucet odesilatele. */
			$statement = $db->prepare('
			
				SELECT * FROM `account` WHERE a_id = :id
				
			');
			$statement->execute([
				'id' => $row['t_from']
			]);
			$sender = $statement->fetch();

			/* Ziska ucet prijemce. */
			$statement = $db->prepare('
			
				SELECT * FROM `account` WHERE a_id = :id
				
			');
			$statement->execute([
				'id' => $row['t_to']
			]);
			$receiver = $statement->fetch();			
			
			/* Vlozi novou instanci transakce do pole. */
			array_push($transactions, new Transaction(
				new Account($sender['a_id'], $sender['a_number'], $sender['a_code']),
				new Account($receiver['a_id'], $receiver['a_number'], $receiver['a_code']),
				$row['t_amount']
			));
		} // foreach
		
		return $transactions;
    }

    /**
     * Returns transaction sum (using SQL aggregate function)
     *
     * @return float
     */
    public function getTransactionSum(): float
	{
        $db = Db::get();
		
		/* Ziska prichozi castky. */
        $statement = $db->prepare('
		
			SELECT sum(t_amount) AS plus FROM `transaction` WHERE t_to = :to
			
		');
		$statement->execute([
			'to' => $this->id
		]);
		$to = $statement->fetch();
		
		/* Ziska odchozi castky. */
        $statement = $db->prepare('
		
			SELECT sum(t_amount) AS minus FROM `transaction` WHERE t_from = :from
			
		');
		$statement->execute([
			'from' => $this->id
		]);		
		$from = $statement->fetch();
		
		return $to['plus'] - $from['minus'];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Account
     */
    public function setId(int $id): Account
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return Account
     */
    public function setNumber(string $number): Account
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Account
     */
    public function setCode(string $code): Account
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Account string representation
     *
     * @return string
     */
    public function __toString()
    {
        return "{$this->number}/{$this->code}";
    }
}
