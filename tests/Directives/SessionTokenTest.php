<?php

namespace Segwitz\ShopifyApp\Test\Directives;

use Segwitz\ShopifyApp\Directives\SessionToken;
use Segwitz\ShopifyApp\Test\TestCase;

class SessionTokenTest extends TestCase
{
    public function testDirective(): void
    {
        $blade = resolve('blade.compiler');
        $result = $blade->compileString('{{ @sessionToken }}');

        $this->assertStringContainsString((new SessionToken())(), $result);
    }
}
