<?php

use MyApi\App;
use MyApi\Service\CacheService;
use MyApi\Service\AuditService;
use MyApi\Service\ImageService;
use Psr\Container\ContainerInterface;

return [
    App::class => function (ContainerInterface $c) {
        return new App($c->get('config'));
    },

    'config' => fn () => require __DIR__ . '/bootstrap.php',

    CacheService::class => fn (ContainerInterface $c) =>
        new CacheService(
            $c->get('config')['cache']['dir'],
            $c->get('config')['cache']['ttl']
        ),

    ImageService::class => fn (ContainerInterface $c) =>
        new ImageService(
            $c->get('config')['upload_dir'],
            $c->get('config')['public_url']
        ),

    AuditService::class => fn (ContainerInterface $c) =>
        new AuditService($c->get(App::class)),
];
