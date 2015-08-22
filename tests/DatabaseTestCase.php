<?php

class DatabaseTestCase extends Orchestra\Testbench\TestCase {

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'tests');
        $app['config']->set('database.connections.tests', [
            'driver'   => 'sqlite',
            'database' => __DIR__.'/test.db',
            'prefix'   => ''
        ]);
    }

}
