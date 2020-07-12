<?php


namespace App\Tests;


use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    /**
     * tests unitaires classiques sans use sf
     */
    public function testAreWorking(): void
    {
        self::assertEquals(3, 1+2);
    }
}
