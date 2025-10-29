<?php

// phinx.php
return [
  'paths' => ['migrations' => __DIR__ . '/migrations'],
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_environment' => 'development',
    'development' => [
      'adapter' => 'mysql',
      'host' => getenv('DB_HOST') ?: '127.0.0.1',
      'name' => getenv('DB_NAME') ?: 'd043fb41',
      'user' => getenv('DB_USER') ?: 'root',
      'pass' => getenv('DB_PASS') ?: '',
      'port' => getenv('DB_PORT') ?: 3306,
      'charset' => 'utf8mb4'
    ]
  ]
];
