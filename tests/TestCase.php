<?php

namespace Vladmeh\XmlUtils\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Vladmeh\XmlUtils\XmlUtilsServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            XmlUtilsServiceProvider::class,
        ];
    }
}
