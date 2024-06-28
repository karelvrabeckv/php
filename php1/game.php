<?php

const DEAD = '.';
const ALIVE = 'X';

// ========================================

$array_in; // vstupni 2D pole
$rows; $cols; // pocet radku a sloupcu vstupniho 2D pole

function readInput($string) {
	/* nastaveni promennych jako globalnich */
	global $array_in, $rows, $cols;

	/* vytvoreni vstupniho 2D pole */
	$array_in = explode("\n", $string); // rozdeli vstupni retezec na radky (1. dimenze)
	foreach ($array_in as &$i)
		$i = str_split($i); // rozdeli radky na znaky (2. dimenze)

	/* zjisteni rozmeru 2D pole */
	$rows = count($array_in); // spocita pocet radku
	$cols = count($array_in[0]); // spocita pocet sloupcu

	/* navrat vstupniho 2D pole */
	return $array_in;
}

// ========================================

$string; // vystupni retezec

function writeOutput($matrix) {
    /* nastaveni promennych jako globalnich */
    global $string;

    /* prevod 2D pole na retezec */
    foreach ($matrix as &$i)
        $i = implode($i); // spoji znaky do radku (2. dimenze)
    $string = implode("\n", $matrix); // spoji radky do vystupniho retezce (1. dimenze)

	/* navrat vystupniho retezce */
    return $string;
}

// ========================================

$array_out = []; // vystupni 2D pole

function gameStep($matrix) {
	/* nastaveni promennych jako globalnich */
	global $array_out, $rows, $cols;
	
	/* naplneni vystupniho 2D pole mrtvymi bunkami */
	for ($i = 0; $i < $rows; $i++) {
		$array_out[$i] = []; // deklarace kazdeho radku jako pole znaku (1. dimenze)
		for ($j = 0; $j < $cols; $j++)
			array_push($array_out[$i], DEAD); // vkladani znaku do sloupecku (2. dimenze)
	} // for
	
	/* rizeni bunek podle jejich sousedu */
	for ($i = 0; $i < $rows; $i++) {
		for ($j = 0; $j < $cols; $j++) {
			$neighbours = 0;
			
			/* prohlizeni sousedu ve vzdalenosti jednoho policka */
			for ($k = $i - 1; $k <= $i + 1; $k++) {
				for ($l = $j - 1; $l <= $j + 1; $l++) {
					/* mezni pripady */
					if (($k < 0) || ($k >= $rows) ||
					    ($l < 0) || ($l >= $cols) ||
					    (($k == $i) && ($l == $j)))
					   continue;
					
					/* ziva sousedni bunka */
					if ($matrix[$k][$l] == ALIVE)
						$neighbours++;
				} // for
			} // for
			
			/* ziva bunka */
			if ($matrix[$i][$j] == ALIVE) {
				/* pripady umrti */
				if (($neighbours < 2) || ($neighbours > 3))
					$array_out[$i][$j] = DEAD;
				
				/* pripady preziti */
				if (($neighbours == 2) || ($neighbours == 3))
					$array_out[$i][$j] = ALIVE;
			} // if
			
			/* mrtva bunka */
			if ($matrix[$i][$j] == DEAD) {
				/* pripady oziveni */
				if ($neighbours == 3)
					$array_out[$i][$j] = ALIVE;
			} // if
		} // for
	} // for
	
	return $array_out;
}
