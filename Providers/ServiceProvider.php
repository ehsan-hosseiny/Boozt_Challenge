<?php

namespace App\Providers;


use App\Core\Container;

use App\Interfaces\OrderRepositoryInterface;
use App\Services\OrderRepositoryService;

class ServiceProvider
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function boot()
    {
        $this->container->set(OrderRepositoryInterface::class, OrderRepositoryService::class);
        return $this->container;
    }

}