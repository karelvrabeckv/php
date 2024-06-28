<?php

namespace HW\Tests;
namespace HW\Lib;

use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
	/* Vytvoreni uzivatele. */
	public function testCreateUser()
	{
		$storage = $this->createMock(Storage::class);
		$user_service = new UserService($storage);
		$id = $user_service->createUser("vrabekar", "vrabekar@fit.cvut.cz");
		$this->assertIsString($id);
	} // TEST CREATE USER
	
	/* Ziskani uzivatelskeho jmena, pokud dany uzivatel neexistuje. */
	public function testGetUsernameIfUserDoesNotExist()
	{
		$id = uniqid();
		
		$storage = $this->createMock(Storage::class);
		$storage->method('get')->with($id)->willReturn(null);
		
		$user_service = new UserService($storage);
		$this->assertNull($user_service->getUsername($id));
	} // TEST GET USERNAME IF USER DOES NOT EXIST
	
	/* Ziskani uzivatelskeho jmena, pokud dany uzivatel existuje. */
	public function testGetUsernameIfUserExists()
	{
		$id = uniqid();

		$storage = $this->createMock(Storage::class);
		$storage->method('get')->with($id)->willReturn('{
			"username": "vrabekar",
			"email": "vrabekar@fit.cvut.cz"
		}');
		
		$user_service = new UserService($storage);			
		$this->assertEquals("vrabekar", $user_service->getUsername($id));
	} // TEST GET USERNAME IF USER EXISTS

	/* Ziskani e-mailu, pokud dany uzivatel neexistuje. */
	public function testGetEmailIfUserDoesNotExist()
	{
		$id = uniqid();
		
		$storage = $this->createMock(Storage::class);
		$storage->method('get')->with($id)->willReturn(null);
		
		$user_service = new UserService($storage);
		$this->assertNull($user_service->getEmail($id));
	} // TEST GET EMAIL IF USER DOES NOT EXIST
	
	/* Ziskani e-mailu, pokud dany uzivatel existuje. */
	public function testGetEmailIfUserExists()
	{
		$id = uniqid();
		
		$storage = $this->createMock(Storage::class);
		$storage->method('get')->with($id)->willReturn('{
			"username": "vrabekar",
			"email": "vrabekar@fit.cvut.cz"
		}');
		
		$user_service = new UserService($storage);			
		$this->assertEquals("vrabekar@fit.cvut.cz", $user_service->getEmail($id));
	} // TEST GET EMAIL IF USER EXISTS
}
