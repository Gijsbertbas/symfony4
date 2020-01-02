<?php
/**
 * Created by PhpStorm.
 * User: gijs
 * Date: 02/01/2020
 * Time: 13:17
 */

namespace App\Tests\Security;

use App\Security\TokenGenerator;
use PHPUnit\Framework\TestCase;

class TokenGeneratorTest extends TestCase
{

    public function testTokenGeneration()
    {
        $tokenGen = new TokenGenerator();
        $token = $tokenGen->getRandomSecureToken(30);

        $this->assertEquals(30, strlen($token));
        $this->assertEquals(1,preg_match("/[A-Za-z0-9]/", $token));
        $this->assertTrue(ctype_alnum($token), 'Invalid tokens in token');
    }

}