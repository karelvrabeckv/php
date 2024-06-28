<?php

namespace App;

use App\Invoice\Address;
use App\Invoice\BusinessEntity;
use App\Invoice\Item;

class Builder
{
    /** @var Invoice */
    protected $invoice;

    /* Konstruktor. */
    public function __construct()
    {
		/* Vytvori novou fakturu. */
		$this->invoice = new Invoice();
    }

    /**
     * @return Invoice
     */
    public function build(): Invoice
    {		
		return $this->invoice;
    }

    /**
     * @param string $number
     * @return $this
     */
    public function setNumber(string $number): self
    {
		/* Prida cislo do faktury. */
		$this->invoice->setNumber($number);
		
		return $this;
    }

    /**
     * @param string      $name
     * @param string      $vatNumber
     * @param string      $street
     * @param string      $number
     * @param string      $city
     * @param string      $zip
     * @param string|null $phone
     * @param string|null $email
     * @return $this
     */
    public function setSupplier(
        string $name,
        string $vatNumber,
        string $street,
        string $number,
        string $city,
        string $zip,
        ?string $phone = null,
        ?string $email = null
    ): self {
		/* Vytvori adresu dodavatele. */
		$address = new Address();		
		$address->setStreet($street);
		$address->setNumber($number);
		$address->setCity($city);
		$address->setZipCode($zip);
		$address->setPhone($phone);
		$address->setEmail($email);

		/* Vytvori dodavatele. */
		$supplier = new BusinessEntity();
		$supplier->setName($name);
		$supplier->setVatNumber($vatNumber);
		$supplier->setAddress($address);

		/* Prida dodavatele do faktury. */
		$this->invoice->setSupplier($supplier);

		return $this;
    }

    /**
     * @param string      $name
     * @param string      $vatNumber
     * @param string      $street
     * @param string      $number
     * @param string      $city
     * @param string      $zip
     * @param string|null $phone
     * @param string|null $email
     * @return $this
     */
    public function setCustomer(
        string $name,
        string $vatNumber,
        string $street,
        string $number,
        string $city,
        string $zip,
        ?string $phone = null,
        ?string $email = null
    ): self {
		/* Vytvori adresu odberatele. */
		$address = new Address();		
		$address->setStreet($street);
		$address->setNumber($number);
		$address->setCity($city);
		$address->setZipCode($zip);
		$address->setPhone($phone);
		$address->setEmail($email);

		/* Vytvori odberatele. */
		$customer = new BusinessEntity();
		$customer->setName($name);
		$customer->setVatNumber($vatNumber);
		$customer->setAddress($address);
	
		/* Prida odberatele do faktury. */
		$this->invoice->setCustomer($customer);
		
		return $this;
    }

    /**
     * @param string     $description
     * @param float|null $quantity
     * @param float|null $price
     * @return $this
     */
    public function addItem(
		string $description,
		?float $quantity,
		?float $price
	): self {
		/* Vytvori polozku. */
		$item = new Item();
		$item->setDescription($description);
		$item->setQuantity($quantity);
		$item->setUnitPrice($price);

		/* Prida polozku do faktury. */
		$this->invoice->addItem($item);

		return $this;
    }
}
