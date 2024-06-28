<?php

namespace App;

use Fpdf\Fpdf;

class Renderer extends Fpdf
{
    public function render(Invoice $invoice)
    {
		/* Zakladni nalezitosti ohledne dokumentu. */
		$this->AddPage();
		$this->setTitle('Invoice', true);
		$this->SetFont('Arial', '', 10);
		
		/* Vystredi obsah. */
		$width = $this->getPageWidth();
		$margin = ($width - 160) / 2;
		$this->SetMargins($margin, $margin);
		
		/* Informace o fakture. */
		$this->Ln();
		$this->Cell(80, 6, ''); // odradkovani
		$this->Ln();
		$dash = html_entity_decode('&#x2013;', ENT_COMPAT, 'cp1252'); // pomlcka
		$this->Cell(40, 15, 'FAKTURA ' . $dash . ' DOKLAD c. ' . $invoice->getNumber());
		$this->Ln();

		/* Hlavicka prvni tabulky. */
		$this->SetFont('Arial', 'B', 11);
		$head = array('Dodavatel', 'Odberatel');
		for ($i = 0; $i < count($head); $i++)
			$this->Cell(80, 6, $head[$i], 'LTR');
		$this->Ln();
		
		/* Telo prvni tabulky. */
		$this->SetFont('Arial', '', 11);
		$table = array(
			'', '',
			
			$invoice->getSupplier()->getName(),
			$invoice->getCustomer()->getName(),
			
			$invoice->getSupplier()->getAddress()->getStreet() . " " .
			$invoice->getSupplier()->getAddress()->getNumber(),
			$invoice->getCustomer()->getAddress()->getStreet() . " " .
			$invoice->getCustomer()->getAddress()->getNumber(),
			
			$invoice->getSupplier()->getAddress()->getZipCode() . " " .
			$invoice->getSupplier()->getAddress()->getCity(),
			$invoice->getCustomer()->getAddress()->getZipCode() . " " .
			$invoice->getCustomer()->getAddress()->getCity(),
			
			'', '',
			
			$invoice->getSupplier()->getVatNumber(),
			$invoice->getCustomer()->getVatNumber(),
			
			'', '',
			
			$invoice->getSupplier()->getAddress()->getPhone(),
			$invoice->getCustomer()->getAddress()->getPhone(),
			
			$invoice->getSupplier()->getAddress()->getEmail(),
			$invoice->getCustomer()->getAddress()->getEmail(),	
		);
		
		/* Generuje bunky. */
		for ($i = 0; $i < count($table); $i += 2)
		{
			// pokud neni zadan e-mail nebo telefonni cislo, vytvori se prazdny radek
			if ($i + 2 >= count($table))
			{
				$this->Cell(80, 5.5, $table[$i], 'LRB');
				$this->Cell(80, 5.5, $table[$i + 1], 'LRB');
			} // if
			else 
			{
				$this->Cell(80, 5.5, $table[$i], 'LR');
				$this->Cell(80, 5.5, $table[$i + 1], 'LR');
			} // else
			$this->Ln();
		} // for
		$this->Ln();
	
		/* Hlavicka druhe tabulky. */
		$this->SetFont('Arial', 'B', 11);
		$head = array('Polozka', 'Pocet m.j.', 'Cena za m.j.', 'Celkem');
		for ($i = 0; $i < count($head); $i++)
			$this->Cell(40, 5.5, $head[$i], 'LTR');
		$this->Ln();

		/* Telo druhe tabulky. */
		$this->SetFont('Arial', '', 11);
		for ($i = 0; $i < count($invoice->getItems()); $i++)
		{
			$this->Cell(40, 5.5, $invoice->getItems()[$i]->getDescription(), 'LTR');
			$this->Cell(40, 5.5, $invoice->getItems()[$i]->getQuantity(), 'LTR', 0, 'R');
			$this->Cell(40, 5.5, number_format($invoice->getItems()[$i]->getUnitPrice(), 2, ',', ' '), 'LTR', 0, 'R');
			$this->Cell(40, 5.5, number_format($invoice->getItems()[$i]->getTotalPrice(), 2, ',', ' '), 'LTR', 0, 'R');
			$this->Ln();
		} // for	
		$this->SetFont('Arial', 'B', 11);
		$this->Cell(40, 5.5, 'Celkem', 'LTB', 0, 'L');
		$this->Cell(40, 5.5, '', 'TB', 0);
		$this->Cell(40, 5.5, '', 'RTB', 0);
		$this->Cell(40, 5.5, number_format($invoice->getTotalPrice(), 2, ',', ' '), 1, 0, 'R');

        return $this->Output();
    }
}
