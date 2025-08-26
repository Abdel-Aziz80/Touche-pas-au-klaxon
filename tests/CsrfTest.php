<?php

declare(strict_types=1);

use App\Helpers\Csrf;
use PHPUnit\Framework\TestCase;

final class CsrfTest extends TestCase
{
    public function testTokenAndCheck(): void
    {
        $token = Csrf::token();
        $this->assertNotEmpty($token);
        $this->assertTrue(Csrf::check($token));
        $this->assertFalse(Csrf::check('fake'));
    }
}
