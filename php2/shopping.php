<?php

/* Zjisti cenu polozky. */
function getPrice($item)
{
	$matches;
	
	/* Ziska hodnoty s oznacenim koruny. */
	preg_match('/[0-9.,]*.?Kč/',            $item, $matches) ||
	preg_match('/[0-9.]*.?,-/',             $item, $matches) ||
	preg_match('/[0-9.,]*.?CZK.?[0-9.,]*/', $item, $matches);
	
	/* Odstrani oznaceni koruny. */
	$del = [' ,-', ',-', ' Kč', 'Kč', ' CZK', 'CZK ', 'CZK', '.'];
	$price = str_replace($del, '', $matches[0]);
	
	/* Desetinnou carku prepise na tecku. */
	return $price = str_replace([','], '.', $price);
} // GET PRICE

/* Seradi polozky vzestupne podle ceny. */
function sortList($list)
{
	$prices;
	
	/* Zjisti cenu kazde polozky. */
	for ($i = 0; $i < count($list); $i++)
	{
		global $prices;
		$price = getPrice($list[$i]);
		$prices[$list[$i]] = $price;
	} // for
	
	asort($prices); // seradi polozky vzestupne podle ceny
	return $list = array_keys($prices); // vrati pole serazenych polozek
} // SORT LIST

/* Spocita celkovou cenu vsech polozek. */
function sumList($list)
{
	$prices; $sum;
	
	/* Zjisti ceny vsech polozek a postupne je secte. */
	for ($i = 0; $i < count($list); $i++)
	{
		global $prices, $sum;
		$price = getPrice($list[$i]);
		$sum += $price;
	} // for
	
	return $sum;
} // SUM LIST

/* Osetreni vstupu. */
if (count($argv) !== 2)
{
	echo "Usage: php shopping.php <input>\n";
	exit(1);
} // if

/* Ziskani dat. */
$input = file_get_contents(end($argv)); // obsah souboru prevede na retezec
$list = explode("\n", $input); // retezec rozdeli na polozky
$list = sortList($list); // seradi polozky vzestupne podle ceny
print_r($list); // zobrazi obsah pole polozek
print_r(sumList($list)); // zobrazi celkovou cenu
