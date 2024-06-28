<?php

use Books\Middleware\JsonBodyParserMiddleware;
use Books\Db;
use Books\Requests\GET;
use Books\Requests\POST;
use Books\Requests\PUT;
use Books\Requests\DEL;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

/* Vytvori aplikaci pro zpracovani HTTP pozadavku. */
$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->add(new JsonBodyParserMiddleware());

/* Inicializuje databazi. */
Db::create();

/* Nastavi GET pro vsechny knihy. */
$app->get('/books', function(Request $request, Response $response)
{
    $json = json_encode(GET::get(), JSON_PRETTY_PRINT);
    $response->getBody()->write($json);
	
    return $response->withHeader('Content-type', 'application/json');
});

/* Nastavi GET pro konkretni knihu. */
$app->get('/books/{id}', function(Request $request, Response $response, $args)
{
	$data = GET::detail($args['id']);
	
	/* Pozadovany zaznam neexistuje. */
	if (!$data) return $response->withStatus(404);

	$json = json_encode($data, JSON_PRETTY_PRINT);
	$response->getBody()->write($json);
	
    return $response->withHeader('Content-type', 'application/json');
});

/* Nastavi POST pro vlozeni knihy. */
$app->post('/books', function(Request $request, Response $response)
{
	/* Autentizace uzivatele. */
	$authorization = $request->getHeader('Authorization');
	if (!$authorization || $authorization[0] != ('Basic ' . base64_encode('admin:pas$word')))
		return $response->withStatus(401);
	
	/* Ziskani dat ze vstupu. */
	$name = $request->getParsedBody()['name'] ?? false;
	$author = $request->getParsedBody()['author'] ?? false;
	$publisher = $request->getParsedBody()['publisher'] ?? false;
	$isbn = $request->getParsedBody()['isbn'] ?? false;
	$pages = $request->getParsedBody()['pages'] ?? false;
	
	/* Chybejici data. */
	if (!$name || !$author || !$publisher || !$isbn || !$pages)
		return $response->withStatus(400);	
	
	/* Vlozeni zaznamu do databaze. */
	$id = POST::insert($name, $author, $publisher, $isbn, $pages);
	
	return $response->withStatus(201)->withHeader('Location', '/books/' . $id);
});

/* Nastavi PUT pro aktualizaci knihy. */
$app->put('/books/{id}', function(Request $request, Response $response, $args)
{
	/* Autentizace uzivatele. */
	$authorization = $request->getHeader('Authorization');
	if (!$authorization || $authorization[0] != ('Basic ' . base64_encode('admin:pas$word')))
		return $response->withStatus(401);
	
	/* Pozadovany zaznam neexistuje. */
	$data = GET::detail($args['id']);
	if (!$data) return $response->withStatus(404);	
	
	/* Ziskani dat ze vstupu. */
	$name = $request->getParsedBody()['name'] ?? false;
	$author = $request->getParsedBody()['author'] ?? false;
	$publisher = $request->getParsedBody()['publisher'] ?? false;
	$isbn = $request->getParsedBody()['isbn'] ?? false;
	$pages = $request->getParsedBody()['pages'] ?? false;
	
	/* Chybejici data. */
	if (!$name || !$author || !$publisher || !$isbn || !$pages)
		return $response->withStatus(400);	
	
	/* Upraveni zaznamu v databazi. */
	PUT::update($args['id'], $name, $author, $publisher, $isbn, $pages);
	
	return $response->withStatus(204);
});

/* Nastavi DELETE pro smazani knihy. */
$app->delete('/books/{id}', function(Request $request, Response $response, $args)
{
	/* Autentizace uzivatele. */
	$authorization = $request->getHeader('Authorization');
	if (!$authorization || $authorization[0] != ('Basic ' . base64_encode('admin:pas$word')))
		return $response->withStatus(401);
	
	/* Pozadovany zaznam neexistuje. */
	$data = GET::detail($args['id']);
	if (!$data) return $response->withStatus(404);
	
	/* Smazani zaznamu z databaze. */
	DEL::del($args['id']);
	
	return $response->withStatus(204);
});

/* Spusteni aplikace. */
$app->run();
