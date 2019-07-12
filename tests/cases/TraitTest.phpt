<?php declare(strict_types=1);

namespace Tests\Surda\WebLoader;

use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class TraitTest extends TestCase
{
    public function testTrait()
    {
        Assert::true(TRUE);
    }
}

(new TraitTest())->run();