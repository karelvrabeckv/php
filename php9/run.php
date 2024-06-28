<?php

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

require __DIR__.'/vendor/autoload.php';

// ========================================

function text(Crawler $crawler, string $selector)
{
    $new = $crawler->filter($selector);
    if (count($new))
	{
        return trim($new->text());
    } // if

    return null;
} // TEXT

// ========================================

/**
 * @param string $query - query string e.g. 'Beats Studio 3'
 * @return array
 */
function scrape(string $query)
{
	$parts = explode(" ", $query); // ziska jednotliva slova zadaneho vyrazu
    $results = []; // pole pro vsechny zaznamy
	$client = new Client(); // klient pro obsluhu HTTP pozadavku
	
	/* Scrapuje Electro World. */
	scrapeElectroWorld($results, $parts, $client);

	/* Scrapuje Alzu. */
	scrapeAlza($results, $parts, $client);

	return $results;
} // SCRAPE

// ========================================

function scrapeElectroWorld(&$results, $parts, $client)
{
	/* Prida jednotlive casti zadaneho vyrazu do URL adresy. */
	$url = 'https://www.electroworld.cz';
	$query = $url . '/vysledky-vyhledavani?q=';
	for ($i = 0; $i < count($parts); $i++)
	{
		if ($parts[$i] == end($parts))
		{
			$query .= $parts[$i];
			break;
		} // if
		$query .= $parts[$i] . '+';
	} // for
	$query .= '&do=searchBox-searchForm-submit';
	
	/* Odesle HTTP pozadavek a ziska vsechna dulezita data. */
	$crawler = $client->request('GET', $query);
	$crawler->filter('.product-box')->each(function ($profile) use (&$results, $url, $client, $crawler)
	{
		$name = text($profile, '.product-box__heading'); // nazev
		$price = text($profile, '.product-box__price'); // cena
		
		/* Odstrani formatovani penezni castky. */
		$price = htmlentities($price, null, 'UTF-8');
		$price = str_replace(["&nbsp;", "Kč"], "", $price);
		
		$link = $url . $profile->filter('.product-box__link')->attr('href'); // detail
		$eshop = "Electro World"; // znacka
		$crawler = $client->request('GET', $link); // presune se na detail produktu
		$description = $crawler->filterXpath('//meta[@name="description"]')->attr('content'); // popis

		/* Ziskana data ulozi do vysledneho pole. */
		array_push(
			$results, 
			[
				'name' => $name,
				'price' => $price,
				'link' => $link,
				'eshop' => $eshop,
				'description' => $description
			]
		);
	});
} // SCRAPE ELECTRO WORLD

// ========================================

function scrapeAlza(&$results, $parts, $client)
{
	/* Prida jednotlive casti zadaneho vyrazu do URL adresy. */
	$url = 'https://www.alza.cz';
	$query = $url . '/search.htm?exps=';
	for ($i = 0; $i < count($parts); $i++)
	{
		if ($parts[$i] == end($parts))
		{
			$query .= $parts[$i];
			break;
		} // if
		$query .= $parts[$i] . '%20';
	} // for

	/* Odesle HTTP pozadavek a ziska vsechna dulezita data. */
	$crawler = $client->request('GET', $query);
	$crawler->filter('.box')->each(function ($profile) use (&$results, $url)
	{
		$name = text($profile, '.name'); // nazev
		$price = text($profile, '.c2'); // cena
		
		/* Odstrani formatovani penezni castky. */
		$price = htmlentities($price, null, 'UTF-8');
		$price = str_replace(["&nbsp;", ",-"], "", $price);
		
		$link = $url . $profile->filter('.fb a')->attr('href'); // detail
		$eshop = "Alza.cz"; // znacka
		$description = text($profile, '.Description'); // popis

		/* Ziskana data ulozi do vysledneho pole. */
		array_push(
			$results, 
			[
				'name' => $name,
				'price' => $price,
				'link' => $link,
				'eshop' => $eshop,
				'description' => $description
			]
		);
	});
} // SCRAPE ALZA

// ========================================

function orderByPrice($results)
{
	usort($results, function($a, $b) {
		return strnatcmp($a['price'], $b['price']);
	});
	return $results;
} // ORDER BY PRICE

// ========================================

/**
 * @param string $query   - query string e.g. 'Beats Studio 3'
 * @param array  $results - input product collection
 * @return array
 */
function filter(string $query, array $results)
{
	$parts = explode(" ", $query); // ziska jednotliva slova zadaneho vyrazu
	$new_results = []; // pole pro relevantni zaznamy
	
	/* Odstrani nepotrebne polozky. */
	foreach ($results as $result)
	{
		$contains = true;
		for ($i = 0; $i < count($parts); $i++)
			if (strpos($result['name'], $parts[$i]) === false) // pokud v nazvu nejsou slova vyrazu
				$contains = false;
		
		if ($contains) array_push($new_results, $result);
	} // for
	
	/* Seradi vzestupne podle ceny. */
	$new_results = orderByPrice($new_results);
	
	/* Cenu prevede do penezniho formatu. */
	foreach ($new_results as &$result)
		$result['price'] = number_format($result['price'], 0, '', ' ') . " Kč";
	
    return $new_results;
} // FILTER

// ========================================

/**
 * input array $results show contain following keys
 * - 'name'
 * - 'price'
 * - 'link' - link to product detail page
 * - 'eshop' - eshop identifier e.g. 'alza'
 * - 'description'
 *
 * @param array $results
 */
function printResults(array $results, bool $includeDescription = false)
{
    $width = [
        'name' => 0,
        'price' => 0,
        'link' => 0,
        'eshop' => 0,
        'description' => 0,
    ];
    foreach ($results as $result)
	{
        foreach ($result as $key => $value)
		{
            $width[$key] = max(mb_strlen($value), $width[$key]);
        }
    }
    echo '+'.str_repeat('-', 2 + $width['name']);
    echo '+'.str_repeat('-', 2 + $width['price']);
    echo '+'.str_repeat('-', 2 + $width['link']);
    echo '+'.str_repeat('-', 2 + $width['eshop'])."+\n";
    foreach ($results as $result)
	{
        echo '| '.$result['name'].str_repeat(' ', $width['name'] - mb_strlen($result['name'])).' ';
        echo '| '.$result['price'].str_repeat(' ', $width['price'] - mb_strlen($result['price'])).' ';
        echo '| '.$result['link'].str_repeat(' ', $width['link'] - mb_strlen($result['link'])).' ';
        echo '| '.$result['eshop'].str_repeat(' ', $width['eshop'] - mb_strlen($result['eshop'])).' ';
        echo "|\n";
        echo '+'.str_repeat('-', 2 + $width['name']);
        echo '+'.str_repeat('-', 2 + $width['price']);
        echo '+'.str_repeat('-', 2 + $width['link']);
        echo '+'.str_repeat('-', 2 + $width['eshop'])."+\n";
        if ($includeDescription)
		{
            echo '| '.$result['description'].str_repeat(' ',
                    max(0, 7 + $width['name'] + $width['price'] + $width['link'] - mb_strlen($result['description'])));
            echo "|\n";
            echo str_repeat('-', 10 + $width['name'] + $width['price'] + $width['link'])."\n";
        }
    }
} // PRINT RESULTS

// ========================================

if (count($argv) !== 2)
{
    echo "Usage: php run.php <query>\n";
    exit(1);
}

$query = $argv[1];
$results = scrape($query);
$results = filter($query, $results);
printResults($results, true);
