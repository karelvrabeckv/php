<?php

namespace HW\Tests;
namespace HW\Lib;

use PHPUnit\Framework\TestCase;

class MathUtilsTest extends TestCase
{
	// ========================================
	// Testovani souctu cisel v seznamu.
	// ========================================
	
	/* Seznam je prazdny. */
    public function testSumOfEmptyList()
	{
		$result = (new MathUtils())->sum(array());
		$this->assertEquals(0, $result);
	} // TEST SUM OF EMPTY LIST

	/* Seznam je neprazdny. */
    public function testSumOfNotEmptyList()
	{
		$result = (new MathUtils())->sum(array('1', '2', '3'));
		$this->assertEquals(6, $result);
	} // TEST SUM OF NOT EMPTY LIST
	
	// ========================================
	// Testovani linearni rovnice.
	// ========================================
	
	/* Linearni clen je nenulovy. */
    public function testLinearNotNullCoefficient()
	{
		$result = (new MathUtils())->solveLinear(1, 0);
		$this->assertEquals(0, $result);
	} // TEST LINEAR NOT NULL COEFFICIENT
	
	/* Linearni clen je nulovy. */
    public function testLinearNullCoefficient()
	{
		$this->expectException(\InvalidArgumentException::class);
		$result = (new MathUtils())->solveLinear(0, 1);
	} // TEST LINEAR NULL COEFFICIENT
	
	// ========================================
	// Testovani kvadraticke rovnice.
	// ========================================

	/* Kvadraticky clen je nulovy. */
    public function testQuadraticNullCoefficient()
	{
		$this->expectException(\InvalidArgumentException::class);
		$result = (new MathUtils())->solveQuadratic(0, 1, 2);		
	} // TEST QUADRATIC NULL COEFFICIENT
	
	/* Diskriminant je kladny. */
    public function testQuadraticPositiveDiscriminant()
	{
		$result = (new MathUtils())->solveQuadratic(1, 4, 1);
		$this->assertEquals([-2 + sqrt(3), -2 - sqrt(3)], $result);
	} // TEST QUADRATIC POSITIVE DISCRIMINANT
	
	/* Diskriminant je nulovy. */
    public function testQuadraticNullDiscriminant()
	{
		$result = (new MathUtils())->solveQuadratic(1, 2, 1);
		$this->assertEquals([-1, -1], $result);
	} // TEST QUADRATIC NULL DISCRIMINANT
	
	/* Diskriminant je zaporny. */
    public function testQuadraticNegativeDiscriminant()
	{
		$this->expectException(\InvalidArgumentException::class);
		$result = (new MathUtils())->solveQuadratic(1, 1, 1);
	} // TEST QUADRATIC NEGATIVE DISCRIMINANT
}
