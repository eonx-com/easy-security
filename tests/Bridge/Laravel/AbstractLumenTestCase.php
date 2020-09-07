<?php

declare(strict_types=1);

namespace EonX\EasySecurity\Tests\Bridge\Laravel;

use EonX\EasyApiToken\Bridge\Laravel\EasyApiTokenServiceProvider;
use EonX\EasySecurity\Bridge\Laravel\EasySecurityServiceProvider;
use EonX\EasySecurity\Tests\AbstractTestCase;
use Laravel\Lumen\Application;

abstract class AbstractLumenTestCase extends AbstractTestCase
{
    /**
     * @var \Laravel\Lumen\Application
     */
    private $app;

    /**
     * @param null|string[] $providers
     * @param null|mixed[] $config
     */
    protected function getApplication(?array $providers = null, ?array $config = null): Application
    {
        if ($this->app !== null) {
            return $this->app;
        }

        $app = new Application(__DIR__);

        if ($config !== null) {
            \config($config);
        }

        $providers = \array_merge($providers ?? [], [
            EasyApiTokenServiceProvider::class,
            EasySecurityServiceProvider::class,
        ]);

        foreach ($providers as $provider) {
            $app->register($provider);
        }

        return $this->app = $app;
    }
}
